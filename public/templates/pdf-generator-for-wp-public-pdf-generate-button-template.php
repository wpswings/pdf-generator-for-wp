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
	require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'public/templates/pdf-generator-for-wp-public-icon-action-template.php';

	$general_settings_data = wps_pgfw_get_option_cached( 'pgfw_general_settings_save', array() );
	$pgfw_pdf_generate_mode = array_key_exists( 'pgfw_general_pdf_generate_mode', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_generate_mode'] : '';
	$settings               = pgfw_get_frontend_icon_display_settings();
	$user                   = wp_get_current_user();
	$is_pro_active          = wps_pgfw_is_pdf_pro_plugin_active();

	if ( 'yes' === $settings['body_show_pdf_icon'] ) {
		if ( empty( $settings['show_roles'] ) || ! array_intersect( (array) $user->roles, (array) $settings['show_roles'] ) ) {
			return '';
		}
	}

	$wrapper_classes = 'wps-pgfw-pdf-generate-icon__wrapper-frontend pgfw-icon-display pgfw-icon-display--' . sanitize_html_class( $settings['display_template'] );
	$whatsapp_link   = generate_whatsapp_pdf_link( $url_here );
	$target_attr     = ( 'open_window' === $pgfw_pdf_generate_mode ) ? '_blank' : '';

	$actions_html  = pgfw_render_icon_action_button(
		array(
			'action_type'      => 'download',
			'display_template' => $settings['display_template'],
			'href'             => $url_here,
			'title'            => __( 'Generate PDF', 'pdf-generator-for-wp' ),
			'label'            => pgfw_get_single_action_label( $settings ),
			'image_only'       => pgfw_should_render_single_action_as_uploaded_icon( $settings ),
			'icon_src'         => pgfw_get_icon_action_icon_src( 'download', $settings ),
			'attributes'       => array_filter(
				array(
					'target' => $target_attr,
				)
			),
			'style_attribute'  => $settings['button_style_attribute'],
		)
	);
	$actions_html  = apply_filters( 'wps_pgfw_bulk_download_button_filter_hook', $actions_html, $id );

	if ( $is_pro_active && 'yes' === $settings['print_enabled'] ) {
		$actions_html .= pgfw_render_icon_action_button(
			array(
				'action_type'      => 'print',
				'display_template' => $settings['display_template'],
				'href'             => '#',
				'title'            => __( 'Print', 'pdf-generator-for-wp' ),
				'label'            => __( 'Print', 'pdf-generator-for-wp' ),
				'icon_src'         => pgfw_get_icon_action_icon_src( 'print', $settings ),
				'id'               => 'pgfw_print_button',
				'classes'          => array( 'wps_pgfw_print_icon' ),
				'attributes'       => array(
					'onclick' => 'window.print()',
				),
				'style_attribute'  => $settings['button_style_attribute'],
			)
		);
	}

	if ( $is_pro_active && 'yes' === $settings['whatsapp_enabled'] ) {
		$actions_html .= pgfw_render_icon_action_button(
			array(
				'action_type'      => 'share',
				'display_template' => $settings['display_template'],
				'href'             => $whatsapp_link,
				'title'            => __( 'Share', 'pdf-generator-for-wp' ),
				'label'            => __( 'Share', 'pdf-generator-for-wp' ),
				'icon_src'         => pgfw_get_icon_action_icon_src( 'share', $settings ),
				'classes'          => array( 'wps_pgfw_whatsapp_share_icon' ),
				'style_attribute'  => $settings['button_style_attribute'],
			)
		);
	}

	return '<div class="' . esc_attr( $wrapper_classes ) . '" style="' . esc_attr( $settings['wrapper_style_attribute'] ) . '">' . $actions_html . '</div>';
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
