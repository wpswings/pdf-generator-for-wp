<?php
/**
 * Regression check for Talk to an Expert field styling.
 */

$css_file = dirname( __DIR__ ) . '/admin/src/css/pdf-generator-for-wp-admin-modern.css';

if ( ! file_exists( $css_file ) ) {
	fwrite( STDERR, "File not found: {$css_file}\n" );
	exit( 1 );
}

$css = file_get_contents( $css_file );

$checks = array(
	'.pgfw-expert-form__control{width:100%;max-width:none !important;',
	'border:1px solid #ddcff6;',
	'border-radius:14px;',
	'background:#fcfaff;',
	'box-sizing:border-box;',
	'.pgfw-expert-form__control:hover{border-color:#c8adff;background:#fff;}',
	'.pgfw-expert-form__control--select{padding-right:42px;max-width:none !important;-webkit-appearance:none;appearance:none;',
	'.pgfw-expert-form input.pgfw-expert-form__control,',
	'.pgfw-expert-form select.pgfw-expert-form__control,',
	'.pgfw-expert-form textarea.pgfw-expert-form__control{',
	'border-color:#ddcff6 !important;',
	'background:#fcfaff !important;',
	'margin-bottom:0 !important;',
	'.pgfw-expert-form select.pgfw-expert-form__control{',
	'padding:0 42px 0 14px !important;',
	'color:#24124c !important;',
	'.pgfw-expert-form input.pgfw-expert-form__control:focus,',
	'.pgfw-expert-form select.pgfw-expert-form__control:focus,',
	'.pgfw-expert-form textarea.pgfw-expert-form__control:focus{',
	'box-shadow:0 0 0 3px rgba(180,138,255,.14) !important;',
);

$missing = array();

foreach ( $checks as $needle ) {
	if ( false === strpos( $css, $needle ) ) {
		$missing[] = $needle;
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing Talk to an Expert field styling contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert field styling contract present.\n" );
