<?php
/**
 * Regression check for the dashboard Talk to an Expert modal.
 */

$plugin_dir      = dirname( __DIR__ );
$dashboard_file  = $plugin_dir . '/admin/partials/pdf-generator-for-wp-admin-dashboard.php';
$admin_js_file   = $plugin_dir . '/admin/src/js/pdf-generator-for-wp-admin.js';
$admin_css_file  = $plugin_dir . '/admin/src/css/pdf-generator-for-wp-admin-modern.css';

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
		'needle'   => '$talk_to_expert_url   = \'https://share-eu1.hsforms.com/1kb_CTsGnSFiHip8vtHKGIAf5cts\';',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'class="pgfw-services-card__cta" href="#"',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'data-pgfw-open-expert-modal',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'class="pgfw-expert-modal"',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'class="pgfw-expert-modal__iframe"',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => '[data-pgfw-open-expert-modal]',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => '.pgfw-expert-modal',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'Escape',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-modal{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-modal__dialog{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-modal__iframe{',
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing Talk to an Expert modal contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert modal contract present.\n" );
