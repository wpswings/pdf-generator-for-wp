<?php
/**
 * Regression check for Talk to an Expert success message cleanup.
 */

define( 'ABSPATH', __DIR__ );

$plugin_file = dirname( __DIR__ ) . '/includes/class-pdf-generator-for-wp-talk-to-expert-form.php';

if ( ! file_exists( $plugin_file ) ) {
	fwrite( STDERR, "File not found: {$plugin_file}\n" );
	exit( 1 );
}

function esc_html__( $text, $domain = '' ) {
	return $text;
}

function wp_strip_all_tags( $text ) {
	return strip_tags( $text );
}

require_once $plugin_file;

$reflection = new ReflectionClass( 'Pdf_Generator_For_Wp_Talk_To_Expert_Form' );
$instance   = $reflection->newInstanceWithoutConstructor();
$method     = $reflection->getMethod( 'wps_pgfw_get_success_message' );
$method->setAccessible( true );

$message = $method->invoke(
	$instance,
	array(
		'inlineMessage' => '&#xa0; Thank you for submitting your request.',
	)
);

if ( 'Thank you for submitting your request.' !== $message ) {
	fwrite( STDERR, "Expected cleaned success message, got: {$message}\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert success message strips encoded spacing artifacts.\n" );
