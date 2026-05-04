(function( $ ) {
	'use strict';

	window.pgfwInitCustomSettingsUI = function( context ) {
		var $context = context ? $( context ) : $( document );
		var $inputs = $context.find( '.pgfw_color_picker' ).filter(function() {
			return ! $( this ).closest( '.pgfw-color-picker-card--native' ).length;
		});
		var $nativeCards = $context.find( '.pgfw-color-picker-card--native' );

		if ( $context.is( '.pgfw_color_picker' ) ) {
			$inputs = $inputs.add( $context );
		}

		if ( $context.is( '.pgfw-color-picker-card--native' ) ) {
			$nativeCards = $nativeCards.add( $context );
		}

		$nativeCards.each(function() {
			var $card = $(this);
			var $input = $card.find('.pgfw_color_picker');
			var $control = $card.find('.pgfw-color-picker-native-control');
			var $hex = $card.find('[data-color-picker-hex]');

			function normalizeColor(value) {
				return value ? value.toUpperCase() : '';
			}

			function isHexColor(value) {
				return /^#[0-9A-F]{6}$/i.test(value || '');
			}

			function applyNativeColor(value) {
				var hexValue = isHexColor(value) ? normalizeColor(value) : '';
				$card.toggleClass('has-color-value', !!hexValue);
				$hex.text(hexValue);
				if ( hexValue ) {
					$card.css('--pgfw-picked-color', hexValue);
				} else {
					$card.css('--pgfw-picked-color', '');
				}
				if ( hexValue && $control.length && $control.val() !== value ) {
					$control.val(value);
				}
			}

			function syncNativeColor(value, shouldTriggerChange) {
				applyNativeColor(value);
				$input.val( value );
				if ( false !== shouldTriggerChange ) {
					$input.trigger( 'change' );
				}
			}

			if ( $card.data('pgfwNativeColorReady') ) {
				syncNativeColor($input.val() || $control.val(), false);
				return;
			}

			$card.data('pgfwNativeColorReady', true);
			$control.on('input change', function() {
				syncNativeColor($(this).val());
			});
			$input.on('input change', function() {
				applyNativeColor($(this).val());
			});
			syncNativeColor($input.val() || $control.val(), false);
		});

		if ( ! $.fn.wpColorPicker ) {
			return;
		}

		$inputs.each(function() {
			var $input = $(this);
			var $card = $input.closest('[data-color-picker-card]');
			var $hex = $card.find('[data-color-picker-hex]');

			function normalizeColor(value) {
				return value ? value.toUpperCase() : '';
			}

			function syncColorMeta(value) {
				if (!$card.length) {
					return;
				}
				var hexValue = normalizeColor(value);
				var $resultButton = $card.find('.wp-color-result');
				var $resultSwatches = $resultButton.find('.color-alpha, span');
				$card.toggleClass('has-color-value', !!hexValue);
				$hex.text(hexValue);
				if ( hexValue ) {
					$card.css('--pgfw-picked-color', hexValue);
					$resultButton.css('background-color', hexValue);
					$resultSwatches.css('background-color', hexValue);
				} else {
					$card.css('--pgfw-picked-color', '');
					$resultButton.css('background-color', '');
					$resultSwatches.css('background-color', '');
				}
			}

			if ( $input.data( 'pgfwColorPickerReady' ) ) {
				syncColorMeta($input.val());
				return;
			}

			$input.data( 'pgfwColorPickerReady', true );
			$input.wpColorPicker({
				hide: true,
				palettes: true,
				change: function(event, ui) {
					syncColorMeta(ui && ui.color ? ui.color.toString() : $input.val());
				},
				clear: function() {
					syncColorMeta('');
				}
			});

			syncColorMeta($input.val());
		});
	};

    $(document).ready(function() {
        function pgfwSyncUploadCardState(inputSelector, imageSelector, removeSelector) {
            var $input = $(inputSelector);
            var $image = $(imageSelector);
            var $remove = $(removeSelector);
            var hasImage = !! $.trim($input.val());
            var $card = $input.closest('[data-pgfw-upload-card]');

            if ( $card.length ) {
                $card.toggleClass('is-filled', hasImage);
                $card.toggleClass('is-empty', ! hasImage);
            }

            if ( $image.length ) {
                $image.toggle(hasImage);
            }

            if ( $remove.length ) {
                $remove.toggle(hasImage);
            }
        }

        function pgfwUpdateUploadCard(inputSelector, imageSelector, removeSelector, imageUrl) {
            var $input = $(inputSelector);
            var $image = $(imageSelector);

            $input.val(imageUrl || '');
            $image.attr('src', imageUrl || '');
            pgfwSyncUploadCardState(inputSelector, imageSelector, removeSelector);
        }

        // custom file name input box.
        $('.pgfw_general_pdf_file_name').on('change',function(){
            var val = $(this).val();
            if ( val == 'custom' ) {
                $('.pgfw_custom_pdf_file_name').show();
            } else {
                $('.pgfw_custom_pdf_file_name').hide();
            }
        });
        //////////////////////////custom page //////////////
            // custom page name input box.
            $('.pgfw_body_page_size').on('change',function(){
                var val = $(this).val();
                if ( val == 'custom_page' ) {
                    $('.pgfw_body_custom_page_size_height').show();
                    $('.pgfw_body_custom_page_size_width').show();
                } else {
                    $('.pgfw_body_custom_page_size_height').hide();
                    $('.pgfw_body_custom_page_size_width').hide();
                  
                    
                }
        });

        
        window.pgfwInitCustomSettingsUI( document );
        pgfwSyncUploadCardState('#sub_pgfw_pdf_invoice_single_download_icon', '.pgfw_single_pdf_icon_image_invoice', '#pgfw_single_pdf_invoice_icon_image_remove');
        pgfwSyncUploadCardState('#sub_pgfw_pdf_single_download_icon', '.pgfw_single_pdf_icon_image', '#pgfw_single_pdf_icon_image_remove');
        pgfwSyncUploadCardState('#sub_pgfw_pdf_bulk_download_icon', '.wps_bulk_pdf_icon_image', '#wps_bulk_pdf_icon_image_remove');

        // remove logo header.
        $('#pgfw_header_image_remove').click(function(e){
            e.preventDefault();
            $('.pgfw_header_image').attr('src', '');
            $('.pgfw_header_image').hide();
            $('#sub_pgfw_header_image_upload').val('');
            $(this).hide();
        });
        // insert logo header.
        $('#pgfw_header_image_upload').click(function(e) {
            e.preventDefault();
            if (this.window === undefined) {
                this.window = wp.media({
                    title    : pgfw_admin_custom_param.upload_image,
                    library  : {type: 'image'},
                    multiple : false,
                    button   : {text: pgfw_admin_custom_param.use_image}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    $('.pgfw_header_image').attr('src', response.url);
                    $('.pgfw_header_image').show();
                    $('#pgfw_header_image_remove').show();
                    $('#sub_pgfw_header_image_upload').val( response.url );
                });
            }
            this.window.open();
            return false;
        });

        // remove single pdf download icon.
        $(document).off('click.pgfwSinglePdfRemove', '#pgfw_single_pdf_icon_image_remove').on('click.pgfwSinglePdfRemove', '#pgfw_single_pdf_icon_image_remove', function(e){
            e.preventDefault();
            pgfwUpdateUploadCard('#sub_pgfw_pdf_single_download_icon', '.pgfw_single_pdf_icon_image', '#pgfw_single_pdf_icon_image_remove', '');
        });
        // insert single pdf download icon.
        $(document).off('click.pgfwSinglePdfUpload', '#pgfw_pdf_single_download_icon').on('click.pgfwSinglePdfUpload', '#pgfw_pdf_single_download_icon', function(e) {
            e.preventDefault();
            if (this.window === undefined) {
                this.window = wp.media({
                    title    : pgfw_admin_custom_param.upload_image,
                    library  : {type: 'image'},
                    multiple : false,
                    button   : {text: pgfw_admin_custom_param.use_image}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    pgfwUpdateUploadCard('#sub_pgfw_pdf_single_download_icon', '.pgfw_single_pdf_icon_image', '#pgfw_single_pdf_icon_image_remove', response.url);
                });
            }
            this.window.open();
            return false;
        });

        // remove bulk pdf download icon.
        $(document).off('click.pgfwBulkPdfRemove', '#wps_bulk_pdf_icon_image_remove').on('click.pgfwBulkPdfRemove', '#wps_bulk_pdf_icon_image_remove', function(e){
            e.preventDefault();
            pgfwUpdateUploadCard('#sub_pgfw_pdf_bulk_download_icon', '.wps_bulk_pdf_icon_image', '#wps_bulk_pdf_icon_image_remove', '');
        });
        // insert bulk pdf download icon.
        $(document).off('click.pgfwBulkPdfUpload', '#wps_pgfw_pdf_bulk_download_icon').on('click.pgfwBulkPdfUpload', '#wps_pgfw_pdf_bulk_download_icon', function(e) {
            e.preventDefault();
            if (this.window === undefined) {
                this.window = wp.media({
                    title    : pgfw_admin_custom_param.upload_image,
                    library  : {type: 'image'},
                    multiple : false,
                    button   : {text: pgfw_admin_custom_param.use_image}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    pgfwUpdateUploadCard('#sub_pgfw_pdf_bulk_download_icon', '.wps_bulk_pdf_icon_image', '#wps_bulk_pdf_icon_image_remove', response.url);
                });
            }
            this.window.open();
            return false;
        });


        //////////Invlice logo changer start/////////
        $(document).off('click.pgfwInvoicePdfRemove', '#pgfw_single_pdf_invoice_icon_image_remove').on('click.pgfwInvoicePdfRemove', '#pgfw_single_pdf_invoice_icon_image_remove', function(e){
            e.preventDefault();
            pgfwUpdateUploadCard('#sub_pgfw_pdf_invoice_single_download_icon', '.pgfw_single_pdf_icon_image_invoice', '#pgfw_single_pdf_invoice_icon_image_remove', '');
        });
        // insert single pdf download icon.
        $(document).off('click.pgfwInvoicePdfUpload', '#pgfw_pdf_invoice_single_download_icon').on('click.pgfwInvoicePdfUpload', '#pgfw_pdf_invoice_single_download_icon', function (e) {
            // alert('CLICK');
            e.preventDefault();
            if (this.window === undefined) {
                this.window = wp.media({
                    title    : pgfw_admin_custom_param.upload_image,
                    library  : {type: 'image'},
                    multiple : false,
                    button   : {text: pgfw_admin_custom_param.use_image}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    pgfwUpdateUploadCard('#sub_pgfw_pdf_invoice_single_download_icon', '.pgfw_single_pdf_icon_image_invoice', '#pgfw_single_pdf_invoice_icon_image_remove', response.url);
                });
            }
            this.window.open();
            return false;
        });
        //////////Invoice logo Changer end//////////

        // remove poster.
        $('#pgfw_poster_image_remove').click(function(e){
            e.preventDefault();
            $('.pgfw_poster_image').attr('src', '');
            $('.pgfw_poster_image').hide();
            $('#sub_pgfw_poster_image_upload').val('');
            $(this).hide();
        });
        // insert poster.
        $('#pgfw_poster_image_upload').click(function(e) {
            e.preventDefault();
            if (this.window === undefined) {
                this.window = wp.media({
                    title: pgfw_admin_custom_param.upload_doc,
                    library: {type: 'application/pdf'},
                    multiple: 'add',
                    button: {text: pgfw_admin_custom_param.use_doc}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').toJSON();
                    var response_arr = {};
                    var old_val_fetched = $('#sub_pgfw_poster_image_upload').val();
                    for ( var i = 0; i < response.length; i++ ) {
                        response_arr[i] = response[i].id;
                    }
                    if ( '' !== old_val_fetched ) {
                        var old_val = JSON.parse( old_val_fetched );
                        for( var j = 0; j < old_val.length; j++ ) {
                            response_arr[i] = old_val[j];
                            i++;
                        }
                    }
                    $('.pgfw_poster_image').attr('src', pgfw_admin_custom_param.pgfw_doc_dummy_img );
                    $('.pgfw_poster_image').show();
                    $('#pgfw_poster_image_remove').show();
                    $('#sub_pgfw_poster_image_upload').val( JSON.stringify( response_arr ) );
                });
            }
            this.window.open();
            return false;
        });
        // add datatable to the poster listing table.
        if ( $.fn.DataTable && $('#pgfw_poster_shortcode_listing_table').length ) {
            $('#pgfw_poster_shortcode_listing_table').DataTable();
        }
        // delete posters.
        $('.pgfw-delete-poster-form-table').click(function(e){
            e.preventDefault();
            var r = confirm( pgfw_admin_custom_param.confirm_text );
            if ( r ) {
                var media_id = $(this).data('media-id');
                var self     = this;
                var cur_html = $(self).html();
                $(self).html(pgfw_admin_custom_param.delete_loader);
                $.ajax({
                    url    : pgfw_admin_custom_param.ajaxurl,
                    method : 'post',
                    data   : {
                        action   : 'wps_pgfw_delete_poster_by_media_id_from_table',
                        nonce    : pgfw_admin_custom_param.nonce,
                        media_id : media_id
                    },
                    success: function( msg ) {
                        $(self).closest('tr').remove();
                        $(self).html(cur_html);
                        if ( msg <= 0) {
                            location.reload();
                        }
                    }, error : function() {
                        $(self).html(cur_html);
                    } 
                });
            }
        });
        // reset settings.
        $('#pgfw_advanced_reset_settings').click(function(e){
            e.preventDefault();
            var r = confirm( pgfw_admin_custom_param.reset_confirm );
            if ( r ) {
                $('#pgfw_reset_setting_loader').html('<img src="' + pgfw_admin_custom_param.reset_loader + '" width="30" height="30">');
                $.ajax({
                    url    : pgfw_admin_custom_param.ajaxurl,
                    method : 'post',
                    data   : {
                        action : 'pgfw_reset_default_settings',
                        nonce  : pgfw_admin_custom_param.nonce,
                    },
                    success : function( msg ) {
                        $('#pgfw_reset_setting_loader').html('<img src="' + pgfw_admin_custom_param.reset_success + '" width="30" height="30">');
                    },
                    error  : function() {
                        $('#pgfw_reset_setting_loader').html('<img src="' + pgfw_admin_custom_param.reset_error + '" width="30" height="30">');
                    }
    
                });
            }
        });
    });

//-------------------------------Pop-up For Pro Tags start -------------------------------------------//
        $(document).on('click', '.wps_pgfw_pro_tag_lable', function() {
            $('.wps-pdf__popup-for-pro-shadow').show();
            $('.wps-pdf__popup-for-pro').addClass('active-pro');
        })

        $(document).on('click', '.wps_pgfw_pro_tag .wps-pgfw-gen-section-form', function() {
            $('.wps-pdf__popup-for-pro-shadow').show();
            $('.wps-pdf__popup-for-pro').addClass('active-pro');
        })
    
        $(document).on('click', '.wps-pdf__popup-for-pro-close', function() {
            $('.wps-pdf__popup-for-pro-shadow').hide();
            $('.wps-pdf__popup-for-pro').removeClass('active-pro');
        })
    
        $(document).on('click', '.wps-pdf__popup-for-pro-shadow', function() {
            $(this).hide();
            $('.wps-pdf__popup-for-pro').removeClass('active-pro');
        })
    
        $(document).on('click', '.wps_go_pro_link', function(e) {
            e.preventDefault();
            $('.wps-pdf__popup-for-pro-shadow').show();
            $('.wps-pdf__popup-for-pro').addClass('active-pro');
        })
    
    $(document).on('click', '.disabled-item', function () {
        $('.wps-pdf__popup-for-pro-shadow').show();
        $('.wps-pdf__popup-for-pro').addClass('active-pro');
    });
//-------------------------------Pop-up For Pro Tags End -------------------------------------------//
       
})( jQuery );
