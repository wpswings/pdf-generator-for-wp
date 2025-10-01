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


// Removed unused legacy class block
// -----------------------------
// AJAX: Securely fetch remote PDF and stream bytes to browser
// -----------------------------
add_action('wp_ajax_fb_fetch_pdf', 'wps_pgfw_fb_fetch_pdf');
add_action('wp_ajax_nopriv_fb_fetch_pdf', 'wps_pgfw_fb_fetch_pdf');
add_action('wp_ajax_ifb_upload_pdf', 'wps_pgfw_upload_pdf');

// AJAX: fetch remote PDF bytes via server for CORS-safe conversion
function wps_pgfw_fb_fetch_pdf() {
    // Validate nonce
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'fb_fetch_pdf')) {
        status_header(403);
        echo 'Invalid nonce';
        exit;
    }

    // Validate URL
    $url = isset($_POST['url']) ? esc_url_raw($_POST['url']) : '';
    if (!$url || !filter_var($url, FILTER_VALIDATE_URL)) {
        status_header(400);
        echo 'Invalid URL';
        exit;
    }

    // Only allow http/https
    $scheme = wp_parse_url($url, PHP_URL_SCHEME);
    if (!in_array($scheme, array('http','https'), true)) {
        status_header(400);
        echo 'Unsupported URL scheme';
        exit;
    }

    // Fetch remote PDF via WordPress HTTP API
    $response = wp_remote_get($url, array(
        'timeout' => 20,
        'redirection' => 5,
        'user-agent' => 'InteractiveFlipbook/1.0 (+WordPress)'
    ));

    if (is_wp_error($response)) {
        status_header(502);
        echo $response->get_error_message();
        exit;
    }

    $code = wp_remote_retrieve_response_code($response);
    if ($code < 200 || $code >= 300) {
        status_header($code ?: 502);
        echo 'Remote server responded with status ' . intval($code);
        exit;
    }

    $headers = wp_remote_retrieve_headers($response);
    $content_type = isset($headers['content-type']) ? strtolower(explode(';', $headers['content-type'])[0]) : '';
    // Some servers mislabel PDFs; allow by extension as a fallback
    $is_pdf_type = ($content_type === 'application/pdf');
    $path_ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?: '', PATHINFO_EXTENSION));
    $is_pdf_ext = ($path_ext === 'pdf');
    if (!$is_pdf_type && !$is_pdf_ext) {
        status_header(415);
        echo 'URL does not point to a PDF';
        exit;
    }

    $body = wp_remote_retrieve_body($response);
    if ($body === '' || $body === null) {
        status_header(502);
        echo 'Empty response body';
        exit;
    }

    // Stream raw bytes
    nocache_headers();
    header('Content-Type: application/pdf');
    header('Content-Length: ' . strlen($body));
    header('X-Content-Type-Options: nosniff');
    echo $body;
    exit;
}

// AJAX: handle secure PDF upload to Media Library
function wps_pgfw_upload_pdf() {
    $nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';
    if (!wp_verify_nonce($nonce, 'ifb_upload_pdf')) {
        wp_send_json_error('Invalid nonce', 403);
    }
    if (!current_user_can('upload_files')) {
        wp_send_json_error('Permission denied', 403);
    }

    if (!isset($_FILES['pdf']) || empty($_FILES['pdf']['name'])) {
        wp_send_json_error('No file provided', 400);
    }

    $file = $_FILES['pdf'];
    $type = wp_check_filetype($file['name']);
    if (strtolower($type['ext']) !== 'pdf') {
        wp_send_json_error('Only PDF files are allowed', 415);
    }

    require_once ABSPATH . 'wp-admin/includes/file.php';
    require_once ABSPATH . 'wp-admin/includes/media.php';
    require_once ABSPATH . 'wp-admin/includes/image.php';

    $overrides = array('test_form' => false, 'mimes' => array('pdf' => 'application/pdf'));
    $file_arr = array(
        'name' => $file['name'],
        'type' => $file['type'],
        'tmp_name' => $file['tmp_name'],
        'error' => $file['error'],
        'size' => $file['size']
    );

    $movefile = wp_handle_upload($file_arr, $overrides);
    if (!$movefile || isset($movefile['error'])) {
        wp_send_json_error($movefile && isset($movefile['error']) ? $movefile['error'] : 'Upload failed');
    }

    $attachment = array(
        'post_mime_type' => 'application/pdf',
        'post_title'     => sanitize_file_name(basename($movefile['file'])),
        'post_content'   => '',
        'post_status'    => 'inherit'
    );
    $attach_id = wp_insert_attachment($attachment, $movefile['file']);
    if (is_wp_error($attach_id)) {
        wp_send_json_error($attach_id->get_error_message());
    }
    require_once ABSPATH . 'wp-admin/includes/image.php';
    wp_update_attachment_metadata($attach_id, wp_generate_attachment_metadata($attach_id, $movefile['file']));

    $url = $movefile['url'];
    wp_send_json_success(array('id' => $attach_id, 'url' => $url));
}