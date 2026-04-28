<?php
/**
 * Regression check for the Adobe Badge SVG asset used by style-2.
 */

$svg_file = dirname( __DIR__ ) . '/admin/src/images/adobe_badge.svg';

if ( ! file_exists( $svg_file ) ) {
	fwrite( STDERR, "SVG file not found: {$svg_file}\n" );
	exit( 1 );
}

$svg = file_get_contents( $svg_file );

if ( false === $svg ) {
	fwrite( STDERR, "Unable to read SVG file: {$svg_file}\n" );
	exit( 1 );
}

$required_snippets = array(
	'width="24"',
	'height="32"',
	'fill="#EF4444"',
	'>PDF<',
);

$missing = array();

foreach ( $required_snippets as $snippet ) {
	if ( false === strpos( $svg, $snippet ) ) {
		$missing[] = $snippet;
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing style-2 Adobe badge SVG contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Style-2 Adobe badge SVG contract present.\n" );
