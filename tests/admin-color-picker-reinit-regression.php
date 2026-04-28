<?php
/**
 * Regression check for reusable admin color picker initialization.
 *
 * Icon-display color fields can be rendered after the initial document-ready
 * pass, so the custom settings script must expose a reusable initializer and
 * the shared admin bootstrap must invoke it.
 */

$plugin_dir        = dirname( __DIR__ );
$admin_js_file     = $plugin_dir . '/admin/src/js/pdf-generator-for-wp-admin.js';
$admin_custom_file = $plugin_dir . '/admin/src/js/pdf-generator-for-wp-admin-custom.js';

$files = array(
	$admin_js_file,
	$admin_custom_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$admin_js     = file_get_contents( $admin_js_file );
$admin_custom = file_get_contents( $admin_custom_file );

$checks = array(
	array(
		'haystack' => $admin_custom,
		'needle'   => 'window.pgfwInitCustomSettingsUI = function',
	),
	array(
		'haystack' => $admin_custom,
		'needle'   => '$input.data( \'pgfwColorPickerReady\', true );',
	),
	array(
		'haystack' => $admin_custom,
		'needle'   => '$input.wpColorPicker({',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => "if ( typeof window.pgfwInitCustomSettingsUI === 'function' ) {",
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'window.pgfwInitCustomSettingsUI( document );',
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing admin color picker reinit contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Admin color picker reinit contract present.\n" );
