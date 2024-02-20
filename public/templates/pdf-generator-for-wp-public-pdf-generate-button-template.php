<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * PDF Download button.
 *
 * @param string $url_here url to download PDF.
 * @param int    $id post id to generate PDF for.
 * @return string
 */
function pgfw_pdf_download_button( $url_here, $id ) {

	$general_settings_data             = get_option( 'pgfw_general_settings_save', array() );
	$pgfw_pdf_generate_mode            = array_key_exists( 'pgfw_general_pdf_generate_mode', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_generate_mode'] : '';
	$mode                              = ( 'open_window' === $pgfw_pdf_generate_mode ) ? 'target=_blank' : '';
	$pgfw_display_settings             = get_option( 'pgfw_save_admin_display_settings', array() );
	$pgfw_pdf_icon_alignment           = array_key_exists( 'pgfw_display_pdf_icon_alignment', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_alignment'] : '';
	$sub_pgfw_pdf_single_download_icon = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $pgfw_display_settings ) ? $pgfw_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
	$pgfw_single_pdf_download_icon_src = ( '' !== $sub_pgfw_pdf_single_download_icon ) ? $sub_pgfw_pdf_single_download_icon : PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/PDF_Tray.svg';
	$pgfw_pdf_icon_width               = array_key_exists( 'pgfw_pdf_icon_width', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_width'] : '';
	$pgfw_pdf_icon_height              = array_key_exists( 'pgfw_pdf_icon_height', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_height'] : '';
	$pgfw_body_show_pdf_icon                 = array_key_exists( 'pgfw_body_show_pdf_icon', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_body_show_pdf_icon'] : '';
	$pgfw_show_post_type_icons_for_user_role = array_key_exists( 'pgfw_show_post_type_icons_for_user_role', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_show_post_type_icons_for_user_role'] : array();
	$wps_wpg_whatsapp_sharing          = array_key_exists( 'wps_wpg_whatsapp_sharing', $pgfw_display_settings ) ? $pgfw_display_settings['wps_wpg_whatsapp_sharing'] : '';
	$pgfw_print_enable          = array_key_exists( 'pgfw_print_enable', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_print_enable'] : '';
	$user = wp_get_current_user();
	$whatsapp_link = generate_whatsapp_pdf_link( $url_here );

	if ( is_plugin_active( 'wordpress-pdf-generator/wordpress-pdf-generator.php' ) ) {
		$wps_wpg_single_pdf_icon_name    = array_key_exists( 'wps_wpg_single_pdf_icon_name', $pgfw_display_settings ) ? $pgfw_display_settings['wps_wpg_single_pdf_icon_name'] : '';
		$is_pro_active = true;
	} else {
		$wps_wpg_single_pdf_icon_name = '';
		$is_pro_active = false;
	}

	if ( 'yes' == $pgfw_body_show_pdf_icon ) {
		
		if ( isset( $pgfw_show_post_type_icons_for_user_role ) && ! empty( $pgfw_show_post_type_icons_for_user_role ) && array_intersect( $user->roles, $pgfw_show_post_type_icons_for_user_role ) ) {

			$html  = '<div style="display:flex; gap:10px;justify-content:' . esc_html( $pgfw_pdf_icon_alignment ) . '" class="wps-pgfw-pdf-generate-icon__wrapper-frontend">
			<div> <a href="' . esc_html( $url_here ) . '" class="pgfw-single-pdf-download-button" ' . esc_html( $mode ) . '><img src="' . esc_url( $pgfw_single_pdf_download_icon_src ) . '" title="' . esc_html__( 'Generate PDF', 'pdf-generator-for-wp' ) . '" style="width:auto; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;"">' . $wps_wpg_single_pdf_icon_name . '</a>
			';
			$html  = apply_filters( 'wps_pgfw_bulk_download_button_filter_hook', $html, $id );
			if ( $is_pro_active && 'yes' === $pgfw_print_enable  ) {
			
				$html .= '<a href="javascript:void(0)" id="pgfw_print_button" class="pgfw-single-pdf-download-button" ><img  src="'.PDF_GENERATOR_FOR_WP_DIR_URL.'admin/src/images/print_icon.png" style="display:inline-block;width:auto; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;" ></a>';
			}
			if ( $is_pro_active && 'yes' == $wps_wpg_whatsapp_sharing ) {
				$html .= '<a class="pgfw-single-pdf-download-button wps_pgfw_whatsapp_share_icon" href="' . $whatsapp_link . '"><img src="' . PDF_GENERATOR_FOR_WP_DIR_URL . '/admin/src/images/whatsapp.png" style="width:' . esc_html( $pgfw_pdf_icon_width ) . 'px; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;" ></a>';
			}
			

			$html .= '</div>';

			return $html;
		}
	} else {
		$html  = '<div style="display:flex; gap:10px;justify-content:' . esc_html( $pgfw_pdf_icon_alignment ) . '" class="wps-pgfw-pdf-generate-icon__wrapper-frontend">
		<a href="' . esc_html( $url_here ) . '" class="pgfw-single-pdf-download-button" ' . esc_html( $mode ) . '><img src="' . esc_url( $pgfw_single_pdf_download_icon_src ) . '" title="' . esc_html__( 'Generate PDF', 'pdf-generator-for-wp' ) . '" style="width:auto; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;">' . $wps_wpg_single_pdf_icon_name . '</a>
		';
		$html  = apply_filters( 'wps_pgfw_bulk_download_button_filter_hook', $html, $id );
		if ( $is_pro_active && 'yes' === $pgfw_print_enable  ) {
			
			$html .= '<a href="javascript:void(0)" id="pgfw_print_button" class="pgfw-single-pdf-download-button" onclick="window.print()"><img  src="'.PDF_GENERATOR_FOR_WP_DIR_URL.'admin/src/images/print_icon.png" style="padding-left:10px;display:inline-block;width:auto; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;" ></a>';
		}
		if ( $is_pro_active && 'yes' == $wps_wpg_whatsapp_sharing ) {

			$html .= '<a class="pgfw-single-pdf-download-button wps_pgfw_whatsapp_share_icon" href="' . $whatsapp_link . '"><img src="' . PDF_GENERATOR_FOR_WP_DIR_URL . '/admin/src/images/whatsapp.png" style="width:auto; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;" ></a>';
		}
		
			$html .= '</div>';
		return $html;
	}

}
/**
 * Whatsapp sharing link generator .
 *
 * @param string $file_url file_url .
 */
function generate_whatsapp_pdf_link( $file_url ) {
	$whatsapp_url = 'https://api.whatsapp.com/send?';
	$whatsapp_url .= 'text=' . urlencode( 'Check out this PDF file: ' . $file_url );
	return $whatsapp_url;
}
