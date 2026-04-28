<?php
/**
 * Regression check for the public Stamped Seal template CSS.
 *
 * This is a lightweight file-based test because the plugin has no existing
 * automated test harness in this workspace.
 */

$css_file = dirname( __DIR__ ) . '/public/src/scss/pdf-generator-for-wp-public.css';

if ( ! file_exists( $css_file ) ) {
	fwrite( STDERR, "CSS file not found: {$css_file}\n" );
	exit( 1 );
}

$css = file_get_contents( $css_file );

if ( false === $css ) {
	fwrite( STDERR, "Unable to read CSS file: {$css_file}\n" );
	exit( 1 );
}

$required_snippets = array(
	'.pgfw-icon-display--style-5 .pgfw-single-pdf-download-button::before',
	'.pgfw-icon-display--style-5 .pgfw-single-pdf-download-button::after',
	'.pgfw-single-pdf-download-button--style-5::before',
	'.pgfw-single-pdf-download-button--style-5::after',
	'overflow: visible;',
	'position: relative;',
	'min-height: 58px;',
	'min-width: 58px;',
	'height: 22px;',
	'width: 40px;',
	'top: 17px;',
	'left: -20px;',
	'right: -20px;',
	'font-size: 9px;',
	'letter-spacing: 0.24em;',
);

$missing = array();

foreach ( $required_snippets as $snippet ) {
	if ( false === strpos( $css, $snippet ) ) {
		$missing[] = $snippet;
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing style-5 frontend CSS contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "style-5 frontend CSS contract present.\n" );
