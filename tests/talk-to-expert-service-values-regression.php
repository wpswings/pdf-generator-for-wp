<?php
/**
 * Regression check for Talk to an Expert service value mappings.
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

require_once $plugin_file;

$source = file_get_contents( $plugin_file );

$required_needles = array(
	"'seo_services'",
	"'google_ads_setup_and_ga4_setup'",
	"'speed_optimization'",
	"'woocommerce_development_services'",
	"esc_html__( 'SEO services', 'pdf-generator-for-wp' )",
	"esc_html__( 'Google Ads Setup and GA4 setup', 'pdf-generator-for-wp' )",
	"esc_html__( 'Speed Optimization', 'pdf-generator-for-wp' )",
	"esc_html__( 'WooCommerce Development Services', 'pdf-generator-for-wp' )",
	'foreach ( $field[\'options\'] as $option_value => $option_label )',
	'value="<?php echo esc_attr( $option_value ); ?>"',
	'<span><?php echo esc_html( $option_label ); ?></span>',
	'$allowed_services  = array_keys( $this->wps_pgfw_get_talk_to_expert_service_options() );',
);

foreach ( $required_needles as $needle ) {
	if ( false === strpos( $source, $needle ) ) {
		fwrite( STDERR, "Missing Talk to an Expert service mapping contract:\n{$needle}\n" );
		exit( 1 );
	}
}

$reflection = new ReflectionClass( 'Pdf_Generator_For_Wp_Talk_To_Expert_Form' );
$instance   = $reflection->newInstanceWithoutConstructor();
$method     = $reflection->getMethod( 'wps_pgfw_get_talk_to_expert_service_options' );
$method->setAccessible( true );

$options = $method->invoke( $instance );

$expected = array(
	'seo_services'                     => 'SEO services',
	'google_ads_setup_and_ga4_setup'  => 'Google Ads Setup and GA4 setup',
	'speed_optimization'              => 'Speed Optimization',
	'woocommerce_development_services' => 'WooCommerce Development Services',
);

if ( $expected !== $options ) {
	fwrite( STDERR, "Unexpected Talk to an Expert service mappings.\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert service labels and submitted values are mapped correctly.\n" );
