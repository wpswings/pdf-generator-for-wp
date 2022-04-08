(function( $ ) {
	'use strict';
    $(document).ready(function() {
        // custom file name input box.
        $('.pgfw_general_pdf_file_name').on('change',function(){
            var val = $(this).val();
            if ( val == 'custom' ) {
                $('.pgfw_custom_pdf_file_name').show();
            } else {
                $('.pgfw_custom_pdf_file_name').hide();
            }
        });
        // colorpicker header and footer.
        $('.pgfw_color_picker').wpColorPicker();
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
        $('#pgfw_single_pdf_icon_image_remove').click(function(e){
            e.preventDefault();
            $('.pgfw_single_pdf_icon_image').attr('src', '');
            $('.pgfw_single_pdf_icon_image').hide();
            $('#sub_pgfw_pdf_single_download_icon').val('');
            $(this).hide();
        });
        // insert single pdf download icon.
        $('#pgfw_pdf_single_download_icon').click(function(e) {
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
                    $('.pgfw_single_pdf_icon_image').attr('src', response.url);
                    $('.pgfw_single_pdf_icon_image').show();
                    $('#pgfw_single_pdf_icon_image_remove').show();
                    $('#sub_pgfw_pdf_single_download_icon').val( response.url );
                });
            }
            this.window.open();
            return false;
        });

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
        $('#pgfw_poster_shortcode_listing_table').DataTable();
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
})( jQuery );
