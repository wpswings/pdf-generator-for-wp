(function( $ ) {
	'use strict';

	window.pgfwInitUI = function() {
		const wps_wpg_pro_enable = pgfw_admin_param.is_pro_active;

		if ( wps_wpg_pro_enable ) {
			$('.wps_pgfw_pro_tag').closest('.wps-form-group').addClass('wps_pgfw_pro_tag_lable').hide();
		} else {
			$('.wps_pgfw_pro_tag').closest('.wps-form-group').addClass('wps_pgfw_pro_tag_lable').show();
		}

		// Material components init.
		if ( window.mdc && mdc.textField ) {
			[].map.call(document.querySelectorAll('.mdc-text-field'), function(el) { return new mdc.textField.MDCTextField(el); });
		}
		if ( window.mdc && mdc.ripple ) {
			[].map.call(document.querySelectorAll('.mdc-button'), function(el) { return new mdc.ripple.MDCRipple(el); });
		}
		if ( window.mdc && mdc.switchControl ) {
			[].map.call(document.querySelectorAll('.mdc-switch'), function(el) { return new mdc.switchControl.MDCSwitch(el); });
		}

		// Password toggle.
		$('.wps-password-hidden').off('click.pgfw').on('click.pgfw', function() {
			const $pwd = $('.wps-form__password');
			$pwd.attr('type', $pwd.attr('type') === 'text' ? 'password' : 'text');
		});

		// Select2 initialisation (safe re-run).
		if ( $.fn.select2 ) {
			$('.wps-defaut-multiselect').each(function() {
				if ( ! $(this).data('select2') ) {
					$(this).select2();
				}
			});
			$('.wpg-select2').each(function() {
				if ( ! $(this).data('select2') ) {
					$(this).select2({ placeholder: 'Select unique items', allowClear: true, width: 'resolve' });
				}
			});
		}

		// Toggle AJAX save (delegated).
		$(document).off('change.pgfw', '.wps-switch input').on('change.pgfw', '.wps-switch input', function() {
			const source = this.dataset.source;
			const value = this.checked ? 'on' : 'off';
			const toast = document.getElementById('wps-toast-msg');

			$.ajax({
				url: pgfw_admin_param.ajaxurl,
				method: 'post',
				data: {
					action: 'wps_pgfw_save_embed_source',
					nonce: pgfw_admin_param.nonce,
					is_enable: value,
					souce_name: source,
					is_pro_active: pgfw_admin_param.is_pro_active,
				},
				success: function( msg ) {
					if ( msg.success && toast ) {
						toast.textContent = '✅ Setting saved!';
						toast.style.display = 'inline-block';
						setTimeout(() => { toast.style.display = 'none'; }, 2000);
					}
				},
			});
		});

		// Shortcode copy buttons.
		document.querySelectorAll('.wps-pgfw-shortcodes-copy-shortcode').forEach(shortcode => {
			shortcode.style.cursor = 'pointer';
			shortcode.style.position = 'relative';
			shortcode.removeEventListener('click', shortcode._pgfwCopyHandler || (()=>{}));
			const handler = function() {
				const text = this.getAttribute('data-shortcode');
				navigator.clipboard.writeText(text).then(() => {
					let tooltip = document.createElement('span');
					tooltip.textContent = 'Copied!';
					Object.assign(tooltip.style, {
						position: 'absolute',
						top: '-25px',
						left: '50%',
						transform: 'translateX(-50%)',
						background: '#28a745',
						color: '#fff',
						padding: '5px 10px',
						borderRadius: '5px',
						fontSize: '12px',
						boxShadow: '0px 2px 5px rgba(0,0,0,0.2)',
						opacity: '0.9',
						transition: 'opacity 0.5s',
					});
					this.appendChild(tooltip);
					setTimeout(() => {
						tooltip.style.opacity = '0';
						setTimeout(() => tooltip.remove(), 500);
					}, 1000);
				});
			};
			shortcode._pgfwCopyHandler = handler;
			shortcode.addEventListener('click', handler);
		});

		// Highlight pro rows.
		document.querySelectorAll('span.wps_shortcode_pro').forEach(span => {
			const tr = span.closest('tr');
			if ( tr ) {
				tr.classList.add('wps-highlight-tr');
			}
		});

		const flashbar = document.querySelector( '[data-pgfw-flashbar]' );
		if ( flashbar ) {
			const flashbarDismissKey = 'pgfw_flashbar_dismissed';
			const dismissFlashbar = function() {
				flashbar.hidden = true;
				flashbar.setAttribute( 'aria-hidden', 'true' );

				try {
					window.localStorage.setItem( flashbarDismissKey, 'yes' );
				} catch ( error ) {
					// Ignore storage failures and still hide the banner for the current view.
				}
			};

			try {
				if ( window.localStorage.getItem( flashbarDismissKey ) === 'yes' ) {
					flashbar.hidden = true;
					flashbar.setAttribute( 'aria-hidden', 'true' );
				}
			} catch ( error ) {
				// Ignore storage read failures and leave the banner visible.
			}

			$( document )
				.off( 'click.pgfwFlashbarDismiss', '[data-pgfw-dismiss-flashbar]' )
				.on( 'click.pgfwFlashbarDismiss', '[data-pgfw-dismiss-flashbar]', function( event ) {
					event.preventDefault();
					dismissFlashbar();
				} );
		}

		const expertModal = document.querySelector( '.pgfw-expert-modal' );
		if ( expertModal ) {
			const expertForm = expertModal.querySelector( '[data-pgfw-expert-form]' );
			const expertFormPanel = expertModal.querySelector( '[data-pgfw-expert-form-panel]' );
			const expertState = expertModal.querySelector( '[data-pgfw-expert-state]' );
			const expertThankYou = expertModal.querySelector( '[data-pgfw-expert-thank-you]' );
			const expertThankYouMessage = expertModal.querySelector( '[data-pgfw-expert-thank-you-message]' );
			let expertRedirectTimeout = null;

			const clearExpertStatus = function() {
				if ( ! expertState ) {
					return;
				}

				expertState.hidden = true;
				expertState.textContent = '';
				expertState.classList.remove( 'is-success', 'is-error' );
			};

			const setExpertStatus = function( type, message ) {
				if ( ! expertState ) {
					return;
				}

				expertState.hidden = false;
				expertState.textContent = message;
				expertState.classList.remove( 'is-success', 'is-error' );
				expertState.classList.add( type === 'success' ? 'is-success' : 'is-error' );
			};

			const clearExpertRedirect = function() {
				if ( expertRedirectTimeout ) {
					window.clearTimeout( expertRedirectTimeout );
					expertRedirectTimeout = null;
				}
			};

			const showExpertForm = function( clearRedirect = true ) {
				if ( clearRedirect ) {
					clearExpertRedirect();
				}
				clearExpertStatus();
				if ( expertForm ) {
					expertForm.classList.remove( 'pgfw-expert-form--submitted' );
				}
				if ( expertFormPanel ) {
					expertFormPanel.hidden = false;
				}
				if ( expertThankYou ) {
					expertThankYou.hidden = true;
					expertThankYou.setAttribute( 'aria-hidden', 'true' );
				}
				if ( expertThankYouMessage ) {
					expertThankYouMessage.textContent = 'Thank you for submitting your request.';
				}
			};

			const showExpertThankYou = function( message ) {
				clearExpertRedirect();
				clearExpertStatus();
				if ( expertForm ) {
					expertForm.classList.add( 'pgfw-expert-form--submitted' );
				}
				if ( expertFormPanel ) {
					expertFormPanel.hidden = true;
				}
				if ( expertThankYouMessage ) {
					expertThankYouMessage.textContent = message || 'Thank you for submitting your request.';
				}
				if ( expertThankYou ) {
					expertThankYou.hidden = false;
					expertThankYou.setAttribute( 'aria-hidden', 'false' );
				}
				expertRedirectTimeout = window.setTimeout( function() {
					window.location.href = pgfw_admin_param.reloadurl;
				}, 4000 );
			};

			const openExpertModal = function() {
				showExpertForm();
				expertModal.hidden = false;
				expertModal.setAttribute( 'aria-hidden', 'false' );
				document.body.classList.add( 'pgfw-expert-modal-open' );
			};

			const closeExpertModal = function() {
				expertModal.hidden = true;
				expertModal.setAttribute( 'aria-hidden', 'true' );
				document.body.classList.remove( 'pgfw-expert-modal-open' );
				showExpertForm( false );
			};

			$( document )
				.off( 'click.pgfwExpertModalOpen', '[data-pgfw-open-expert-modal]' )
				.on( 'click.pgfwExpertModalOpen', '[data-pgfw-open-expert-modal]', function( event ) {
					event.preventDefault();
					openExpertModal();
				} );

			$( document )
				.off( 'click.pgfwExpertModalClose', '[data-pgfw-close-expert-modal]' )
				.on( 'click.pgfwExpertModalClose', '[data-pgfw-close-expert-modal]', function( event ) {
					event.preventDefault();
					closeExpertModal();
				} );

			$( document )
				.off( 'keydown.pgfwExpertModal' )
				.on( 'keydown.pgfwExpertModal', function( event ) {
					if ( event.key === 'Escape' && ! expertModal.hidden ) {
						closeExpertModal();
					}
				} );

			$( document )
				.off( 'submit.pgfwExpertForm', '[data-pgfw-expert-form]' )
				.on( 'submit.pgfwExpertForm', '[data-pgfw-expert-form]', function( event ) {
					const formElement = this;
					const submitButton = formElement.querySelector( '[data-pgfw-expert-submit]' );
					const formData = new FormData( formElement );
					const payload = {};

					event.preventDefault();
					clearExpertStatus();

					formData.forEach( function( value, key ) {
						const normalizedKey = key.replace( /\[\]$/, '' );

						if ( Object.prototype.hasOwnProperty.call( payload, normalizedKey ) ) {
							if ( ! Array.isArray( payload[ normalizedKey ] ) ) {
								const currentValue = payload[ normalizedKey ];
								payload[ normalizedKey ] = [];
								payload[ normalizedKey ].push( currentValue );
							}
							payload[ normalizedKey ].push( value );
							return;
						}

						payload[ normalizedKey ] = value;
					} );

					if ( submitButton ) {
						submitButton.disabled = true;
						submitButton.textContent = submitButton.getAttribute( 'data-pgfw-submit-loading-label' ) || 'Sending...';
					}

					$.ajax( {
						url: pgfw_admin_param.ajaxurl,
						method: 'POST',
						dataType: 'json',
						data: {
							action: 'wps_pgfw_submit_talk_to_expert',
							nonce: pgfw_admin_param.talk_to_expert_nonce,
							form_data: JSON.stringify( payload ),
						},
					} )
						.done( function( response ) {
							const message = response && response.data && response.data.message ? response.data.message : 'Thank you for submitting your request.';

							if ( response && response.success ) {
								formElement.reset();
								showExpertThankYou( message );
								return;
							}

							setExpertStatus( 'error', message );
						} )
						.fail( function( xhr ) {
							const message = xhr && xhr.responseJSON && xhr.responseJSON.data && xhr.responseJSON.data.message
								? xhr.responseJSON.data.message
								: 'Something went wrong while submitting the form. Please try again.';

							setExpertStatus( 'error', message );
						} )
						.always( function() {
							if ( submitButton ) {
								submitButton.disabled = false;
								submitButton.textContent = submitButton.getAttribute( 'data-pgfw-submit-label' ) || 'Submit Request';
							}
						} );
				} );
		}

		// Internal template mapping save.
		const templateSelectors = $('.wpg-template-items, .wpg-template-post-types');
		if ( templateSelectors.length ) {
			const ajaxSave = function( templateName ) {
				const posts = $('select[name=\"wpg_template_items[' + templateName + '][]\"]').val() || [];
				const postTypes = $('select[name=\"wpg_template_post_types[' + templateName + '][]\"]').val() || [];
				$.ajax({
					url: pgfw_admin_param.ajaxurl,
					method: 'POST',
					data: {
						action: 'wpg_save_template_items',
						template: templateName,
						items: posts,
						post_types: postTypes,
						nonce: pgfw_admin_param.nonce,
					},
				});
			};

			templateSelectors.off('change.pgfw').on('change.pgfw', function() {
				const match = $(this).attr('name').match(/wpg_template_(?:items|post_types)\\[(.*?)\\]/);
				const template = match && match[1] ? match[1] : '';
				if ( template ) {
					ajaxSave( template );
				}
			});
		}

		if ( typeof window.pgfwInitCustomSettingsUI === 'function' ) {
			window.pgfwInitCustomSettingsUI( document );
		}

	};

	$(document).ready(function() {
		window.pgfwInitUI();
	});

	$(window).on('load', function() {
		window.pgfwInitUI();
	});

})(jQuery);
