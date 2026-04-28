<?php
/**
 * Regression check for the shared frontend icon setting contract.
 */

$plugin_dir             = dirname( __DIR__ );
$global_functions_file  = $plugin_dir . '/includes/pdf-generator-for-wp-global-functions.php';
$button_template_file   = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php';
$modal_template_file    = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php';
$admin_settings_file    = $plugin_dir . '/admin/class-pdf-generator-for-wp-admin.php';

$files = array(
	$global_functions_file,
	$button_template_file,
	$modal_template_file,
	$admin_settings_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$global_functions = file_get_contents( $global_functions_file );
$button_template  = file_get_contents( $button_template_file );
$modal_template   = file_get_contents( $modal_template_file );
$admin_settings   = file_get_contents( $admin_settings_file );

$checks = array(
	array(
		'haystack' => $global_functions,
		'needle'   => 'function pgfw_get_frontend_icon_display_settings',
	),
	array(
		'haystack' => $global_functions,
		'needle'   => "'wps_wpg_single_pdf_icon_name'",
	),
	array(
		'haystack' => $global_functions,
		'needle'   => "'single_pdf_icon_name'",
	),
	array(
		'haystack' => $button_template,
		'needle'   => 'pgfw_get_frontend_icon_display_settings()',
	),
	array(
		'haystack' => $modal_template,
		'needle'   => 'pgfw_get_frontend_icon_display_settings()',
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => "'id'          => 'wps_wpg_single_pdf_icon_name'",
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => "'name'        => 'wps_wpg_single_pdf_icon_name'",
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing shared icon setting contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Shared icon setting contract present.\n" );
