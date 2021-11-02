<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
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

	$html  = '<div style="text-align:' . esc_html( $pgfw_pdf_icon_alignment ) . '" class="mwb-pgfw-pdf-generate-icon__wrapper-frontend">
				<a href="' . esc_html( $url_here ) . '" class="pgfw-single-pdf-download-button" ' . esc_html( $mode ) . '><img src="' . esc_url( $pgfw_single_pdf_download_icon_src ) . '" title="' . esc_html__( 'Generate PDF', 'pdf-generator-for-wp' ) . '" style="width:' . esc_html( $pgfw_pdf_icon_width ) . 'px; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;"></a>';
	$html  = apply_filters( 'mwb_pgfw_bulk_download_button_filter_hook', $html, $id );
	$html .= '</div>';
	return $html;
}
