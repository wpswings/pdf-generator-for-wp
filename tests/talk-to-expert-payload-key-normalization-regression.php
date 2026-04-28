<?php
/**
 * Regression check for Talk to an Expert payload key normalization.
 */

$js_file = dirname( __DIR__ ) . '/admin/src/js/pdf-generator-for-wp-admin.js';

if ( ! file_exists( $js_file ) ) {
	fwrite( STDERR, "File not found: {$js_file}\n" );
	exit( 1 );
}

$source = file_get_contents( $js_file );

$required_needles = array(
	'const normalizedKey = key.replace( /\[\]$/, \'\' );',
	'Object.prototype.hasOwnProperty.call( payload, normalizedKey )',
	'const currentValue = payload[ normalizedKey ];',
	'payload[ normalizedKey ] = [];',
	'payload[ normalizedKey ].push( currentValue );',
	'payload[ normalizedKey ].push( value );',
	'payload[ normalizedKey ] = value;',
);

foreach ( $required_needles as $needle ) {
	if ( false === strpos( $source, $needle ) ) {
		fwrite( STDERR, "Missing Talk to an Expert payload key normalization contract:\n{$needle}\n" );
		exit( 1 );
	}
}

fwrite( STDOUT, "Talk to an Expert payload keys are normalized before submit.\n" );
