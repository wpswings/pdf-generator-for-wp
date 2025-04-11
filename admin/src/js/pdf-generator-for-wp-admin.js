(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 $(document).ready(function() {
		
		var wps_wpg_pro_enable = pgfw_admin_param.is_pro_active
		var wps_wpg_licese_check = pgfw_admin_param.license_check
		
		if ( wps_wpg_pro_enable ){
			jQuery('.wps_pgfw_pro_tag').closest('.wps-form-group').addClass('wps_pgfw_pro_tag_lable').hide();
		}else {
			jQuery('.wps_pgfw_pro_tag').closest('.wps-form-group').addClass('wps_pgfw_pro_tag_lable').show();
		}
		
         
        
		const MDCText = mdc.textField.MDCTextField;
        const textField = [].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
            return new MDCText(el);
        });
        const MDCRipple = mdc.ripple.MDCRipple;
        const buttonRipple = [].map.call(document.querySelectorAll('.mdc-button'), function(el) {
            return new MDCRipple(el);
        });
        const MDCSwitch = mdc.switchControl.MDCSwitch;
        const switchControl = [].map.call(document.querySelectorAll('.mdc-switch'), function(el) {
            return new MDCSwitch(el);
        });

        $('.wps-password-hidden').click(function() {
            if ($('.wps-form__password').attr('type') == 'text') {
                $('.wps-form__password').attr('type', 'password');
            } else {
                $('.wps-form__password').attr('type', 'text');
            }
        });

	});

	$(window).load(function(){
		// add select2 for multiselect.
		if( $(document).find('.wps-defaut-multiselect').length > 0 ) {
			$(document).find('.wps-defaut-multiselect').select2();
		}
	});

	document.addEventListener('DOMContentLoaded', () => {
		document.querySelectorAll('.wps-switch input').forEach((el) => {
			el.addEventListener('change', function() {
				const source = this.dataset.source;
				const value = this.checked ? 'on' : 'off';
				const toast = document.getElementById('wps-toast-msg');

				$.ajax({
                    url    : pgfw_admin_param.ajaxurl,
                    method : 'post',
                    data   : {
                        action   : 'wps_pgfw_save_embed_source',
                        nonce    : pgfw_admin_param.nonce,
						is_enable: value,
						souce_name: source,
						is_pro_active : pgfw_admin_param.is_pro_active,
                    },
                    success: function( msg ) {
						console.log(msg);
						if (msg.success) {
							toast.textContent = '✅ Setting saved!';
							toast.style.display = 'inline-block';
							setTimeout(() => {
								toast.style.display = 'none';
							}, 2000);
                        }
                    }, error : function() {
						console.log('error encounter');
                    } 
                });
			});
		});
	});
	

	document.addEventListener("DOMContentLoaded", function() {
		const shortcodes = document.querySelectorAll(".wps-pgfw-shortcodes-copy-shortcode");

		shortcodes.forEach(shortcode => {
			shortcode.style.cursor = "pointer";
			shortcode.style.position = "relative";

			shortcode.addEventListener("click", function() {
				const text = this.getAttribute("data-shortcode");
				navigator.clipboard.writeText(text).then(() => {
					let tooltip = document.createElement("span");
					tooltip.textContent = "Copied!";
					tooltip.style.position = "absolute";
					tooltip.style.top = "-25px";
					tooltip.style.left = "50%";
					tooltip.style.transform = "translateX(-50%)";
					tooltip.style.background = "#28a745";
					tooltip.style.color = "#fff";
					tooltip.style.padding = "5px 10px";
					tooltip.style.borderRadius = "5px";
					tooltip.style.fontSize = "12px";
					tooltip.style.boxShadow = "0px 2px 5px rgba(0,0,0,0.2)";
					tooltip.style.opacity = "0.9";
					tooltip.style.transition = "opacity 0.5s";
					this.appendChild(tooltip);

					setTimeout(() => {
						tooltip.style.opacity = "0";
						setTimeout(() => tooltip.remove(), 500);
					}, 1000);
				});
			});
		});
	});
	
	 
	})( jQuery );
