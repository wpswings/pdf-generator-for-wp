<?php
/**
 * Regression check for the plugin dashboard marketing rail structure.
 */

$dashboard_file = dirname( __DIR__ ) . '/admin/partials/pdf-generator-for-wp-admin-dashboard.php';
$css_file       = dirname( __DIR__ ) . '/admin/src/css/pdf-generator-for-wp-admin-modern.css';

$files = array(
	$dashboard_file,
	$css_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$dashboard = file_get_contents( $dashboard_file );
$css       = file_get_contents( $css_file );

$checks = array(
	array(
		'haystack' => $dashboard,
		'needle'   => "esc_html_e( 'Grow Your Store With Our Services', 'pdf-generator-for-wp' )",
	),
	array(
		'haystack' => $dashboard,
		'needle'   => "esc_html_e( 'SEO Services', 'pdf-generator-for-wp' )",
	),
	array(
		'haystack' => $dashboard,
		'needle'   => "esc_html_e( 'Google Ads Setup And G4 Setup', 'pdf-generator-for-wp' )",
	),
	array(
		'haystack' => $dashboard,
		'needle'   => "esc_html_e( 'Speed Optimization', 'pdf-generator-for-wp' )",
	),
	array(
		'haystack' => $dashboard,
		'needle'   => "esc_html_e( 'Talk to an Expert', 'pdf-generator-for-wp' )",
	),
	array(
		'haystack' => $dashboard,
		'needle'   => "esc_html_e( 'Still facing problems?', 'pdf-generator-for-wp' )",
	),
	array(
		'haystack' => $dashboard,
		'needle'   => "esc_html_e( 'Explore more plugins', 'pdf-generator-for-wp' )",
	),
	array(
		'haystack' => $css,
		'needle'   => '.pgfw-services-card__eyebrow',
	),
	array(
		'haystack' => $css,
		'needle'   => '.pgfw-contact-card',
	),
	array(
		'haystack' => $css,
		'needle'   => '.pgfw-plugin-card',
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing dashboard marketing rail contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Dashboard marketing rail contract present.\n" );
