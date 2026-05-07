<?php
/**
 * Provide a global area
 *
 * This file is used to store global function.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;

if ( ! function_exists( 'wps_generate_pdf' ) ) {

	/**
	 * Main function for generating pdf.
	 *
	 * @param array $args array containing the arguments.
	 *
	 * @return string|bool
	 */
	function wps_generate_pdf( $args = array() ) {
		$attr = wp_parse_args(
			$args,
			array(
				'html'             => '',
				'paper_size'       => 'a4',
				'page_orientation' => 'portrait',
				'file_name'        => 'document.pdf',
				'Attachment'       => 1,
				'compress'         => 1,
				'get_content'      => false,
				'upload_file'      => false,
				'file_path'        => '',
			)
		);

		$dompdf = wps_get_dompdf_object();
		$dompdf->loadHtml( $attr['html'] );
		$dompdf->setPaper( wps_get_page_sizes( $attr['paper_size'] ), $attr['page_orientation'] );
		$dompdf->render();
		$output = $dompdf->output();
		if ( $attr['get_content'] ) {
			return $output;
		}
		if ( $attr['upload_file'] ) {
			return file_put_contents( $attr['file_path'], $output ); //phpcs:ignore WordPress
		}
		$dompdf->stream(
			$attr['file_name'],
			array(
				'compress'   => $attr['compress'],
				'Attachment' => $attr['Attachment'],
			)
		);
	}

	/**
	 * Get dompdf object.
	 *
	 * @return object
	 */
	function wps_get_dompdf_object() {
		require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		$dompdf = new Dompdf( array( 'enable_remote' => true ) );
		return $dompdf;
	}
	/**
	 * Get paper sizes.
	 *
	 * @param string $page_size page size to generate PDF on.
	 * @return array array containing page size.
	 */
	function wps_get_page_sizes( $page_size = 'a4' ) {
		$paper_sizes = array(
			'4a0'                      => array( 0, 0, 4767.87, 6740.79 ),
			'2a0'                      => array( 0, 0, 3370.39, 4767.87 ),
			'a0'                       => array( 0, 0, 2383.94, 3370.39 ),
			'a1'                       => array( 0, 0, 1683.78, 2383.94 ),
			'a2'                       => array( 0, 0, 1190.55, 1683.78 ),
			'a3'                       => array( 0, 0, 841.89, 1190.55 ),
			'a4'                       => array( 0, 0, 595.28, 841.89 ),
			'a5'                       => array( 0, 0, 419.53, 595.28 ),
			'a6'                       => array( 0, 0, 297.64, 419.53 ),
			'b0'                       => array( 0, 0, 2834.65, 4008.19 ),
			'b1'                       => array( 0, 0, 2004.09, 2834.65 ),
			'b2'                       => array( 0, 0, 1417.32, 2004.09 ),
			'b3'                       => array( 0, 0, 1000.63, 1417.32 ),
			'b4'                       => array( 0, 0, 708.66, 1000.63 ),
			'b5'                       => array( 0, 0, 498.90, 708.66 ),
			'b6'                       => array( 0, 0, 354.33, 498.90 ),
			'c0'                       => array( 0, 0, 2599.37, 3676.54 ),
			'c1'                       => array( 0, 0, 1836.85, 2599.37 ),
			'c2'                       => array( 0, 0, 1298.27, 1836.85 ),
			'c3'                       => array( 0, 0, 918.43, 1298.27 ),
			'c4'                       => array( 0, 0, 649.13, 918.43 ),
			'c5'                       => array( 0, 0, 459.21, 649.13 ),
			'c6'                       => array( 0, 0, 323.15, 459.21 ),
			'ra0'                      => array( 0, 0, 2437.80, 3458.27 ),
			'ra1'                      => array( 0, 0, 1729.13, 2437.80 ),
			'ra2'                      => array( 0, 0, 1218.90, 1729.13 ),
			'ra3'                      => array( 0, 0, 864.57, 1218.90 ),
			'ra4'                      => array( 0, 0, 609.45, 864.57 ),
			'sra0'                     => array( 0, 0, 2551.18, 3628.35 ),
			'sra1'                     => array( 0, 0, 1814.17, 2551.18 ),
			'sra2'                     => array( 0, 0, 1275.59, 1814.17 ),
			'sra3'                     => array( 0, 0, 907.09, 1275.59 ),
			'sra4'                     => array( 0, 0, 637.80, 907.09 ),
			'letter'                   => array( 0, 0, 612.00, 792.00 ),
			'legal'                    => array( 0, 0, 612.00, 1008.00 ),
			'ledger'                   => array( 0, 0, 1224.00, 792.00 ),
			'tabloid'                  => array( 0, 0, 792.00, 1224.00 ),
			'executive'                => array( 0, 0, 521.86, 756.00 ),
			'folio'                    => array( 0, 0, 612.00, 936.00 ),
			'commercial #10 envelope'  => array( 0, 0, 684, 297 ),
			'catalog #10 1/2 envelope' => array( 0, 0, 648, 864 ),
			'8.5x11'                   => array( 0, 0, 612.00, 792.00 ),
			'8.5x14'                   => array( 0, 0, 612.00, 1008.0 ),
			'11x17'                    => array( 0, 0, 792.00, 1224.00 ),
		);
		return isset( $paper_sizes[ $page_size ] ) ? $paper_sizes[ $page_size ] : array( 0, 0, 595.28, 841.89 );
	}
}

