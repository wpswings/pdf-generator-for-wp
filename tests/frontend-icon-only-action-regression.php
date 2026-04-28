<?php
/**
 * Regression check for icon-only shared frontend PDF actions.
 */

$plugin_dir           = dirname( __DIR__ );
$action_template_file = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-icon-action-template.php';
$public_css_file      = $plugin_dir . '/public/src/scss/pdf-generator-for-wp-public.css';

$files = array(
	$action_template_file,
	$public_css_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$action_template = file_get_contents( $action_template_file );
$public_css      = file_get_contents( $public_css_file );

$required_snippets = array(
	array(
		'haystack' => $action_template,
		'needle'   => "'pgfw-single-pdf-download-button--icon-only'",
	),
	array(
		'haystack' => $action_template,
		'needle'   => 'aria-label="',
	),
	array(
		'haystack' => $public_css,
		'needle'   => '.pgfw-single-pdf-download-button--icon-only',
	),
	array(
		'haystack' => $public_css,
		'needle'   => 'background: transparent !important;',
	),
	array(
		'haystack' => $public_css,
		'needle'   => 'padding: 0 !important;',
	),
);

$forbidden_snippets = array(
	array(
		'haystack' => $action_template,
		'needle'   => 'pgfw-single-pdf-download-button__label',
	),
);

$missing = array();

foreach ( $required_snippets as $snippet ) {
	if ( false === strpos( $snippet['haystack'], $snippet['needle'] ) ) {
		$missing[] = 'missing: ' . $snippet['needle'];
	}
}

foreach ( $forbidden_snippets as $snippet ) {
	if ( false !== strpos( $snippet['haystack'], $snippet['needle'] ) ) {
		$missing[] = 'forbidden: ' . $snippet['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Frontend icon-only action contract failed:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Frontend icon-only action contract present.\n" );
