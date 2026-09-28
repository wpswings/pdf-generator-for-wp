/**
 * "Save to Google Drive" for visitors.
 *
 * The visitor authorizes with *their own* Google account (Google Identity
 * Services token popup, drive.file scope), then the PDF they would have
 * downloaded is fetched from this site and uploaded straight from the browser
 * to their Drive. The access token only lives in memory on this page - it is
 * never sent to or stored by the WordPress site.
 */
(function ($) {
	'use strict';

	if (typeof pgfw_gdrive_save_param === 'undefined' || !pgfw_gdrive_save_param.client_id) {
		return;
	}

	var param = pgfw_gdrive_save_param;
	var i18n = param.i18n || {};
	var SCOPE = 'https://www.googleapis.com/auth/drive.file';
	var UPLOAD_URL = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart&fields=id,name,webViewLink';

	var tokenClient = null;
	var accessToken = '';
	var tokenExpiresAt = 0;
	var pending = null; // { $btn, pdf } waiting for the consent popup.

	function setStatus($btn, text, link) {
		var $status = $btn.next('.pgfw-save-to-gdrive-status');
		$status.text(text || '');
		if (link) {
			$status.append(' ').append(
				$('<a target="_blank" rel="noopener noreferrer"></a>').attr('href', link).text(i18n.open || 'Open in Drive')
			);
		}
	}

	function setBusy($btn, busy) {
		$btn.toggleClass('is-busy', busy).attr('aria-disabled', busy ? 'true' : 'false');
	}

	function fileNameFrom(response, postId) {
		var disposition = response.headers.get('Content-Disposition') || '';
		var match = /filename\*=UTF-8''([^;]+)/i.exec(disposition) || /filename="?([^";]+)"?/i.exec(disposition);
		var name = match ? decodeURIComponent(match[1]) : 'document-' + postId + '.pdf';
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

	function upload($btn, pdfPromise) {
		setStatus($btn, i18n.saving || 'Saving to your Google Drive...');
		pdfPromise.then(function (pdf) {
			var boundary = 'pgfw' + Date.now();
			var metadata = { name: pdf.name, mimeType: 'application/pdf' };
			var body = new Blob([
				'--' + boundary + '\r\nContent-Type: application/json; charset=UTF-8\r\n\r\n',
				JSON.stringify(metadata),
				'\r\n--' + boundary + '\r\nContent-Type: application/pdf\r\n\r\n',
				pdf.blob,
				'\r\n--' + boundary + '--'
			]);
			return fetch(UPLOAD_URL, {
				method: 'POST',
				headers: {
					Authorization: 'Bearer ' + accessToken,
					'Content-Type': 'multipart/related; boundary=' + boundary
				},
				body: body
			});
		}).then(function (response) {
			if (response.status === 401) {
				accessToken = '';
				throw new Error(i18n.auth_expired || 'Your Google sign-in expired. Please try again.');
			}
			if (!response.ok) {
				throw new Error(i18n.upload_error || 'Could not save the PDF to Google Drive.');
			}
			return response.json();
		}).then(function (file) {
			setStatus($btn, i18n.saved || 'Saved to your Google Drive.', file && file.webViewLink);
		}).catch(function (err) {
			setStatus($btn, err && err.message ? err.message : (i18n.upload_error || 'Could not save the PDF to Google Drive.'));
		}).then(function () {
			setBusy($btn, false);
		});
	}

	function onToken(response) {
		var job = pending;
		pending = null;
		if (!job) {
			return;
		}
		if (response.error || !google.accounts.oauth2.hasGrantedAllScopes(response, SCOPE)) {
			setStatus(job.$btn, i18n.denied || 'Google Drive access was not granted.');
			setBusy(job.$btn, false);
			return;
		}
		accessToken = response.access_token;
		tokenExpiresAt = Date.now() + (parseInt(response.expires_in, 10) || 3600) * 1000 - 60000;
		upload(job.$btn, job.pdf);
	}

	function onTokenError() {
		// Popup closed / blocked before the visitor finished signing in.
		if (pending) {
			setStatus(pending.$btn, i18n.cancelled || 'Google sign-in was cancelled.');
			setBusy(pending.$btn, false);
			pending = null;
		}
	}

	function getTokenClient() {
		if (!tokenClient) {
			tokenClient = google.accounts.oauth2.initTokenClient({
				client_id: param.client_id,
				scope: SCOPE,
				callback: onToken,
				error_callback: onTokenError
			});
		}
		return tokenClient;
	}

	$(document).on('click', '.pgfw-save-to-gdrive', function (e) {
		e.preventDefault();
		var $btn = $(this);
		if ($btn.hasClass('is-busy')) {
			return;
		}
		if (typeof google === 'undefined' || !google.accounts || !google.accounts.oauth2) {
			setStatus($btn, i18n.not_ready || 'Google sign-in is still loading. Please try again in a moment.');
			return;
		}

		setBusy($btn, true);
		setStatus($btn, i18n.preparing || 'Preparing PDF...');

		// Start generating the PDF right away, in parallel with sign-in.
		var pdf = fetchPdf($btn.data('pdf-url'), $btn.data('post-id'));
		pdf.catch(function () {}); // Reported by upload(); avoid an unhandled rejection meanwhile.

		if (accessToken && Date.now() < tokenExpiresAt) {
			upload($btn, pdf);
			return;
		}

		// Must be called synchronously inside the click so the popup isn't blocked.
		pending = { $btn: $btn, pdf: pdf };
		getTokenClient().requestAccessToken({ prompt: '' });
	});
})(jQuery);
