<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/public/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
/**
 * Return template for modal.
 *
 * @param string $url_here url to download PDF.
 * @param int    $id post id.
 * @return string
 */
function pgfw_modal_for_email_template( $url_here, $id ) {
	$pgfw_display_settings             = get_option( 'pgfw_save_admin_display_settings', array() );
	$pgfw_pdf_icon_alignment           = array_key_exists( 'pgfw_display_pdf_icon_alignment', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_alignment'] : '';
	$sub_pgfw_pdf_single_download_icon = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $pgfw_display_settings ) ? $pgfw_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
	$pgfw_single_pdf_download_icon_src = ( '' !== $sub_pgfw_pdf_single_download_icon ) ? $sub_pgfw_pdf_single_download_icon : PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/PDF_Tray.svg';
	$pgfw_pdf_icon_width               = array_key_exists( 'pgfw_pdf_icon_width', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_width'] : '';
	$pgfw_pdf_icon_height              = array_key_exists( 'pgfw_pdf_icon_height', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_height'] : '';

	$html  = '<div style="text-align:' . esc_html( $pgfw_pdf_icon_alignment ) . '">
				<a href="#TB_inline?height=300&width=400&inlineId=single-pdf-download" title="' . esc_html__( 'Please Enter Your Email ID', 'pdf-generator-for-wp' ) . '" class="pgfw-single-pdf-download-button thickbox"><img src="' . esc_url( $pgfw_single_pdf_download_icon_src ) . '" title="' . esc_html__( 'Generate PDF', 'pdf-generator-for-wp' ) . '" style="width:' . esc_html( $pgfw_pdf_icon_width ) . 'px; height:' . esc_html( $pgfw_pdf_icon_height ) . 'px;"></a>';
	$html  = apply_filters( 'mwb_pgfw_bulk_download_button_filter_hook', $html, $id );
	$html .= '</div>
				<div id="single-pdf-download" style="display:none;">
					<input type="hidden" name="post_id" id="pgfw_current_post_id" data-post-id="' . esc_html( $id ) . '">
					<div class="mwb_pgfw_email_input">
						<label for="pgfw-user-email-input">
							' . esc_html__( 'Email ID', 'pdf-generator-for-wp' ) . '
						</label>
						<input type="email" id="pgfw-user-email-input" name="pgfw-user-email-input" placeholder="' . esc_html__( 'email', 'pdf-generator-for-wp' ) . '">
					</div>';
	if ( is_user_logged_in() ) {
		$html .= '<div class="mwb_pgfw_email_account">
					<input type="checkbox" id="pgfw-user-email-from-account" name="pgfw-user-email-from-account">
					<label for="pgfw-user-email-from-account">
						' . esc_html__( 'Use account Email ID instead.', 'pdf-generator-for-wp' ) . '
					</label>
				</div>';
	}
	$html .= '<div class="mwb_pgfw_email_button">
				<button id="pgfw-submit-email-user">' . esc_html__( 'Submit', 'pdf-generator-for-wp' ) . '</button>
				</div>
				<div id="pgfw-user-email-submittion-message"></div>
			</div>';
	return $html;
}
