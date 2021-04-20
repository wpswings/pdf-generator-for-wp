<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/includes
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Pdf_Generator_For_WordPress_Activator {

	/**
	 * Saving mandatory and default settings while activating plugin.
	 *
	 * This will contain all the settings which are required to generate PDF.
	 *
	 * @since    1.0.0
	 */
	public static function pdf_generator_for_wordpress_activate() {
		$old_plugin_settings = get_option( 'ptp_settings', array() );
		$pdf_builder         = array_key_exists( 'pdf_builder', $old_plugin_settings ) ? $old_plugin_settings['pdf_builder'] : '';
		$pgfw_show_option    = array_key_exists( 'show', $old_plugin_settings ) ? $old_plugin_settings['show'] : '';
		$pgfw_frontend_show  = array_key_exists( 'frontend', $old_plugin_settings ) ? $old_plugin_settings['frontend'] : '';

		$pgfw_general_settings_check = get_option( 'pgfw_general_settings_save' );
		if ( ! $pgfw_general_settings_check ) {
			if ( '' !== $pdf_builder ) {
				$post_options           = $old_plugin_settings['pdf_builder'][0];
				$pgfw_file_name         = array_key_exists( 'mwb_file_name', $post_options ) ? $post_options['mwb_file_name'] : '';
				$pgfw_show_category     = array_key_exists( 'post_categ', $post_options ) ? $post_options['post_categ'] : '';
				$pgfw_post_tags         = array_key_exists( 'post_tags', $post_options ) ? $post_options['post_tags'] : '';
				$pgfw_post_date         = array_key_exists( 'post_date', $post_options ) ? $post_options['post_date'] : '';
				$author_name            = array_key_exists( 'author_name', $post_options ) ? $post_options['author_name'] : '';
				$post_template_settings = $old_plugin_settings['pdf_builder'][1];
				$content_font_size      = array_key_exists( 'content_font_size', $post_template_settings ) ? $post_template_settings['content_font_size'] : '';
				$content_font_pdf       = array_key_exists( 'content_font_pdf', $post_template_settings ) ? $post_template_settings['content_font_pdf'] : '';
				$page_orientation       = array_key_exists( 'page_orientation', $post_template_settings ) ? $post_template_settings['page_orientation'] : '';
				$page_size              = array_key_exists( 'page_size', $post_template_settings ) ? $post_template_settings['page_size'] : '';
				$margin_top             = array_key_exists( 'margin_top', $post_template_settings ) ? $post_template_settings['margin_top'] : '';
				$margin_left            = array_key_exists( 'margin_left', $post_template_settings ) ? $post_template_settings['margin_left'] : '';
				$margin_right           = array_key_exists( 'margin_right', $post_template_settings ) ? $post_template_settings['margin_right'] : '';
				$pgfw_header_settings   = $old_plugin_settings['pdf_builder'][2];
				$header_font_pdf        = array_key_exists( 'header_font_pdf', $pgfw_header_settings ) ? $pgfw_header_settings['header_font_pdf'] : '';
				$header_font_size       = array_key_exists( 'header_font_size', $pgfw_header_settings ) ? $pgfw_header_settings['header_font_size'] : '';
				$header_image           = array_key_exists( 'logo_img_url', $pgfw_header_settings ) ? $pgfw_header_settings['logo_img_url'] : '';
				$pgfw_footer_settings   = $old_plugin_settings['pdf_builder'][7];
				$footer_font_pdf        = array_key_exists( 'footer_font_pdf', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_font_pdf'] : '';
				$footer_font_size       = array_key_exists( 'footer_font_size', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_font_size'] : '';
				$footer_font_margin     = array_key_exists( 'footer_font_margin', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_font_margin'] : '';
				$pgfw_add_watermark     = $old_plugin_settings['pdf_builder'][3];
				$add_watermark          = array_key_exists( 'add_watermark', $pgfw_add_watermark ) ? $pgfw_add_watermark['add_watermark'] : '';
				$watermark_text         = array_key_exists( 'watermark_text', $pgfw_add_watermark ) ? $pgfw_add_watermark['watermark_text'] : '';
				$pgfw_meta_fields       = $old_plugin_settings['pdf_builder'][6];
				$post_meta              = array_key_exists( 'post', $pgfw_meta_fields ) ? $pgfw_meta_fields['post'] : '';
				$page_meta              = array_key_exists( 'page', $pgfw_meta_fields ) ? $pgfw_meta_fields['page'] : '';
				$product_meta           = array_key_exists( 'product', $pgfw_meta_fields ) ? $pgfw_meta_fields['product'] : '';
				$pgfw_new_settings      = array(
					'pgfw_general_settings_save'       => array(
						'pgfw_enable_plugin'               => ( $pgfw_frontend_show ) ? 'yes' : 'no',
						'pgfw_general_pdf_show_categories' => ( $pgfw_show_category ) ? 'yes' : 'no',
						'pgfw_general_pdf_show_tags'       => ( $pgfw_post_tags ) ? 'yes' : 'no',
						'pgfw_general_pdf_show_post_date'  => ( $pgfw_post_date ) ? 'yes' : 'no',
						'pgfw_general_pdf_show_author_name' => ( ( 'none' !== $author_name ) && ( '' !== $author_name ) ) ? 'yes' : 'no',
						'pgfw_general_pdf_generate_mode'   => 'download_locally',
						'pgfw_general_pdf_file_name'       => ( 'post_id' === $pgfw_file_name ) ? 'document_productid' : $pgfw_file_name,
						'pgfw_custom_pdf_file_name'        => '',
					),
					'pgfw_save_admin_display_settings' => array(
						'pgfw_user_access'                => 'yes',
						'pgfw_guest_access'               => ( 'guestuser' === $pgfw_show_option ) ? 'yes' : 'no',
						'pgfw_bulk_download_enable'       => 'yes',
						'pgfw_guest_download_or_email'    => 'direct_download',
						'pgfw_user_download_or_email'     => 'direct_download',
						'pgfw_display_pdf_icon_after'     => 'after_content',
						'pgfw_display_pdf_icon_alignment' => 'center',
						'sub_pgfw_pdf_single_download_icon' => '',
						'sub_pgfw_pdf_bulk_download_icon' => '',
						'pgfw_pdf_icon_width'             => 25,
						'pgfw_pdf_icon_height'            => 45,
					),
					'pgfw_header_setting_submit'       => array(
						'pgfw_header_use_in_pdf'       => 'yes',
						'sub_pgfw_header_image_upload' => $header_image,
						'pgfw_header_company_name'     => '',
						'pgfw_header_tagline'          => '',
						'pgfw_header_color'            => '#000000',
						'pgfw_header_width'            => 20,
						'pgfw_header_font_style'       => $header_font_pdf,
						'pgfw_header_font_size'        => $header_font_size,
					),
					'pgfw_footer_setting_submit'       => array(
						'pgfw_footer_use_in_pdf' => 'yes',
						'pgfw_footer_tagline'    => '',
						'pgfw_footer_color'      => '#000000',
						'pgfw_footer_width'      => $footer_font_margin,
						'pgfw_footer_font_style' => $footer_font_pdf,
						'pgfw_footer_font_size'  => $footer_font_size,
					),
					'pgfw_body_save_settings'          => array(
						'pgfw_body_title_font_style'    => $content_font_pdf,
						'pgfw_body_title_font_size'     => 20,
						'pgfw_body_title_font_color'    => '#000000',
						'pgfw_body_page_size'           => $page_size,
						'pgfw_body_page_orientation'    => 'portrait',
						'pgfw_body_page_font_style'     => $content_font_pdf,
						'pgfw_content_font_size'        => $content_font_size,
						'pgfw_body_font_color'          => '#000000',
						'pgfw_body_border_size'         => 2,
						'pgfw_body_border_color'        => '#000000',
						'pgfw_body_margin_top'          => $margin_top,
						'pgfw_body_margin_left'         => $margin_left,
						'pgfw_body_margin_right'        => $margin_right,
						'pgfw_body_rtl_support'         => 'no',
						'pgfw_body_add_watermark'       => ( $add_watermark ) ? 'yes' : 'no',
						'pgfw_body_watermark_text'      => $watermark_text,
						'pgfw_body_watermark_color'     => '#000000',
						'pgfw_body_page_cover_template' => 'covertemplate1',
						'pgfw_body_page_template'       => 'template1',
						'pgfw_body_post_template'       => 'template1',
					),
					'pgfw_advanced_save_settings'      => array(
						'pgfw_advanced_show_post_type_icons' => array( 'page', 'post', 'product' ),
					),
					'pgfw_meta_fields_save_settings'   => array(
						'pgfw_meta_fields_post_show'    => ( $post_meta ) ? 'yes' : 'no',
						'pgfw_meta_fields_product_show' => ( $product_meta ) ? 'yes' : 'no',
						'pgfw_meta_fields_page_show'    => ( $page_meta ) ? 'yes' : 'no',
						'pgfw_meta_fields_product_list' => $product_meta,
						'pgfw_meta_fields_post_list'    => $post_meta,
						'pgfw_meta_fields_page_list'    => $page_meta,
					),
					'pgfw_pdf_upload_save_settings'    => array(
						'sub_pgfw_poster_image_upload' => '',
						'pgfw_poster_user_access'      => 'yes',
						'pgfw_poster_guest_access'     => 'yes',
					),
				);
			} else {
				$pgfw_new_settings = array(
					'pgfw_general_settings_save'       => array(
						'pgfw_enable_plugin'               => 'yes',
						'pgfw_general_pdf_show_categories' => 'yes',
						'pgfw_general_pdf_show_tags'       => 'yes',
						'pgfw_general_pdf_show_post_date'  => 'yes',
						'pgfw_general_pdf_show_author_name' => 'yes',
						'pgfw_general_pdf_generate_mode'   => 'download_locally',
						'pgfw_general_pdf_file_name'       => 'post_name',
						'pgfw_custom_pdf_file_name'        => '',
					),
					'pgfw_save_admin_display_settings' => array(
						'pgfw_user_access'                => 'yes',
						'pgfw_guest_access'               => 'yes',
						'pgfw_bulk_download_enable'       => 'yes',
						'pgfw_guest_download_or_email'    => 'direct_download',
						'pgfw_user_download_or_email'     => 'direct_download',
						'pgfw_display_pdf_icon_after'     => 'after_content',
						'pgfw_display_pdf_icon_alignment' => 'center',
						'sub_pgfw_pdf_single_download_icon' => '',
						'sub_pgfw_pdf_bulk_download_icon' => '',
						'pgfw_pdf_icon_width'             => 25,
						'pgfw_pdf_icon_height'            => 45,
					),
					'pgfw_header_setting_submit'       => array(
						'pgfw_header_use_in_pdf'       => 'yes',
						'sub_pgfw_header_image_upload' => '',
						'pgfw_header_company_name'     => '',
						'pgfw_header_tagline'          => '',
						'pgfw_header_color'            => '#000000',
						'pgfw_header_width'            => 5,
						'pgfw_header_font_style'       => 'helvetica',
						'pgfw_header_font_size'        => 10,
					),
					'pgfw_footer_setting_submit'       => array(
						'pgfw_footer_use_in_pdf' => 'yes',
						'pgfw_footer_tagline'    => 'footer tagline',
						'pgfw_footer_color'      => '#000000',
						'pgfw_footer_width'      => 12,
						'pgfw_footer_font_style' => 'helvetica',
						'pgfw_footer_font_size'  => 10,
					),
					'pgfw_body_save_settings'          => array(
						'pgfw_body_title_font_style' => 'helvetica',
						'pgfw_body_title_font_size'  => 20,
						'pgfw_body_title_font_color' => '#000000',
						'pgfw_body_page_size'        => 'a4',
						'pgfw_body_page_orientation' => 'portrait',
						'pgfw_body_page_font_style'  => 'helvetica',
						'pgfw_content_font_size'     => 12,
						'pgfw_body_font_color'       => '#000000',
						'pgfw_body_border_size'      => 0,
						'pgfw_body_border_color'     => '',
						'pgfw_body_margin_top'       => 10,
						'pgfw_body_margin_left'      => 35,
						'pgfw_body_margin_right'     => 10,
						'pgfw_body_rtl_support'      => 'no',
						'pgfw_body_add_watermark'    => 'yes',
						'pgfw_body_watermark_text'   => 'default watermark',
						'pgfw_body_watermark_color'  => '#000000',
						'pgfw_body_page_template'    => 'template1',
						'pgfw_body_post_template'    => 'template1',
					),
					'pgfw_advanced_save_settings'      => array(
						'pgfw_advanced_show_post_type_icons' => array( 'page', 'post', 'product' ),
					),
					'pgfw_meta_fields_save_settings'   => array(
						'pgfw_meta_fields_post_show'    => 'no',
						'pgfw_meta_fields_product_show' => 'no',
						'pgfw_meta_fields_page_show'    => 'no',
						'pgfw_meta_fields_product_list' => '',
						'pgfw_meta_fields_post_list'    => '',
						'pgfw_meta_fields_page_list'    => '',
					),
					'pgfw_pdf_upload_save_settings'    => array(
						'sub_pgfw_poster_image_upload' => '',
						'pgfw_poster_user_access'      => 'yes',
						'pgfw_poster_guest_access'     => 'yes',
					),
				);
			}
			foreach ( $pgfw_new_settings as $key => $val ) {
				update_option( $key, $val );
			}
		}
	}

}
