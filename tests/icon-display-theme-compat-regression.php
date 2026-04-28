<?php
/**
 * Regression check for CSS-variable-driven frontend icon sizing and wrapper layout.
 */

$plugin_dir      = dirname( __DIR__ );
$public_css_file = $plugin_dir . '/public/src/scss/pdf-generator-for-wp-public.css';

if ( ! file_exists( $public_css_file ) ) {
	fwrite( STDERR, "CSS file not found: {$public_css_file}\n" );
	exit( 1 );
}

$public_css = file_get_contents( $public_css_file );

if ( false === $public_css ) {
	fwrite( STDERR, "Unable to read CSS file: {$public_css_file}\n" );
	exit( 1 );
}

$required_snippets = array(
	'--pgfw-icon-width',
	'--pgfw-icon-height',
	'--pgfw-icon-justify',
	'.pgfw-icon-display {',
	'justify-content: var(--pgfw-icon-justify, center);',
	'width: var(--pgfw-icon-width, 24px);',
	'height: var(--pgfw-icon-height, 24px);',
);

$forbidden_snippets = array(
	'height: 22px !important;',
	'height: 34px !important;',
	'height: 24px !important;',
	'height: 20px !important;',
);

$missing = array();

foreach ( $required_snippets as $snippet ) {
	if ( false === strpos( $public_css, $snippet ) ) {
		$missing[] = $snippet;
	}
}

$present_forbidden = array();

foreach ( $forbidden_snippets as $snippet ) {
	if ( false !== strpos( $public_css, $snippet ) ) {
		$present_forbidden[] = $snippet;
	}
}

if ( ! empty( $missing ) || ! empty( $present_forbidden ) ) {
	$message = '';
	if ( ! empty( $missing ) ) {
		$message .= "Missing theme-compat CSS contract:\n- " . implode( "\n- ", $missing ) . "\n";
	}
	if ( ! empty( $present_forbidden ) ) {
		$message .= "Forbidden fixed-height CSS still present:\n- " . implode( "\n- ", $present_forbidden ) . "\n";
	}
	fwrite( STDERR, $message );
	exit( 1 );
}

fwrite( STDOUT, "Theme-compat CSS contract present.\n" );
