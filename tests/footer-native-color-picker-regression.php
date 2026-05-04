<?php
/**
 * Regression check for the footer native color-picker wiring.
 *
 * @package Pdf_Generator_For_Wp
 */

$admin_settings_file = dirname( __DIR__ ) . '/admin/class-pdf-generator-for-wp-admin.php';
$admin_custom_js_file = dirname( __DIR__ ) . '/admin/src/js/pdf-generator-for-wp-admin-custom.js';

if ( ! file_exists( $admin_settings_file ) ) {
	fwrite( STDERR, "Admin settings file not found: {$admin_settings_file}\n" );
	exit( 1 );
}

if ( ! file_exists( $admin_custom_js_file ) ) {
	fwrite( STDERR, "Admin custom JS file not found: {$admin_custom_js_file}\n" );
	exit( 1 );
}

$admin_settings = file_get_contents( $admin_settings_file );
$admin_custom_js = file_get_contents( $admin_custom_js_file );

if ( false === $admin_settings ) {
	fwrite( STDERR, "Unable to read admin settings file: {$admin_settings_file}\n" );
	exit( 1 );
}

if ( false === $admin_custom_js ) {
	fwrite( STDERR, "Unable to read admin custom JS file: {$admin_custom_js_file}\n" );
	exit( 1 );
}

$admin_required_needles = array(
	"'id'          => 'pgfw_footer_color'",
	"'class'       => 'pgfw_color_picker pgfw_footer_color pgfw_native_color_picker'",
	"'pdf-generator-for-wp-footer'",
);

$js_required_needles = array(
	'$input.val( value );',
	'$input.trigger( \'change\' );',
);

foreach ( $admin_required_needles as $needle ) {
	if ( false === strpos( $admin_settings, $needle ) ) {
		fwrite( STDERR, "Missing footer native color-picker contract:\n{$needle}\n" );
		exit( 1 );
	}
}

foreach ( $js_required_needles as $needle ) {
	if ( false === strpos( $admin_custom_js, $needle ) ) {
		fwrite( STDERR, "Missing footer native color-picker sync contract:\n{$needle}\n" );
		exit( 1 );
	}
}

fwrite( STDOUT, "Footer color setting uses the native color-picker card.\n" );
