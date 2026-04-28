<?php
/**
 * Regression check for the dashboard flash sale banner layout and dismiss control.
 */

$plugin_dir     = dirname( __DIR__ );
$dashboard_file = $plugin_dir . '/admin/partials/pdf-generator-for-wp-admin-dashboard.php';
$admin_js_file  = $plugin_dir . '/admin/src/js/pdf-generator-for-wp-admin.js';
$admin_css_file = $plugin_dir . '/admin/src/css/pdf-generator-for-wp-admin-modern.css';

$files = array(
	$dashboard_file,
	$admin_js_file,
	$admin_css_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$dashboard = file_get_contents( $dashboard_file );
$admin_js  = file_get_contents( $admin_js_file );
$admin_css = file_get_contents( $admin_css_file );

$checks = array(
	array(
		'haystack' => $dashboard,
		'needle'   => 'data-pgfw-flashbar="true"',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'class="pgfw-flashbar__group"',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'class="pgfw-flashbar__dismiss"',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'data-pgfw-dismiss-flashbar="true"',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'pgfw_flashbar_dismissed',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => '[data-pgfw-dismiss-flashbar]',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-flashbar__group{display:flex;align-items:center;justify-content:center;',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-flashbar__cta-wrap{display:flex;align-items:center;justify-content:center}',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => '<div class="pgfw-flashbar__cta-wrap">',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'class="pgfw-flashbar__content"',
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing dashboard flashbar contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Dashboard flashbar contract present.\n" );
