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
                    title    : 'upload image',
                    library  : {type: 'image'},
                    multiple : false,
                    button   : {text: 'use image'}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    $('.pgfw_header_image').attr('src', response.sizes.thumbnail.url);
                    $('.pgfw_header_image').show();
                    $('#pgfw_header_image_remove').show();
                    $('#sub_pgfw_header_image_upload').val( response.sizes.thumbnail.url );
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
                    title    : 'upload image',
                    library  : {type: 'image'},
                    multiple : false,
                    button   : {text: 'use image'}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    $('.pgfw_single_pdf_icon_image').attr('src', response.sizes.thumbnail.url);
                    $('.pgfw_single_pdf_icon_image').show();
                    $('#pgfw_single_pdf_icon_image_remove').show();
                    $('#sub_pgfw_pdf_single_download_icon').val( response.sizes.thumbnail.url );
                });
            }
            this.window.open();
            return false;
        });
        // remove bulk pdf download icon.
        $('#pgfw_bulk_pdf_icon_image_remove').click(function(e){
            e.preventDefault();
            $('.pgfw_bulk_pdf_icon_image').attr('src', '');
            $('.pgfw_bulk_pdf_icon_image').hide();
            $('#sub_pgfw_pdf_bulk_download_icon').val('');
            $(this).hide();
        });
        // insert bulk pdf download icon.
        $('#pgfw_pdf_bulk_download_icon').click(function(e) {
            e.preventDefault();
            if (this.window === undefined) {
                this.window = wp.media({
                    title    : 'upload image',
                    library  : {type: 'image'},
                    multiple : false,
                    button   : {text: 'use image'}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    $('.pgfw_bulk_pdf_icon_image').attr('src', response.sizes.thumbnail.url);
                    $('.pgfw_bulk_pdf_icon_image').show();
                    $('#pgfw_bulk_pdf_icon_image_remove').show();
                    $('#sub_pgfw_pdf_bulk_download_icon').val( response.sizes.thumbnail.url );
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
                    title: 'Upload doc',
                    library: {type: 'application/pdf'},
                    multiple: true,
                    button: {text: 'Use doc'}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').toJSON();
                    var response_arr = {};
                    for ( var i = 0; i < response.length; i++ ) {
                        response_arr[i] = response[i].id;
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
    });
})( jQuery );