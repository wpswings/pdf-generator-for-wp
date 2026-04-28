<?php
/**
 * Regression check for Talk to an Expert HubSpot payload shape.
 */

define( 'ABSPATH', __DIR__ );

$plugin_file = dirname( __DIR__ ) . '/includes/class-pdf-generator-for-wp-talk-to-expert-form.php';

if ( ! file_exists( $plugin_file ) ) {
	fwrite( STDERR, "File not found: {$plugin_file}\n" );
	exit( 1 );
}

$GLOBALS['pgfw_captured_hubspot_request'] = null;

function admin_url( $path = '' ) {
	return 'https://example.com/wp-admin/' . ltrim( $path, '/' );
}

function wp_json_encode( $value ) {
	return json_encode( $value );
}

function wp_remote_post( $url, $request ) {
	$GLOBALS['pgfw_captured_hubspot_request'] = array(
		'url'     => $url,
		'request' => $request,
	);

	return array(
		'response' => array(
			'code' => 200,
		),
		'body'     => '{"inlineMessage":"ok"}',
	);
}

function is_wp_error( $value ) {
	return false;
}

function wp_remote_retrieve_response_code( $response ) {
	return isset( $response['response']['code'] ) ? (int) $response['response']['code'] : 0;
}

function wp_remote_retrieve_body( $response ) {
	return isset( $response['body'] ) ? $response['body'] : '';
}

function esc_html__( $text, $domain = '' ) {
	return $text;
}

function sanitize_text_field( $text ) {
	return is_scalar( $text ) ? (string) $text : '';
}

function wp_strip_all_tags( $text ) {
	return strip_tags( $text );
}

require_once $plugin_file;

$reflection = new ReflectionClass( 'Pdf_Generator_For_Wp_Talk_To_Expert_Form' );
$instance   = $reflection->newInstanceWithoutConstructor();
$method     = $reflection->getMethod( 'wps_pgfw_submit_hubspot_form' );
$method->setAccessible( true );

$fields = array_filter(
	array(
		array(
			'name'  => 'firstname',
			'value' => 'Jane',
		),
		null,
		array(
			'name'  => 'email',
			'value' => 'jane@example.com',
		),
		null,
		array(
			'name'  => 'company',
			'value' => 'Demo Store',
		),
	)
);

$method->invoke( $instance, $fields );

$request_body = isset( $GLOBALS['pgfw_captured_hubspot_request']['request']['body'] ) ? $GLOBALS['pgfw_captured_hubspot_request']['request']['body'] : '';

if ( false === strpos( $request_body, '"fields":[' ) ) {
	fwrite( STDERR, "Expected HubSpot fields payload to encode as a JSON array.\nPayload: {$request_body}\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert HubSpot payload encodes fields as a JSON array.\n" );