if ( ! function_exists( 'pgfw_get_single_pdf_download_icon_src' ) ) {
	/**
	 * Get the frontend icon source for the selected display template.
	 *
	 * Custom uploads override the built-in template assets.
	 *
	 * @param string $custom_icon_url Uploaded custom icon URL.
	 * @param string $display_template Selected display template slug.
	 * @return string
	 */
	function pgfw_get_single_pdf_download_icon_src( $custom_icon_url, $display_template ) {
		if ( '' !== $custom_icon_url ) {
			return $custom_icon_url;
		}

		$template_icon_map = array(
			'style-2' => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/adobe_badge.svg',
			'default' => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/PDF_Tray.svg',
		);

		return isset( $template_icon_map[ $display_template ] ) ? $template_icon_map[ $display_template ] : $template_icon_map['default'];
	}
}

if ( ! function_exists( 'pgfw_get_icon_display_template_config' ) ) {
	/**
	 * Get shared template metadata for frontend icon rendering.
	 *
	 * @param string $display_template Selected template slug.
	 * @return array
	 */
	function pgfw_get_icon_display_template_config( $display_template = 'default' ) {
		$config = array(
			'style-2' => array(
				'label'        => 'Adobe Badge',
				'wrapper_type' => 'compact',
			),
			'default' => array(
				'label'        => 'Printer Classic',
				'wrapper_type' => 'compact',
			),
			'style-4' => array(
				'label'        => 'Boxed Button',
				'wrapper_type' => 'button',
			),
			'style-5' => array(
				'label'        => 'Stamped Seal',
				'wrapper_type' => 'seal',
			),
			'style-3' => array(
				'label'        => 'Brand Tile',
				'wrapper_type' => 'tile',
			),
			'style-6' => array(
				'label'        => 'Gradient FAB',
				'wrapper_type' => 'orb',
			),
			'style-7' => array(
				'label'        => 'Glass Pill',
				'wrapper_type' => 'pill',
			),
			'style-8' => array(
				'label'        => 'Shimmer Tile',
				'wrapper_type' => 'tile',
			),
		);

		return isset( $config[ $display_template ] ) ? $config[ $display_template ] : $config['default'];
	}
}

