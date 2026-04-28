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
 * Return template for modal.
 *
 * @param string $url_here url to download PDF.
 * @param int    $id post id.
 * @return string
 */
function pgfw_modal_for_email_template( $url_here, $id ) {
	require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'public/templates/pdf-generator-for-wp-public-icon-action-template.php';

	$settings             = pgfw_get_frontend_icon_display_settings();
	$pgfw_wrapper_classes = 'pdf-icon-for-the-email pgfw-icon-display pgfw-icon-display--' . sanitize_html_class( $settings['display_template'] );

	$html  = '<div class="' . esc_attr( $pgfw_wrapper_classes ) . '" style="' . esc_attr( $settings['wrapper_style_attribute'] ) . '">';
	$html .= pgfw_render_icon_action_button(
		array(
			'action_type'      => 'email',
			'display_template' => $settings['display_template'],
			'href'             => '#',
			'title'            => __( 'Please Enter Your Email ID', 'pdf-generator-for-wp' ),
			'label'            => pgfw_get_single_action_label( $settings ),
			'image_only'       => pgfw_should_render_single_action_as_uploaded_icon( $settings ),
			'icon_src'         => pgfw_get_icon_action_icon_src( 'email', $settings ),
			'classes'          => array( 'pgfw-single-pdf-download-a', 'pgfw-single-pdf-download-a--' . sanitize_html_class( $settings['display_template'] ) ),
			'style_attribute'  => $settings['button_style_attribute'],
		)
	);
	$html  = apply_filters( 'wps_pgfw_bulk_download_button_filter_hook', $html, $id );

	if ( is_user_logged_in() ) {
		$html .= '</div>
		<div id="single-pdf-download">
			<div class="wps-pdf_email--shadow"></div>
			<div class="wps-pdf_email-content">
				<div class="wps-pdf_email--close">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M0.225302 0.225302C0.525706 -0.0751008 1.01276 -0.0751008 1.31316 0.225302L10 8.91214L18.6868 0.225302C18.9872 -0.0751008 19.4743 -0.0751008 19.7747 0.225302C20.0751 0.525706 20.0751 1.01276 19.7747 1.31316L11.0879 10L19.7747 18.6868C20.0751 18.9872 20.0751 19.4743 19.7747 19.7747C19.4743 20.0751 18.9872 20.0751 18.6868 19.7747L10 11.0879L1.31316 19.7747C1.01276 20.0751 0.525706 20.0751 0.225302 19.7747C-0.0751008 19.4743 -0.0751008 18.9872 0.225302 18.6868L8.91214 10L0.225302 1.31316C-0.0751008 1.01276 -0.0751008 0.525706 0.225302 0.225302Z" fill="#171717"/>
					</svg>
				</div>
				<h2>' . esc_html__( 'Please Enter Your Email ID', 'pdf-generator-for-wp' ) . '</h2>
				<input type="hidden" name="post_id" id="pgfw_current_post_id" data-post-id="' . esc_html( $id ) . '">
				<div class="wps_pgfw_email_input">
					<label for="pgfw-user-email-input">
						' . esc_html__( 'Email ID', 'pdf-generator-for-wp' ) . '
					</label>
				<input type="email" id="pgfw-user-email-input" name="pgfw-user-email-input" placeholder="' . esc_html__( 'email', 'pdf-generator-for-wp' ) . '">
			
				
				<label for="pgfw-user-email-from-account">
				<input type="checkbox" id="pgfw-user-email-from-account" name="pgfw-user-email-from-account">
							' . esc_html__( 'Use account Email ID instead.', 'pdf-generator-for-wp' ) . '
						</label>
					</div><div class="wps_pgfw_email_button">
					<button id="pgfw-submit-email-user">' . esc_html__( 'Submit', 'pdf-generator-for-wp' ) . '</button>
					</div>
					<div id="pgfw-user-email-submittion-message"></div>
				   </div>
			   </div>';
	} else {
		$html .= '</div>
		<div id="single-pdf-download">
			<div class="wps-pdf_email--shadow"></div>
			<div class="wps-pdf_email-content">
				<div class="wps-pdf_email--close">
					<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
						<path fill-rule="evenodd" clip-rule="evenodd" d="M0.225302 0.225302C0.525706 -0.0751008 1.01276 -0.0751008 1.31316 0.225302L10 8.91214L18.6868 0.225302C18.9872 -0.0751008 19.4743 -0.0751008 19.7747 0.225302C20.0751 0.525706 20.0751 1.01276 19.7747 1.31316L11.0879 10L19.7747 18.6868C20.0751 18.9872 20.0751 19.4743 19.7747 19.7747C19.4743 20.0751 18.9872 20.0751 18.6868 19.7747L10 11.0879L1.31316 19.7747C1.01276 20.0751 0.525706 20.0751 0.225302 19.7747C-0.0751008 19.4743 -0.0751008 18.9872 0.225302 18.6868L8.91214 10L0.225302 1.31316C-0.0751008 1.01276 -0.0751008 0.525706 0.225302 0.225302Z" fill="#171717"/>
					</svg>
				</div>
				<h2>' . esc_html__( 'Please Enter Your Email ID', 'pdf-generator-for-wp' ) . '</h2>
				<input type="hidden" name="post_id" id="pgfw_current_post_id" data-post-id="' . esc_html( $id ) . '">
				<div class="wps_pgfw_email_input">
					<label for="pgfw-user-email-input">
						' . esc_html__( 'Email ID', 'pdf-generator-for-wp' ) . '
					</label>
				<input type="email" id="pgfw-user-email-input" name="pgfw-user-email-input" placeholder="' . esc_html__( 'email', 'pdf-generator-for-wp' ) . '">
			
				</div>';
			$html .= '<div class="wps_pgfw_email_button">
					<button id="pgfw-submit-email-user">' . esc_html__( 'Submit', 'pdf-generator-for-wp' ) . '</button>
					</div>
					<div id="pgfw-user-email-submittion-message"></div>
				</div>
			</div>';
	}

	return $html;
}
