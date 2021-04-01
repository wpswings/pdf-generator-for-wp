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
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function pdf_generator_for_wordpress_activate() {
		$old_plugin_settings = get_option( 'ptp_settings', array() );
		$pdf_builder         = array_key_exists( 'pdf_builder', $old_plugin_settings ) ? $old_plugin_settings['pdf_builder'] : '';
		$pgfw_show_option    = array_key_exists( 'show', $old_plugin_settings ) ? $old_plugin_settings['show'] : '';
		$pgfw_frontend_show  = array_key_exists( 'frontend', $old_plugin_settings ) ? $old_plugin_settings['frontend'] : '';
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
				'mwb_pgfw_general_settings'     => array(
					'enable_plugin'        => ( $pgfw_frontend_show ) ? 'yes' : 'no',
					'default_pdf_icon'     => 'yes',
					'show_post_categories' => ( $pgfw_show_category ) ? 'yes' : 'no',
					'show_post_tags'       => ( $pgfw_post_tags ) ? 'yes' : 'no',
					'show_post_date'       => ( $pgfw_post_date ) ? 'yes' : 'no',
					'show_post_author'     => ( ( 'none' !== $author_name ) && ( '' !== $author_name ) ) ? 'yes' : 'no',
					'pdf_generate_mode'    => 'download_locally',
					'pdf_file_name'        => ( 'post_id' === $pgfw_file_name ) ? 'document_productid' : $pgfw_file_name,
					'pdf_file_name_custom' => '',
				),
				'mwb_pgfw_display_settings'     => array(
					'user_access'  => 'yes',
					'guest_access' => ( 'guestuser' === $pgfw_show_option ) ? 'yes' : 'no',
				),
				'mwb_pgfw_header_settings'      => array(
					'header_image'      => $header_image,
					'header_tagline'    => '',
					'header_color'      => '#000000',
					'header_width'      => '20',
					'header_font_style' => $header_font_pdf,
					'header_font_size'  => $header_font_size,
				),
				'mwb_pgfw_footer_settings'      => array(
					'footer_image'      => '',
					'footer_tagline'    => '',
					'footer_color'      => '#000000',
					'footer_width'      => $footer_font_margin,
					'footer_font_style' => $footer_font_pdf,
					'footer_font_size'  => $footer_font_size,
				),
				'mwb_pgfw_body_settings'        => array(
					'body_title_font_style' => $content_font_pdf,
					'body_title_font_size'  => 20,
					'body_title_font_color' => '#000000',
					'body_page_size'        => $page_size,
					'body_page_orientation' => 'portrait',
					'body_page_font_style'  => $content_font_pdf,
					'body_page_font_size'   => $content_font_size,
					'body_page_font_color'  => '#000000',
					'body_border_size'      => 2,
					'body_border_color'     => '#000000',
					'body_margin_top'       => $margin_top,
					'body_margin_left'      => $margin_left,
					'body_margin_right'     => $margin_right,
					'body_rtl_support'      => 'no',
					'body_add_watermark'    => ( $add_watermark ) ? 'yes' : 'no',
					'body_watermark_text'   => $watermark_text,
					'body_watermark_color'  => '#000000',
					'body_cover_template'   => 'covertemplate1',
					'body_page_template'    => 'template1',
					'body_post_template'    => 'template1',
				),
				'mwb_pgfw_advanced_settings'    => array(
					'advanced_pdf_generate_icons_show' => array( 'page', 'post', 'product' ),
					'advanced_pdf_password_protect'    => 'no',
				),
				'mwb_pgfw_meta_fields_settings' => array(
					'post_meta_show'    => ( $post_meta ) ? 'yes' : 'no',
					'product_meta_show' => ( $product_meta ) ? 'yes' : 'no',
					'page_meta_show'    => ( $page_meta ) ? 'yes' : 'no',
					'product_meta_arr'  => $product_meta,
					'post_meta_arr'     => $post_meta,
					'page_meta_arr'     => $page_meta,
				),
			);
		} else {
			$pgfw_new_settings = array(
				'mwb_pgfw_general_settings'     => array(
					'enable_plugin'        => 'yes',
					'default_pdf_icon'     => 'yes',
					'show_post_categories' => 'yes',
					'show_post_tags'       => 'yes',
					'show_post_date'       => 'yes',
					'show_post_author'     => 'yes',
					'pdf_generate_mode'    => 'download_locally',
					'pdf_file_name'        => 'post_name',
					'pdf_file_name_custom' => '',
				),
				'mwb_pgfw_display_settings'     => array(
					'user_access'  => 'yes',
					'guest_access' => 'yes',
				),
				'mwb_pgfw_header_settings'      => array(
					'header_image'      => '',
					'header_tagline'    => '',
					'header_color'      => '#000000',
					'header_width'      => '20',
					'header_font_style' => 'helvetica',
					'header_font_size'  => 10,
				),
				'mwb_pgfw_footer_settings'      => array(
					'footer_image'      => '',
					'footer_tagline'    => '',
					'footer_color'      => '#000000',
					'footer_width'      => 10,
					'footer_font_style' => 'helvetica',
					'footer_font_size'  => 10,
				),
				'mwb_pgfw_body_settings'        => array(
					'body_title_font_style' => 'helvetica',
					'body_title_font_size'  => 20,
					'body_title_font_color' => '#000000',
					'body_page_size'        => 'A4',
					'body_page_orientation' => 'portrait',
					'body_page_font_style'  => 'helvetica',
					'body_page_font_size'   => 10,
					'body_page_font_color'  => '#000000',
					'body_border_size'      => 2,
					'body_border_color'     => '#000000',
					'body_margin_top'       => 5,
					'body_margin_left'      => 5,
					'body_margin_right'     => 5,
					'body_rtl_support'      => 'no',
					'body_add_watermark'    => 'no',
					'body_watermark_text'   => '',
					'body_watermark_color'  => '#000000',
					'body_cover_template'   => 'covertemplate1',
					'body_page_template'    => 'template1',
					'body_post_template'    => 'template1',
				),
				'mwb_pgfw_advanced_settings'    => array(
					'advanced_pdf_generate_icons_show' => array( 'page', 'post', 'product' ),
					'advanced_pdf_password_protect'    => 'no',
				),
				'mwb_pgfw_meta_fields_settings' => array(
					'post_meta_show'    => 'yes',
					'product_meta_show' => 'yes',
					'page_meta_show'    => 'yes',
					'product_meta_arr'  => array(),
					'post_meta_arr'     => array(),
					'page_meta_arr'     => array(),
				),
			);
		}
		foreach ( $pgfw_new_settings as $key => $val ) {
			update_option( $key, $val );
		}
	}

}
