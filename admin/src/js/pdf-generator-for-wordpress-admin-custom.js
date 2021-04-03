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
        // remove logo footer.
        $('#pgfw_footer_image_remove').click(function(e){
            e.preventDefault();
            $('.pgfw_footer_image').attr('src', '');
            $('.pgfw_footer_image').hide();
            $('#sub_pgfw_footer_image_upload').val('');
            $(this).hide();
        });
        // insert logo footer.
        $('#pgfw_footer_image_upload').click(function(e) {
            e.preventDefault();
            if (this.window === undefined) {
                this.window = wp.media({
                    title: 'upload image',
                    library: {type: 'image'},
                    multiple: false,
                    button: {text: 'use image'}
                });
                var self = this;
                this.window.on('select', function() {
                    var response = self.window.state().get('selection').first().toJSON();
                    $('.pgfw_footer_image').attr('src', response.sizes.thumbnail.url);
                    $('.pgfw_footer_image').show();
                    $('#pgfw_footer_image_remove').show();
                    $('#sub_pgfw_footer_image_upload').val( response.sizes.thumbnail.url );
                });
            }
            this.window.open();
            return false;
        });
    });
})( jQuery );