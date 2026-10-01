/**
 * "Save to Dropbox" for visitors.
 *
 * The visitor authorizes with *their own* Dropbox account (OAuth 2 code flow with
 * PKCE, no app secret), then the PDF they would have downloaded is fetched from
 * this site and uploaded straight from the browser to their Dropbox. The access
 * token only lives in memory on this page - it is never sent to or stored by the
 * WordPress site.
 *
 * Dropbox's sign-in page is opened in the same tab (Dropbox's own recommended
 * browser flow). It returns to the plugin's callback URL, which hands the result
 * back to this page through sessionStorage and redirects here, where the upload
 * is finished.
 */
(function ($) {
	'use strict';

	if (typeof pgfw_dropbox_save_param === 'undefined' || !pgfw_dropbox_save_param.app_key) {
		return;
	}

	var param = pgfw_dropbox_save_param;
	var i18n = param.i18n || {};
	var STORAGE_KEY = 'pgfw_dropbox_save';
	var AUTHORIZE_URL = 'https://www.dropbox.com/oauth2/authorize';
	var TOKEN_URL = 'https://api.dropboxapi.com/oauth2/token';
	var UPLOAD_URL = 'https://content.dropboxapi.com/2/files/upload';

	var accessToken = '';
	var tokenExpiresAt = 0;

	function setStatus($btn, text, link) {
		var $status = $btn.next('.pgfw-save-to-dropbox-status');
		$status.text(text || '');
		if (link) {
			$status.append(' ').append(
				$('<a target="_blank" rel="noopener noreferrer"></a>').attr('href', link).text(i18n.open || 'Open Dropbox')
			);
		}
	}

	function setBusy($btn, busy) {
		$btn.toggleClass('is-busy', busy).attr('aria-disabled', busy ? 'true' : 'false');
	}

	function readJob() {
		try {
			return JSON.parse(window.sessionStorage.getItem(STORAGE_KEY) || 'null');
		} catch (e) {
			return null;
		}
	}

	function writeJob(job) {
		try {
			if (job) {
				window.sessionStorage.setItem(STORAGE_KEY, JSON.stringify(job));
			} else {
				window.sessionStorage.removeItem(STORAGE_KEY);
			}
			return true;
		} catch (e) {
			return false;
		}
	}

	function base64Url(bytes) {
		var binary = '';
		for (var i = 0; i < bytes.length; i++) {
			binary += String.fromCharCode(bytes[i]);
		}
		return window.btoa(binary).replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');
	}

	function randomString() {
		var bytes = new Uint8Array(48);
		window.crypto.getRandomValues(bytes);
		return base64Url(bytes);
	}

	// Dropbox-API-Arg must be ASCII, so escape everything else as \uXXXX.
	function headerSafeJson(value) {
		return JSON.stringify(value).replace(/[\u007f-￿]/g, function (c) {
			return '\\u' + ('000' + c.charCodeAt(0).toString(16)).slice(-4);
		});
	}

	function fileNameFrom(response, postId) {
		var disposition = response.headers.get('Content-Disposition') || '';
		var match = /filename\*=UTF-8''([^;]+)/i.exec(disposition) || /filename="?([^";]+)"?/i.exec(disposition);
		var name = match ? decodeURIComponent(match[1]) : 'document-' + postId + '.pdf';
		name = name.replace(/[\\/]/g, '-');
		return /\.pdf$/i.test(name) ? name : name + '.pdf';
	}

	// Same request the download icon makes, so the visitor gets exactly the PDF
	// (and access rules / password protection) they would have downloaded.
	function fetchPdf(url, postId) {
		return fetch(url, { credentials: 'same-origin' }).then(function (response) {
			var type = response.headers.get('Content-Type') || '';
			if (!response.ok || type.indexOf('pdf') === -1) {
				throw new Error(i18n.pdf_error || 'Could not generate the PDF.');
			}
			return response.blob().then(function (blob) {
				return { blob: blob, name: fileNameFrom(response, postId) };
			});
		});
	}

	function upload($btn) {
		setBusy($btn, true);
		setStatus($btn, i18n.preparing || 'Preparing PDF...');
		fetchPdf($btn.data('pdf-url'), $btn.data('post-id')).then(function (pdf) {
			setStatus($btn, i18n.saving || 'Saving to your Dropbox...');
			return fetch(UPLOAD_URL, {
				method: 'POST',
				headers: {
					Authorization: 'Bearer ' + accessToken,
					'Content-Type': 'application/octet-stream',
					'Dropbox-API-Arg': headerSafeJson({ path: '/' + pdf.name, mode: 'add', autorename: true })
				},
				body: pdf.blob
			});
		}).then(function (response) {
			if (response.status === 401) {
				accessToken = '';
				throw new Error(i18n.auth_expired || 'Your Dropbox sign-in expired. Please try again.');
			}
			if (!response.ok) {
				throw new Error(i18n.upload_error || 'Could not save the PDF to Dropbox.');
			}
			setStatus($btn, i18n.saved || 'Saved to your Dropbox.', 'https://www.dropbox.com/home');
		}).catch(function (err) {
			setStatus($btn, err && err.message ? err.message : (i18n.upload_error || 'Could not save the PDF to Dropbox.'));
		}).then(function () {
			setBusy($btn, false);
		});
	}

	function startAuthorization($btn) {
		var verifier = randomString();
		var state = randomString();
		window.crypto.subtle.digest('SHA-256', new TextEncoder().encode(verifier)).then(function (hash) {
			var saved = writeJob({
				verifier: verifier,
				state: state,
				post_id: String($btn.data('post-id')),
				return_url: window.location.href
			});
			if (!saved) {
				throw new Error();
			}
			window.location.href = AUTHORIZE_URL + '?' + $.param({
				client_id: param.app_key,
				response_type: 'code',
				redirect_uri: param.redirect_uri,
				code_challenge: base64Url(new Uint8Array(hash)),
				code_challenge_method: 'S256',
				token_access_type: 'online',
				state: state
			});
		}).catch(function () {
			setStatus($btn, i18n.auth_error || 'Dropbox sign-in failed. Please try again.');
			setBusy($btn, false);
		});
	}

	// Back from Dropbox: swap the authorization code for a token, then upload.
	function resumeAuthorization() {
		var job = readJob();
		if (!job || (!job.code && !job.error)) {
			return;
		}
		writeJob(null);

		var $btn = $('.pgfw-save-to-dropbox').filter(function () {
			return String($(this).data('post-id')) === job.post_id;
		}).first();
		if (!$btn.length) {
			return;
		}
		if ($btn[0].scrollIntoView) {
			$btn[0].scrollIntoView({ block: 'center' });
		}
		if (job.error || !job.code) {
			setStatus($btn, i18n.denied || 'Dropbox access was not granted.');
			return;
		}
		if (job.returned_state !== job.state) {
			setStatus($btn, i18n.auth_error || 'Dropbox sign-in failed. Please try again.');
			return;
		}

		setBusy($btn, true);
		setStatus($btn, i18n.preparing || 'Preparing PDF...');
		fetch(TOKEN_URL, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: $.param({
				grant_type: 'authorization_code',
				code: job.code,
				code_verifier: job.verifier,
				client_id: param.app_key,
				redirect_uri: param.redirect_uri
			})
		}).then(function (response) {
			if (!response.ok) {
				throw new Error();
			}
			return response.json();
		}).then(function (token) {
			if (!token || !token.access_token) {
				throw new Error();
			}
			accessToken = token.access_token;
			tokenExpiresAt = Date.now() + (parseInt(token.expires_in, 10) || 3600) * 1000 - 60000;
			upload($btn);
		}).catch(function () {
			setStatus($btn, i18n.auth_error || 'Dropbox sign-in failed. Please try again.');
			setBusy($btn, false);
		});
	}

	$(document).on('click', '.pgfw-save-to-dropbox', function (e) {
		e.preventDefault();
		var $btn = $(this);
		if ($btn.hasClass('is-busy')) {
			return;
		}

		if (accessToken && Date.now() < tokenExpiresAt) {
			upload($btn);
			return;
		}

		// PKCE needs Web Crypto, which browsers only offer on HTTPS (or localhost).
		if (!window.crypto || !window.crypto.subtle || !window.TextEncoder) {
			setStatus($btn, i18n.insecure || 'Saving to Dropbox needs the site to be opened over HTTPS.');
			return;
		}

		setBusy($btn, true);
		startAuthorization($btn);
	});

	$(resumeAuthorization);
})(jQuery);
