<?php
/**
 * Regression check for icon-display color field styling config.
 *
 * The icon-display color fields should use the plain shared color-picker row
 * config, without the extra separate-border wrapper that changes their layout.
 */

$admin_settings_file = dirname( __DIR__ ) . '/admin/class-pdf-generator-for-wp-admin.php';
$field_renderer_file = dirname( __DIR__ ) . '/includes/class-pdf-generator-for-wp.php';

foreach ( array( $admin_settings_file, $field_renderer_file ) as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "Required file not found: {$file}\n" );
		exit( 1 );
	}
}

$admin_settings = file_get_contents( $admin_settings_file );
$field_renderer = file_get_contents( $field_renderer_file );

if ( false === $admin_settings ) {
	fwrite( STDERR, "Unable to read admin settings file: {$admin_settings_file}\n" );
	exit( 1 );
}

if ( false === $field_renderer ) {
	fwrite( STDERR, "Unable to read field renderer file: {$field_renderer_file}\n" );
	exit( 1 );
}

$field_ids = array(
	'pgfw_template_color',
	'pgfw_template_text_color',
);

$failures = array();

foreach ( $field_ids as $field_id ) {
	$id_needle = "'id'           => '" . $field_id . "'";
	$id_pos    = strpos( $admin_settings, $id_needle );

	if ( false === $id_pos ) {
		$failures[] = "Field block not found for {$field_id}";
		continue;
	}

	$block_start = strrpos( substr( $admin_settings, 0, $id_pos ), 'array(' );
	$next_block  = strpos( $admin_settings, "\n\t\t\tarray(", $id_pos );
	$list_end    = strpos( $admin_settings, "\n\t\t);", $id_pos );
	$block_end   = false !== $next_block ? $next_block : $list_end;

	if ( false === $block_start || false === $block_end ) {
		$failures[] = "Unable to isolate field block for {$field_id}";
		continue;
	}

	$field_block = substr( $admin_settings, $block_start, $block_end - $block_start );

	if ( false === strpos( $field_block, "'type'         => 'color'" ) && false === strpos( $field_block, "'type'        => 'color'" ) ) {
		$failures[] = "Field {$field_id} is not configured as a color field";
	}

	if ( false === strpos( $field_block, 'pgfw_native_color_picker' ) ) {
		$failures[] = "Field {$field_id} is missing native color picker class";
	}
}

$renderer_checks = array(
	'pgfw-color-picker-card--native',
	'pgfw-color-picker-native-control',
	'pgfw_native_color_picker',
);

foreach ( $renderer_checks as $needle ) {
	if ( false === strpos( $field_renderer, $needle ) ) {
		$failures[] = "Renderer missing native color support token: {$needle}";
	}
}

if ( ! empty( $failures ) ) {
	fwrite( STDERR, "Icon display color field style regression failed:\n- " . implode( "\n- ", $failures ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Icon display color field style config present.\n" );
