<?php
/**
 * Regression check for admin icon upload wiring.
 */

$plugin_dir          = dirname( __DIR__ );
$admin_settings_file = $plugin_dir . '/admin/class-pdf-generator-for-wp-admin.php';
$admin_custom_js     = $plugin_dir . '/admin/src/js/pdf-generator-for-wp-admin-custom.js';
$field_renderer_file = $plugin_dir . '/includes/class-pdf-generator-for-wp.php';

foreach ( array( $admin_settings_file, $admin_custom_js, $field_renderer_file ) as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$admin_settings = file_get_contents( $admin_settings_file );
$custom_js      = file_get_contents( $admin_custom_js );
$field_renderer = file_get_contents( $field_renderer_file );

$checks = array(
	array(
		'haystack' => $admin_settings,
		'needle'   => "'id'          => 'sub_pgfw_pdf_bulk_download_icon'",
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => "'name'        => 'sub_pgfw_pdf_bulk_download_icon'",
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => "'sub_id'      => 'wps_pgfw_pdf_bulk_download_icon'",
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => '$sub_pgfw_pdf_invoice_single_download_icon = array_key_exists( \'sub_pgfw_pdf_invoice_single_download_icon\', $pgfw_display_settings ) ? $pgfw_display_settings[\'sub_pgfw_pdf_invoice_single_download_icon\'] : \'\';',
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => "'name'         => 'sub_pgfw_pdf_invoice_single_download_icon'",
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => '\'value\'        => $sub_pgfw_pdf_invoice_single_download_icon',
	),
	array(
		'haystack' => $custom_js,
		'needle'   => "$(document).off('click.pgfwSinglePdfUpload', '#pgfw_pdf_single_download_icon')",
	),
	array(
		'haystack' => $custom_js,
		'needle'   => "$(document).off('click.pgfwBulkPdfUpload', '#wps_pgfw_pdf_bulk_download_icon')",
	),
	array(
		'haystack' => $custom_js,
		'needle'   => "pgfwUpdateUploadCard('#sub_pgfw_pdf_bulk_download_icon', '.wps_bulk_pdf_icon_image', '#wps_bulk_pdf_icon_image_remove', response.url);",
	),
	array(
		'haystack' => $custom_js,
		'needle'   => "pgfwUpdateUploadCard('#sub_pgfw_pdf_invoice_single_download_icon', '.pgfw_single_pdf_icon_image_invoice', '#pgfw_single_pdf_invoice_icon_image_remove', response.url);",
	),
	array(
		'haystack' => $field_renderer,
		'needle'   => '<button type="button" class="mdc-button--raised ',
	),
	array(
		'haystack' => $field_renderer,
		'needle'   => 'pgfw-upload-card__preview-frame',
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing admin icon upload contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Admin icon upload contract present.\n" );