if ( ! function_exists( 'pgfw_get_frontend_icon_display_settings' ) ) {
	/**
	 * Get normalized frontend icon display settings.
	 *
	 * @return array
	 */
	function pgfw_get_frontend_icon_display_settings() {
		$display_settings = wps_pgfw_get_option_cached( 'pgfw_save_admin_display_settings', array() );
		$display_settings = is_array( $display_settings ) ? $display_settings : array();

		$display_template = array_key_exists( 'pgfw_pdf_icon_display_template', $display_settings ) ? sanitize_text_field( $display_settings['pgfw_pdf_icon_display_template'] ) : 'default';
		$icon_width       = array_key_exists( 'pgfw_pdf_icon_width', $display_settings ) ? absint( $display_settings['pgfw_pdf_icon_width'] ) : 25;
		$icon_height      = array_key_exists( 'pgfw_pdf_icon_height', $display_settings ) ? absint( $display_settings['pgfw_pdf_icon_height'] ) : 45;
		$alignment        = array_key_exists( 'pgfw_display_pdf_icon_alignment', $display_settings ) ? sanitize_text_field( $display_settings['pgfw_display_pdf_icon_alignment'] ) : 'center';
		$label            = array_key_exists( 'wps_wpg_single_pdf_icon_name', $display_settings ) ? $display_settings['wps_wpg_single_pdf_icon_name'] : '';

		if ( ! in_array( $display_template, array( 'style-2', 'default', 'style-4', 'style-5', 'style-3', 'style-6', 'style-7', 'style-8' ), true ) ) {
			$display_template = 'default';
		}

		if ( '' === $label && array_key_exists( 'single_pdf_icon_name', $display_settings ) ) {
			$label = $display_settings['single_pdf_icon_name'];
		}

		$bulk_label = array_key_exists( 'wps_wpg_bulk_pdf_icon_name', $display_settings ) ? $display_settings['wps_wpg_bulk_pdf_icon_name'] : '';

		$settings = array(
			'display_template'            => $display_template,
			'template_config'             => pgfw_get_icon_display_template_config( $display_template ),
			'alignment'                   => $alignment,
			'icon_width'                  => $icon_width > 0 ? $icon_width : 25,
			'icon_height'                 => $icon_height > 0 ? $icon_height : 45,
			'single_icon_url'             => array_key_exists( 'sub_pgfw_pdf_single_download_icon', $display_settings ) ? $display_settings['sub_pgfw_pdf_single_download_icon'] : '',
			'single_label'                => sanitize_text_field( $label ),
			'bulk_icon_url'               => array_key_exists( 'sub_pgfw_pdf_bulk_download_icon', $display_settings ) ? $display_settings['sub_pgfw_pdf_bulk_download_icon'] : '',
			'bulk_label'                  => sanitize_text_field( $bulk_label ),
			'body_show_pdf_icon'          => array_key_exists( 'pgfw_body_show_pdf_icon', $display_settings ) ? $display_settings['pgfw_body_show_pdf_icon'] : '',
			'show_roles'                  => array_key_exists( 'pgfw_show_post_type_icons_for_user_role', $display_settings ) ? $display_settings['pgfw_show_post_type_icons_for_user_role'] : array(),
			'print_enabled'               => array_key_exists( 'pgfw_print_enable', $display_settings ) ? $display_settings['pgfw_print_enable'] : '',
			'whatsapp_enabled'            => array_key_exists( 'wps_wpg_whatsapp_sharing', $display_settings ) ? $display_settings['wps_wpg_whatsapp_sharing'] : '',
			'wrapper_style_attribute'     => '--pgfw-icon-justify:' . $alignment . ';',
			'button_style_attribute'      => '--pgfw-icon-width:' . ( $icon_width > 0 ? $icon_width : 25 ) . 'px;--pgfw-icon-height:' . ( $icon_height > 0 ? $icon_height : 45 ) . 'px;',
			'custom_label_fallback'       => __( 'Download PDF', 'pdf-generator-for-wp' ),
			'custom_bulk_label_fallback'  => __( 'Bulk PDF', 'pdf-generator-for-wp' ),
		);

		return $settings;
	}
}

if ( ! function_exists( 'pgfw_get_single_action_label' ) ) {
	/**
	 * Get the visible label for the single PDF action button.
	 *
	 * When a custom icon is selected without custom text, keep the action
	 * icon-only on the frontend instead of forcing the fallback label.
	 *
	 * @param array $settings Normalized frontend settings.
	 * @return string
	 */
	function pgfw_get_single_action_label( $settings = array() ) {
		$single_label    = isset( $settings['single_label'] ) ? $settings['single_label'] : '';
		$single_icon_url = isset( $settings['single_icon_url'] ) ? $settings['single_icon_url'] : '';
		$fallback_label  = isset( $settings['custom_label_fallback'] ) ? $settings['custom_label_fallback'] : __( 'Download PDF', 'pdf-generator-for-wp' );

		if ( '' !== $single_icon_url ) {
			return '';
		}

		if ( '' !== $single_label ) {
			return $single_label;
		}

		return $fallback_label;
	}
}

if ( ! function_exists( 'pgfw_should_render_single_action_as_uploaded_icon' ) ) {
	/**
	 * Determine whether the single PDF action should render as a plain uploaded icon.
	 *
	 * @param array $settings Normalized frontend settings.
	 * @return bool
	 */
	function pgfw_should_render_single_action_as_uploaded_icon( $settings = array() ) {
		$single_icon_url = isset( $settings['single_icon_url'] ) ? $settings['single_icon_url'] : '';
		return '' !== $single_icon_url;
	}
}

