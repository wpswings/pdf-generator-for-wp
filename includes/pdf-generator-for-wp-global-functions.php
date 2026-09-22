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
				'post_id'          => 0,
			)
		);

		$dompdf = wps_get_dompdf_object();
		$dompdf->loadHtml( $attr['html'] );
		$dompdf->setPaper( wps_get_page_sizes( $attr['paper_size'] ), $attr['page_orientation'] );
		$dompdf->render();
		wps_pgfw_apply_pdf_security( $dompdf, $attr['post_id'] );
		wps_pgfw_auto_save_pdf_to_cloud( $dompdf, $attr['file_name'] );
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
	 * Resolve which password (if any) applies to a given post/page/product's generated PDF,
	 * per the same precedence used to actually encrypt it: a per-item override
	 * (see the "PDF Password Protection" metabox) always wins when set; otherwise the
	 * global "Enable PDF Password Protection" toggle + password from General Settings apply.
	 *
	 * Shared by wps_pgfw_apply_pdf_security() (to encrypt) and anywhere the password needs
	 * to be displayed to a customer/admin (e.g. order details, order emails).
	 *
	 * @param int $post_id Optional. Post/page/product ID to check for a per-item override.
	 *                     0 when there isn't a single owning post (e.g. a WooCommerce order,
	 *                     which can span multiple products, or a multi-post bulk export) -
	 *                     only the global setting is considered in that case.
	 * @return string The password to use, or '' if no password protection applies.
	 */
	function wps_pgfw_get_pdf_password( $post_id = 0 ) {
		$general_settings_data = get_option( 'pgfw_general_settings_save', array() );
		$is_enabled             = array_key_exists( 'pgfw_pdf_password_protection_enable', $general_settings_data ) ? $general_settings_data['pgfw_pdf_password_protection_enable'] : '';
		$global_password        = array_key_exists( 'pgfw_pdf_password', $general_settings_data ) ? $general_settings_data['pgfw_pdf_password'] : '';

		$override_password = $post_id ? get_post_meta( $post_id, '_pgfw_pdf_password_override', true ) : '';

		if ( '' !== $override_password ) {
			return $override_password;
		}
		if ( 'yes' === $is_enabled && '' !== $global_password ) {
			return $global_password;
		}
		return '';
	}

	/**
	 * Apply password protection/encryption to a rendered Dompdf document, using
	 * wps_pgfw_get_pdf_password() to resolve which password (override or global) applies.
	 *
	 * Must be called after $dompdf->render() and before $dompdf->output()/$dompdf->stream().
	 * No-ops when no password applies.
	 *
	 * @param \Dompdf\Dompdf $dompdf  Rendered Dompdf instance.
	 * @param int            $post_id Optional. Post/page/product ID this PDF was generated for.
	 * @return void
	 */
	function wps_pgfw_apply_pdf_security( $dompdf, $post_id = 0 ) {
		$pdf_password = wps_pgfw_get_pdf_password( $post_id );

		if ( '' === $pdf_password || ! is_object( $dompdf ) ) {
			return;
		}

		$canvas = $dompdf->getCanvas();
		if ( $canvas && method_exists( $canvas, 'get_cpdf' ) ) {
			$canvas->get_cpdf()->setEncryption(
				$pdf_password,
				$pdf_password,
				array(
					'print'  => true,
					'copy'   => true,
					'modify' => true,
					'add'    => true,
				)
			);
		}
	}

	/**
	 * Upload a rendered Dompdf document to any enabled cloud storage provider
	 * (Google Drive, Dropbox, Amazon S3), based on the Cloud Storage tab settings.
	 *
	 * Must be called after $dompdf->render() (and after wps_pgfw_apply_pdf_security(),
	 * if used, so password-protected copies are what get uploaded).
	 *
	 * @param \Dompdf\Dompdf $dompdf    Rendered Dompdf instance.
	 * @param string         $file_name Destination file name, e.g. "invoice-12.pdf".
	 * @return void
	 */
	function wps_pgfw_auto_save_pdf_to_cloud( $dompdf, $file_name = 'document.pdf' ) {
		if ( ! class_exists( 'Pdf_Generator_For_Wp_Cloud_Storage' ) ) {
			return;
		}
		$cloud_storage = new Pdf_Generator_For_Wp_Cloud_Storage();
		$cloud_storage->upload_dompdf_output( $dompdf, $file_name );
	}

	/**
	 * Resolve the configured PDF page size/orientation (Body Settings) to CSS pixel
	 * dimensions at dompdf's 96dpi reference, for the PDF Builder canvas to match
	 * exactly what dompdf will actually render.
	 *
	 * @return array { 'width' => int, 'height' => int } in CSS px.
	 */
	function wps_pgfw_get_pdf_page_size_px() {
		$body_settings = get_option( 'pgfw_body_save_settings', array() );
		$page_size     = array_key_exists( 'pgfw_body_page_size', $body_settings ) ? $body_settings['pgfw_body_page_size'] : 'a4';
		$orientation   = array_key_exists( 'pgfw_body_page_orientation', $body_settings ) ? $body_settings['pgfw_body_page_orientation'] : 'portrait';

		if ( 'custom_page' === $page_size
			&& ! empty( $body_settings['pgfw_body_custom_page_size_width'] )
			&& ! empty( $body_settings['pgfw_body_custom_page_size_height'] ) ) {
			$width_pt  = (float) $body_settings['pgfw_body_custom_page_size_width'] * 2.834;
			$height_pt = (float) $body_settings['pgfw_body_custom_page_size_height'] * 2.834;
		} else {
			$paper_size_pts = wps_get_page_sizes( $page_size );
			$width_pt       = isset( $paper_size_pts[2] ) ? $paper_size_pts[2] : 595.28;
			$height_pt      = isset( $paper_size_pts[3] ) ? $paper_size_pts[3] : 841.89;
		}

		$width_px  = (int) round( $width_pt * ( 96 / 72 ) );
		$height_px = (int) round( $height_pt * ( 96 / 72 ) );

		$long_side  = max( $width_px, $height_px );
		$short_side = min( $width_px, $height_px );

		if ( 'landscape' === $orientation ) {
			return array(
				'width'  => $long_side,
				'height' => $short_side,
			);
		}

		return array(
			'width'  => $short_side,
			'height' => $long_side,
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

/**
 * Get option with caching support.
 *
 * @param string $option  Option name.
 * @param mixed  $default Default value if option doesn't exist.
 * @return mixed Option value or default.
 */
function wps_pgfw_get_option_cached( $option, $default = '' ) {
	return get_option( $option, $default );
}
