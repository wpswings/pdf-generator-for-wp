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
        // save general settings.
        // $('#pgfw_general_settings_save').click(function(e){
        //     e.preventDefault();
        //     var self                 = this;
        //     var enable_plugin        = $('#pgfw_enable_plugin').prop('checked');
        //     var default_pdf_icon     = $('#pgfw_general_pdf_icon_default').prop('checked');
        //     var show_post_categories = $('#pgfw_general_pdf_show_categories').prop('checked');
        //     var show_post_tags       = $('#pgfw_general_pdf_show_tags').prop('checked');
        //     var show_post_date       = $('#pgfw_general_pdf_show_post_date').prop('checked');
        //     var show_post_author     = $('#pgfw_general_pdf_show_author_name').prop('checked');
        //     var pdf_generate_mode    = ( $('#pgfw_general_pdf_generate_mode').val() ).trim();
        //     var pdf_file_name        = ( $('#pgfw_general_pdf_file_name').val() ).trim();
        //     var pdf_file_name_custom = ( $('#pgfw_custom_pdf_file_name').val() ).trim();
        //     var general_settings_arr = {
        //         'enable_plugin'        : ( enable_plugin ) ? 'yes' : 'no',
        //         'default_pdf_icon'     : ( default_pdf_icon ) ? 'yes' : 'no',
        //         'show_post_categories' : ( show_post_categories ) ? 'yes' : 'no',
        //         'show_post_tags'       : ( show_post_tags ) ? 'yes' : 'no',
        //         'show_post_date'       : ( show_post_date ) ? 'yes' : 'no',
        //         'show_post_author'     : ( show_post_author ) ? 'yes' : 'no',
        //         'pdf_generate_mode'    : ( pdf_generate_mode ) ? pdf_generate_mode : 'download_locally',
        //         'pdf_file_name'        : ( pdf_file_name ) ? pdf_file_name : 'post_name',
        //         'pdf_file_name_custom' : pdf_file_name_custom,
        //     };
        //     if ( ( 'custom' == pdf_file_name ) && '' == pdf_file_name_custom ) {
        //         window_scroll();
        //         $('header').append(mwb_pgfw_save_settings_obj.custom_file_error_msg);
        //     } else {
        //         mwb_pgfw_create_ajax_request( self, general_settings_arr, 'mwb_pgfw_general_settings' );
        //     }
        // });
        // // save display settings.
        // $('#pgfw_save_admin_display_settings').click(function(e){
        //     e.preventDefault();
        //     var self              = this;
        //     var user_access       = $('#pgfw_user_access').prop('checked');
        //     var guest_access      = $('#pgfw_guest_access').prop('checked');
        //     var admin_display_settings_arr = {
        //         'user_access'       : (user_access) ? 'yes' : 'no',
        //         'guest_access'      : (guest_access) ? 'yes' : 'no',
        //     };
        //     mwb_pgfw_create_ajax_request( self, admin_display_settings_arr, 'mwb_pgfw_display_settings' );

        // });
        // // save settings header.
        // $('#pgfw_header_setting_submit').click(function(e){
        //     e.preventDefault();
        //     var self              = this;
        //     var header_image      = $('#pgfw_header_image').attr('src');
        //     var header_tagline    = ( $('#pgfw_header_tagline').val() ).trim();
        //     var header_color      = ( $('#pgfw_header_color').val() ).trim();
        //     var header_width      = ( $('#pgfw_header_width').val() ).trim();
        //     var header_font_style = ( $('#pgfw_header_font_style').val() ).trim();
        //     var header_font_size  = ( $('#pgfw_header_font_size').val() ).trim();
        //     var header_settings_arr = {
        //         'header_image'      : header_image,
        //         'header_tagline'    : header_tagline,
        //         'header_color'      : header_color,
        //         'header_width'      : header_width,
        //         'header_font_style' : header_font_style,
        //         'header_font_size'  : header_font_size,
        //     };
        //     mwb_pgfw_create_ajax_request( self, header_settings_arr, 'mwb_pgfw_header_settings' );
        // });
        // // save settings footer.
        // $('#pgfw_footer_setting_submit').click(function(e){
        //     e.preventDefault();
        //     var self              = this;
        //     var footer_image      = $('#pgfw_footer_image').attr('src');
        //     var footer_tagline    = ( $('#pgfw_footer_tagline').val() ).trim();
        //     var footer_color      = ( $('#pgfw_footer_color').val() ).trim();
        //     var footer_width      = ( $('#pgfw_footer_width').val() ).trim();
        //     var footer_font_style = ( $('#pgfw_footer_font_style').val() ).trim();
        //     var footer_font_size  = ( $('#pgfw_footer_font_size').val() ).trim();
        //     var footer_settings_arr = {
        //         'footer_image'      : footer_image,
        //         'footer_tagline'    : footer_tagline,
        //         'footer_color'      : footer_color,
        //         'footer_width'      : footer_width,
        //         'footer_font_style' : footer_font_style,
        //         'footer_font_size'  : footer_font_size,
        //     };
        //     mwb_pgfw_create_ajax_request( self, footer_settings_arr, 'mwb_pgfw_footer_settings' );
            
        // });
        // // save body settings.
        // $('#pgfw_body_save_settings').click(function(e){
        //     e.preventDefault();
        //     var self                  = this;
        //     var body_title_font_style = ( $('#pgfw_body_title_font_style').val() ).trim();
        //     var body_title_font_size  = ( $('#pgfw_body_title_font_size').val() ).trim();
        //     var body_title_font_color = ( $('#pgfw_body_title_font_color').val() ).trim();
        //     var body_page_size        = ( $('#pgfw_body_page_size').val() ).trim();
        //     var body_page_orientation = ( $('#pgfw_body_page_orientation').val() ).trim();
        //     var body_page_font_style  = ( $('#pgfw_body_page_font_style').val() ).trim();
        //     var body_page_font_size   = ( $('#pgfw_content_font_size').val() ).trim();
        //     var body_page_font_color  = ( $('#pgfw_body_font_color').val() ).trim();
        //     var body_border_size      = ( $('#pgfw_body_border_size').val() ).trim();
        //     var body_border_color     = ( $('#pgfw_body_border_color').val() ).trim();
        //     var body_margin_top       = ( $('#pgfw_body_margin_top').val() ).trim();
        //     var body_margin_left      = ( $('#pgfw_body_margin_left').val() ).trim();
        //     var body_margin_right     = ( $('#pgfw_body_margin_right').val() ).trim();
        //     var body_rtl_support      = $('#pgfw_body_rtl_support').prop('checked');
        //     var body_add_watermark    = $('#pgfw_body_add_watermark').prop('checked');
        //     var body_watermark_text   = ( $('#pgfw_body_watermark_text').val() ).trim();
        //     var body_watermark_color  = ( $('#pgfw_body_watermark_color').val() ).trim();
        //     var body_cover_template   = ( $('#pgfw_body_page_cover_template').val() ).trim();
        //     var body_page_template    = ( $('#pgfw_body_page_template').val() ).trim();
        //     var body_post_template    = ( $('#pgfw_body_post_template').val() ).trim();
        //     var body_settings_arr = {
        //         'body_title_font_style' : body_title_font_style,
        //         'body_title_font_size'  : body_title_font_size,
        //         'body_title_font_color' : body_title_font_color,
        //         'body_page_size'        : body_page_size,
        //         'body_page_orientation' : body_page_orientation,
        //         'body_page_font_style'  : body_page_font_style,
        //         'body_page_font_size'   : body_page_font_size,
        //         'body_page_font_color'  : body_page_font_color,
        //         'body_border_size'      : body_border_size,
        //         'body_border_color'     : body_border_color,
        //         'body_margin_top'       : body_margin_top,
        //         'body_margin_left'      : body_margin_left,
        //         'body_margin_right'     : body_margin_right,
        //         'body_rtl_support'      : ( body_rtl_support ) ? 'yes' : 'no',
        //         'body_add_watermark'    : ( body_add_watermark ) ? 'yes' : 'no',
        //         'body_watermark_text'   : body_watermark_text,
        //         'body_watermark_color'  : body_watermark_color,
        //         'body_cover_template'   : body_cover_template,
        //         'body_page_template'    : body_page_template,
        //         'body_post_template'    : body_post_template,
        //     };
        //     mwb_pgfw_create_ajax_request( self, body_settings_arr, 'mwb_pgfw_body_settings' );
        // });
        // // save advance setting fields.
        // $('#pgfw_advanced_save_settings').click(function(e){
        //     e.preventDefault();
        //     var self                             = this;
        //     var advanced_pdf_generate_icons_show = $('#pgfw_advanced_show_post_type_icons').val();
        //     var advanced_pdf_password_protect    = $('#pgfw_advanced_password_protect').prop('checked');
        //     var advanced_settings_arr = {
        //         'advanced_pdf_generate_icons_show' : advanced_pdf_generate_icons_show,
        //         'advanced_pdf_password_protect'    : ( advanced_pdf_password_protect ) ? 'yes' : 'no',
        //     };
        //     mwb_pgfw_create_ajax_request( self, advanced_settings_arr, 'mwb_pgfw_advanced_settings' );
        // });
        // // save settings meta fields.
        // $('#pgfw_meta_fields_save_settings').click(function(e){
        //     e.preventDefault();
        //     var self              = this;
        //     var post_meta_show    = $('#pgfw_meta_fields_post_show').prop('checked');
        //     var product_meta_show = $('#pgfw_meta_fields_product_show').prop('checked');
        //     var page_meta_show    = $('#pgfw_meta_fields_page_show').prop('checked');
        //     var product_meta_arr  = ( $('#pgfw_meta_fields_product_list').val() ).trim();
        //     var post_meta_arr     = ( $('#pgfw_meta_fields_post_list').val() ).trim();
        //     var page_meta_arr     = ( $('#pgfw_meta_fields_page_list').val() ).trim();
        //     var meta_fields_settings_arr = {
        //         'post_meta_show'    : ( post_meta_show ) ? 'yes' : 'no',
        //         'product_meta_show' : ( product_meta_show ) ? 'yes' : 'no',
        //         'page_meta_show'    : ( page_meta_show ) ? 'yes' : 'no',
        //         'product_meta_arr'  : product_meta_arr,
        //         'post_meta_arr'     : post_meta_arr,
        //         'page_meta_arr'     : page_meta_arr,
        //     };
        //     mwb_pgfw_create_ajax_request( self, meta_fields_settings_arr, 'mwb_pgfw_meta_fields_settings' );
        // });
        // // creating ajax calling function.
        // function mwb_pgfw_create_ajax_request( cur_obj, data_to_send, key_to_save ) {
        //     $(cur_obj).html('<img src=" ' + mwb_pgfw_save_settings_obj.loader_url + ' " style="width:30px;height:30px;">');
        //     $.ajax({
        //         url    : mwb_pgfw_save_settings_obj.ajaxurl,
        //         method : 'post',
        //         data   : {
        //             setting_arr_data : data_to_send,
        //             action           : 'mwb_pgfw_saving_settings',
        //             nonce            : mwb_pgfw_save_settings_obj.nonce,
        //             key_to_save      : key_to_save,
        //         },
        //         success: function( msg ) {
        //             $(cur_obj).html(mwb_pgfw_save_settings_obj.saved_msg);
        //             window_scroll();
        //             $('header').append(msg);
        //         },
        //         error: function( msg ) {
        //             $(cur_obj).html(mwb_pgfw_save_settings_obj.saved_msg);
        //             window_scroll();
        //             $('header').append(msg);
        //         }
        //     });
        // }
        // function window_scroll() {
        //     document.body.scrollTop = 0; // For Safari
        //     document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
        //     $('.notice').remove();
        // }
    });
})( jQuery );