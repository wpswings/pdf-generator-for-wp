<?php
/**
 * Regression check for template-specific frontend icon sources.
 */

$plugin_dir = dirname( __DIR__ );
$global_functions_file = $plugin_dir . '/includes/pdf-generator-for-wp-global-functions.php';
$button_template_file  = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php';
$modal_template_file   = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php';

$files = array(
	$global_functions_file,
	$button_template_file,
	$modal_template_file,
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

$checks = array(
	array(
		'haystack' => $global_functions,
		'needle'   => 'function pgfw_get_single_pdf_download_icon_src',
	),
	array(
		'haystack' => $global_functions,
		'needle'   => "'style-2' => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/adobe_badge.svg'",
	),
	array(
		'haystack' => $global_functions,
		'needle'   => "'default' => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/PDF_Tray.svg'",
	),
	array(
		'haystack' => $button_template,
		'needle'   => "pgfw_get_icon_action_icon_src( 'download', \$settings )",
	),
	array(
		'haystack' => $modal_template,
		'needle'   => "pgfw_get_icon_action_icon_src( 'email', \$settings )",
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing template icon mapping contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Template icon mapping contract present.\n" );
