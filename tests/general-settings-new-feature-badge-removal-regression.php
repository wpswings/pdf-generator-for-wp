<?php
/**
 * Regression check for removing the General Settings "New Feature" badge.
 */

$admin_file = dirname( __DIR__ ) . '/admin/class-pdf-generator-for-wp-admin.php';
$css_file   = dirname( __DIR__ ) . '/admin/src/css/pdf-generator-for-wp-admin-global.css';

foreach ( array( $admin_file, $css_file ) as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$admin_source = file_get_contents( $admin_file );
$css_source   = file_get_contents( $css_file );

$unexpected = array(
	"'parent-class' => 'pgfw_new-feature'",
	'.pgfw_new-feature',
	'content: "New Feature";',
);

$found = array();

foreach ( $unexpected as $needle ) {
	if ( false !== strpos( $admin_source . "\n" . $css_source, $needle ) ) {
		$found[] = $needle;
	}
}

if ( ! empty( $found ) ) {
	fwrite( STDERR, "General Settings new-feature badge should be removed:\n- " . implode( "\n- ", $found ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "General Settings new-feature badge removed.\n" );
