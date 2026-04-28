<?php
/**
 * Regression check for Talk to an Expert success modal behavior.
 */

$js_file = dirname( __DIR__ ) . '/admin/src/js/pdf-generator-for-wp-admin.js';

if ( ! file_exists( $js_file ) ) {
	fwrite( STDERR, "File not found: {$js_file}\n" );
	exit( 1 );
}

$js = file_get_contents( $js_file );

$checks = array(
	'showExpertThankYou( message );',
	'data-pgfw-expert-thank-you',
	'expertFormPanel.hidden = true;',
	'expertThankYou.hidden = false;',
	'window.setTimeout( function() {',
	'window.location.href = pgfw_admin_param.reloadurl;',
	'}, 4000 );',
);

$missing = array();

foreach ( $checks as $needle ) {
	if ( false === strpos( $js, $needle ) ) {
		$missing[] = $needle;
	}
}

if ( false !== strpos( $js, 'response.data.redirect_url' ) || false !== strpos( $js, 'window.location.href = response.data.redirect_url;' ) ) {
	$missing[] = 'Legacy redirect contract still present';
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing Talk to an Expert success modal contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert success modal contract present.\n" );
