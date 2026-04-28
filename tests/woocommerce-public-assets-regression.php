<?php
/**
 * Regression check for WooCommerce archive asset loading.
 *
 * Shop/category/tag pages also render the PDF icon, so the public asset guard
 * must explicitly allow WooCommerce archive contexts.
 */

$public_class_file = dirname( __DIR__ ) . '/public/class-pdf-generator-for-wp-public.php';

if ( ! file_exists( $public_class_file ) ) {
	fwrite( STDERR, "Public class file not found: {$public_class_file}\n" );
	exit( 1 );
}

$public_class = file_get_contents( $public_class_file );

if ( false === $public_class ) {
	fwrite( STDERR, "Unable to read public class file: {$public_class_file}\n" );
	exit( 1 );
}

$required_snippets = array(
	'if ( function_exists( \'is_shop\' ) && is_shop() ) {',
	'if ( function_exists( \'is_product_taxonomy\' ) && is_product_taxonomy() ) {',
	'if ( function_exists( \'is_product_category\' ) && is_product_category() ) {',
	'if ( function_exists( \'is_product_tag\' ) && is_product_tag() ) {',
);

$missing = array();

foreach ( $required_snippets as $snippet ) {
	if ( false === strpos( $public_class, $snippet ) ) {
		$missing[] = $snippet;
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing WooCommerce public asset loading contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "WooCommerce public asset loading contract present.\n" );