if ( ! function_exists( 'pgfw_get_icon_action_icon_src' ) ) {
	/**
	 * Get the icon source for a frontend icon action.
	 *
	 * @param string $action_type Action type.
	 * @param array  $settings Normalized frontend settings.
	 * @return string
	 */
	function pgfw_get_icon_action_icon_src( $action_type, $settings = array() ) {
		switch ( $action_type ) {
			case 'bulk':
				$bulk_icon_url = isset( $settings['bulk_icon_url'] ) ? $settings['bulk_icon_url'] : '';
				return '' !== $bulk_icon_url ? $bulk_icon_url : PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/download_PDF.svg';
			case 'print':
				return PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/print_icon.png';
			case 'share':
				return PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/whatsapp.png';
			case 'download':
			case 'email':
			default:
				$single_icon_url  = isset( $settings['single_icon_url'] ) ? $settings['single_icon_url'] : '';
				$display_template = isset( $settings['display_template'] ) ? $settings['display_template'] : 'default';
				return pgfw_get_single_pdf_download_icon_src( $single_icon_url, $display_template );
		}
	}
}



add_action( 'wp_ajax_fb_fetch_pdf', 'wps_pgfw_fb_fetch_pdf' );
add_action( 'wp_ajax_ifb_upload_pdf', 'wps_pgfw_upload_pdf' );


/**
 * Reject hostnames that resolve to internal/private/reserved IP ranges.
 *
 * Used to block SSRF: the host of the URL passed to wp_remote_get() must
 * resolve only to publicly routable addresses.
 *
 * @param string $host Hostname or IP literal from the URL.
 * @return bool True if the host is safe to fetch.
 */
function wps_pgfw_host_is_public( $host ) {
	if ( '' === $host || null === $host ) {
		return false;
	}

	// Strip IPv6 brackets if present.
	$host = trim( $host, "[]" );

	$ips = array();
	if ( filter_var( $host, FILTER_VALIDATE_IP ) ) {
		$ips[] = $host;
	} else {
		if ( function_exists( 'dns_get_record' ) ) {
			$records = @dns_get_record( $host, DNS_A | DNS_AAAA ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
			if ( is_array( $records ) ) {
				foreach ( $records as $record ) {
					if ( ! empty( $record['ip'] ) ) {
						$ips[] = $record['ip'];
					}
					if ( ! empty( $record['ipv6'] ) ) {
						$ips[] = $record['ipv6'];
					}
				}
			}
		}
		$v4 = @gethostbynamel( $host ); // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
		if ( is_array( $v4 ) ) {
			$ips = array_merge( $ips, $v4 );
		}
	}

	if ( empty( $ips ) ) {
		return false;
	}

	foreach ( array_unique( $ips ) as $ip ) {
		$is_public = filter_var(
			$ip,
			FILTER_VALIDATE_IP,
			FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
		);
		if ( ! $is_public ) {
			return false;
		}
	}
	return true;
}


/**
 * Fetch PDF from external URL and serve it.
 */
function wps_pgfw_fb_fetch_pdf() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'fb_fetch_pdf' ) ) {
		status_header( 403 );
		echo 'Invalid nonce';
		exit;
	}

	if ( ! current_user_can( 'upload_files' ) ) {
		status_header( 403 );
		echo 'Permission denied';
		exit;
	}

	$url = isset( $_POST['url'] ) ? esc_url_raw( wp_unslash( $_POST['url'] ) ) : '';
	if ( ! $url || ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
		status_header( 400 );
		echo 'Invalid URL';
		exit;
	}

	$parts = wp_parse_url( $url );
	if ( ! is_array( $parts ) || empty( $parts['scheme'] ) || empty( $parts['host'] ) ) {
		status_header( 400 );
		echo 'Invalid URL';
		exit;
	}

	if ( ! in_array( strtolower( $parts['scheme'] ), array( 'http', 'https' ), true ) ) {
		status_header( 400 );
		echo 'Unsupported URL scheme';
		exit;
	}

	// Always allow URLs that point at the WordPress site's own host
	// (e.g. PDFs in the Media Library), even when the site itself runs on a
	// private/loopback IP such as on Local by Flywheel dev environments.
	$site_host    = wp_parse_url( home_url(), PHP_URL_HOST );
	$is_same_host = $site_host && 0 === strcasecmp( $parts['host'], $site_host );

	if ( ! $is_same_host && ! wps_pgfw_host_is_public( $parts['host'] ) ) {
		status_header( 400 );
		echo 'URL host is not allowed';
		exit;
	}

	$response = wp_remote_get(
		$url,
		array(
			'timeout'     => 20,
			'redirection' => 0,
			'user-agent'  => 'InteractiveFlipbook/1.0 (+WordPress)',
		)
	);

	if ( is_wp_error( $response ) ) {
		status_header( 502 );
		echo wp_kses_post( $response->get_error_message() );
		exit;
	}

	$code = wp_remote_retrieve_response_code( $response );
	if ( $code < 200 || $code >= 300 ) {
		status_header( $code ? $code : 502 );
		echo 'Remote server responded with status ' . intval( $code );
		exit;
	}

	$headers      = wp_remote_retrieve_headers( $response );
	$content_type = isset( $headers['content-type'] ) ? strtolower( explode( ';', $headers['content-type'] )[0] ) : '';
	if ( 'application/pdf' !== trim( $content_type ) ) {
		status_header( 415 );
		echo 'URL does not point to a PDF';
		exit;
	}

	$body = wp_remote_retrieve_body( $response );
	if ( '' === $body || null === $body ) {
		status_header( 502 );
		echo 'Empty response body';
		exit;
	}

	if ( 0 !== strncmp( $body, '%PDF-', 5 ) ) {
		status_header( 415 );
		echo 'Response is not a valid PDF';
		exit;
	}

	nocache_headers();
	header( 'Content-Type: application/pdf' );
	header( 'Content-Length: ' . strlen( $body ) );
	header( 'X-Content-Type-Options: nosniff' );
	// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	echo ( $body );
	exit;
}

