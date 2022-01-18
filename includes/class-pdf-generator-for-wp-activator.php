<?php
/**
 * Fired during plugin activation
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Pdf_Generator_For_Wp_Activator {

	/**
	 * Function will run during plugin activation.
	 *
	 * @param boolean $network_wide either network activated or not.
	 * @since 1.0.0
	 * @return void
	 */
	public static function pdf_generator_for_wp_activate( $network_wide ) {
		global $wpdb;
		if ( is_multisite() && $network_wide ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" ); // phpcs:ignore WordPress
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				self::pgfw_updating_default_settings_indb();
				restore_current_blog();
			}
		} else {
			self::pgfw_updating_default_settings_indb();
		}
	}

	/**
	 * Updating default settings in db wile plugin activation.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public static function pgfw_updating_default_settings_indb() {
		if ( ! get_option( 'pgfw_general_settings_save' ) ) {
			$pgfw_new_settings = array(
				'pgfw_general_settings_save'       => array(
					'pgfw_enable_plugin'                => 'yes',
					'pgfw_general_pdf_show_categories'  => 'yes',
					'pgfw_general_pdf_show_tags'        => 'yes',
					'pgfw_general_pdf_show_post_date'   => 'yes',
					'pgfw_general_pdf_show_author_name' => 'yes',
					'pgfw_general_pdf_generate_mode'    => 'download_locally',
					'pgfw_general_pdf_file_name'        => 'post_name',
					'pgfw_custom_pdf_file_name'         => '',
				),
				'pgfw_save_admin_display_settings' => array(
					'pgfw_user_access'                  => 'yes',
					'pgfw_guest_access'                 => 'yes',
					'pgfw_guest_download_or_email'      => 'direct_download',
					'pgfw_user_download_or_email'       => 'direct_download',
					'pgfw_display_pdf_icon_after'       => 'after_content',
					'pgfw_display_pdf_icon_alignment'   => 'center',
					'sub_pgfw_pdf_single_download_icon' => '',
					'sub_pgfw_pdf_bulk_download_icon'   => '',
					'pgfw_pdf_icon_width'               => 25,
					'pgfw_pdf_icon_height'              => 45,
				),
				'pgfw_header_setting_submit'       => array(
					'pgfw_header_use_in_pdf'       => 'yes',
					'sub_pgfw_header_image_upload' => '',
					'pgfw_header_company_name'     => 'Company Name',
					'pgfw_header_tagline'          => 'Address | Phone | Link | Email',
					'pgfw_header_color'            => '#000000',
					'pgfw_header_width'            => 8,
					'pgfw_header_font_style'       => 'helvetica',
					'pgfw_header_font_size'        => 10,
					'pgfw_header_top'              => -60,
				),
				'pgfw_footer_setting_submit'       => array(
					'pgfw_footer_use_in_pdf' => 'yes',
					'pgfw_footer_tagline'    => 'Footer Tagline',
					'pgfw_footer_color'      => '#000000',
					'pgfw_footer_width'      => 12,
					'pgfw_footer_font_style' => 'helvetica',
					'pgfw_footer_font_size'  => 10,
					'pgfw_footer_bottom'     => -140,
				),
				'pgfw_body_save_settings'          => array(
					'pgfw_body_title_font_style'  => 'helvetica',
					'pgfw_body_title_font_size'   => 20,
					'pgfw_body_title_font_color'  => '#000000',
					'pgfw_body_page_size'         => 'a4',
					'pgfw_body_page_orientation'  => 'portrait',
					'pgfw_body_page_font_style'   => 'helvetica',
					'pgfw_content_font_size'      => 12,
					'pgfw_body_font_color'        => '#000000',
					'pgfw_body_border_size'       => 0,
					'pgfw_body_border_color'      => '',
					'pgfw_body_margin_top'        => 70,
					'pgfw_body_margin_left'       => 35,
					'pgfw_body_margin_right'      => 10,
					'pgfw_body_margin_bottom'     => 60,
					'pgfw_body_rtl_support'       => 'no',
					'pgfw_body_add_watermark'     => 'yes',
					'pgfw_body_watermark_text'    => 'default watermark',
					'pgfw_body_watermark_color'   => '#000000',
					'pgfw_body_page_template'     => 'template1',
					'pgfw_body_post_template'     => 'template1',
					'pgfw_border_position_top'    => -110,
					'pgfw_border_position_left'   => -34,
					'pgfw_border_position_right'  => -15,
					'pgfw_border_position_bottom' => -60,
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
			foreach ( $pgfw_new_settings as $key => $val ) {
				update_option( $key, $val );
			}
		}
	}

}
