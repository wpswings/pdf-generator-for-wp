<?php
/**
 * Regression check for applying icon-display templates to related action icons.
 */

$plugin_dir         = dirname( __DIR__ );
$public_class_file  = $plugin_dir . '/public/class-pdf-generator-for-wp-public.php';
$button_template    = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php';
$public_css_file    = $plugin_dir . '/public/src/scss/pdf-generator-for-wp-public.css';

$files = array(
	$public_class_file,
	$button_template,
	$public_css_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$public_class_contents = file_get_contents( $public_class_file );
$button_template_data  = file_get_contents( $button_template );
$public_css_data       = file_get_contents( $public_css_file );

$checks = array(
	array(
		'haystack' => $button_template_data,
		'needle'   => 'pgfw_render_icon_action_button(',
	),
	array(
		'haystack' => $button_template_data,
		'needle'   => "'action_type'      => 'print'",
	),
	array(
		'haystack' => $button_template_data,
		'needle'   => "'action_type'      => 'share'",
	),
	array(
		'haystack' => $button_template_data,
		'needle'   => 'wps_pgfw_whatsapp_share_icon',
	),
	array(
		'haystack' => $public_css_data,
		'needle'   => 'a.pgfw-single-pdf-download-a,',
	),
	array(
		'haystack' => $public_css_data,
		'needle'   => '.pgfw-action-button .pgfw-single-pdf-download-button__label::after',
	),
	array(
		'haystack' => $public_css_data,
		'needle'   => 'display: none;',
	),
	array(
		'haystack' => $public_class_contents,
		'needle'   => "'span' => array(",
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing action icon display contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Action icon display contract present.\n" );