/**
 * Handle PDF upload via AJAX.
 */
function wps_pgfw_upload_pdf() {
	$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	if ( ! wp_verify_nonce( $nonce, 'ifb_upload_pdf' ) ) {
		wp_send_json_error( 'Invalid nonce', 403 );
	}
	if ( ! current_user_can( 'upload_files' ) ) {
		wp_send_json_error( 'Permission denied', 403 );
	}

	if ( ! isset( $_FILES['pdf'] ) || empty( $_FILES['pdf']['name'] ) ) {
		wp_send_json_error( 'No file provided', 400 );
	}

	// Sanitize $_FILES array before use.
	$files = isset( $_FILES['pdf'] ) ? array_map( 'sanitize_file_name', wp_unslash( $_FILES['pdf'] ) ) : array();
	if ( empty( $files ) ) {
		wp_send_json_error( 'No file provided', 400 );
	}

	$file = $files;
	if ( 'pdf' !== strtolower( $type['ext'] ) ) {
		wp_send_json_error( 'Only PDF files are allowed', 415 );
	}

	require_once ABSPATH . 'wp-admin/includes/file.php';
	require_once ABSPATH . 'wp-admin/includes/media.php';
	require_once ABSPATH . 'wp-admin/includes/image.php';

	$overrides = array(
		'test_form' => false,
		'mimes'     => array( 'pdf' => 'application/pdf' ),
	);
	$file_arr = array(
		'name'     => $file['name'],
		'type'     => $file['type'],
		'tmp_name' => $file['tmp_name'],
		'error'    => $file['error'],
		'size'     => $file['size'],
	);

	$movefile = wp_handle_upload( $file_arr, $overrides );
	if ( ! $movefile || isset( $movefile['error'] ) ) {
		wp_send_json_error( $movefile && isset( $movefile['error'] ) ? $movefile['error'] : 'Upload failed' );
	}

	$attachment = array(
		'post_mime_type' => 'application/pdf',
		'post_title'     => sanitize_file_name( basename( $movefile['file'] ) ),
		'post_content'   => '',
		'post_status'    => 'inherit',
	);
	$attach_id = wp_insert_attachment( $attachment, $movefile['file'] );
	if ( is_wp_error( $attach_id ) ) {
		wp_send_json_error( $attach_id->get_error_message() );
	}
	require_once ABSPATH . 'wp-admin/includes/image.php';
	wp_update_attachment_metadata( $attach_id, wp_generate_attachment_metadata( $attach_id, $movefile['file'] ) );

	$url = $movefile['url'];
	wp_send_json_success(
		array(
			'id'  => $attach_id,
			'url' => $url,
		)
	);
}
