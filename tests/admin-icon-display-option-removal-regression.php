<?php
/**
 * Regression check for removing the PDF Icon Display admin option.
 */

$admin_settings_file = dirname( __DIR__ ) . '/admin/class-pdf-generator-for-wp-admin.php';

if ( ! file_exists( $admin_settings_file ) ) {
	fwrite( STDERR, "Admin settings file not found: {$admin_settings_file}\n" );
	exit( 1 );
}

$admin_settings = file_get_contents( $admin_settings_file );

if ( false === $admin_settings ) {
	fwrite( STDERR, "Unable to read admin settings file: {$admin_settings_file}\n" );
	exit( 1 );
}

$forbidden_snippets = array(
	"'title'       => __( 'PDF Icon Display', 'pdf-generator-for-wp' )",
	"'id'          => 'pgfw_pdf_icon_display_template'",
	'$pgfw_pdf_icon_display_template',
);

$present = array();

foreach ( $forbidden_snippets as $snippet ) {
	if ( false !== strpos( $admin_settings, $snippet ) ) {
		$present[] = $snippet;
	}
}

if ( ! empty( $present ) ) {
	fwrite( STDERR, "PDF Icon Display admin option still present:\n- " . implode( "\n- ", $present ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "PDF Icon Display admin option removed.\n" );
