<?php
/**
 * Regression check for invoice order-status multiselect wiring.
 *
 * The "Download Invoice for Users at Order Status" field should use the same
 * default multiselect class token that the shared admin bootstrap upgrades to
 * Select2. A typo here leaves the field as a raw native multiselect.
 */

$admin_settings_file = dirname( __DIR__ ) . '/admin/class-pdf-generator-for-wp-admin.php';

if ( ! file_exists( $admin_settings_file ) ) {
	fwrite( STDERR, "Required file not found: {$admin_settings_file}\n" );
	exit( 1 );
}

$admin_settings = file_get_contents( $admin_settings_file );

if ( false === $admin_settings ) {
	fwrite( STDERR, "Unable to read admin settings file: {$admin_settings_file}\n" );
	exit( 1 );
}

$field_id_needle = "'id'          => 'wpg_allow_invoice_generation_for_orders'";
$field_id_pos    = strpos( $admin_settings, $field_id_needle );

if ( false === $field_id_pos ) {
	fwrite( STDERR, "Invoice order-status field definition not found.\n" );
	exit( 1 );
}

$block_start = strrpos( substr( $admin_settings, 0, $field_id_pos ), 'array(' );
$next_block  = strpos( $admin_settings, "\n\t\t\tarray(", $field_id_pos );
$list_end    = strpos( $admin_settings, "\n\t\t);", $field_id_pos );
$block_end   = false !== $next_block ? $next_block : $list_end;

if ( false === $block_start || false === $block_end ) {
	fwrite( STDERR, "Unable to isolate invoice order-status field block.\n" );
	exit( 1 );
}

$field_block = substr( $admin_settings, $block_start, $block_end - $block_start );
$failures    = array();

if ( false === strpos( $field_block, "'type'        => 'multiselect'" ) ) {
	$failures[] = 'Field is no longer configured as a multiselect.';
}

if ( false === strpos( $field_block, 'wps-defaut-multiselect' ) ) {
	$failures[] = 'Field is missing the shared default multiselect class token.';
}

if ( false !== strpos( $field_block, 'wpg-defaut-multiselect' ) ) {
	$failures[] = 'Field still contains the mistyped default multiselect class token.';
}

if ( ! empty( $failures ) ) {
	fwrite( STDERR, "Invoice order-status multiselect regression failed:\n- " . implode( "\n- ", $failures ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Invoice order-status multiselect wiring present.\n" );
