<?php
/**
 * Regression check for Talk to an Expert annual revenue sourcing.
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

function sanitize_text_field( $text ) {
	return is_scalar( $text ) ? (string) $text : '';
}

function wc_get_is_paid_statuses() {
	return array( 'processing', 'completed' );
}

class PGFW_Mock_Order {
	private $total;

	public function __construct( $total ) {
		$this->total = $total;
	}

	public function get_total() {
		return $this->total;
	}
}

class PGFW_WPDB_Mock {
	public $prefix = 'wp_';
	public $table_exists = true;
	public $revenue = '4321.5';

	public function prepare( $query, ...$args ) {
		if ( 1 === count( $args ) && is_array( $args[0] ) ) {
			$args = $args[0];
		}

		return $query . ' -- ' . wp_json_encode( $args );
	}

	public function get_var( $query ) {
		if ( false !== strpos( $query, 'SHOW TABLES LIKE' ) ) {
			return $this->table_exists ? $this->prefix . 'wc_order_stats' : null;
		}

		if ( false !== strpos( $query, 'SUM(total_sales)' ) ) {
			return $this->revenue;
		}

		return null;
	}
}

function wc_get_orders( $args ) {
	return isset( $GLOBALS['pgfw_mock_orders'] ) ? $GLOBALS['pgfw_mock_orders'] : array();
}

function wp_json_encode( $value ) {
	return json_encode( $value );
}

require_once $plugin_file;

$source = file_get_contents( $plugin_file );

if ( false === strpos( $source, "wps_pgfw_prepare_hubspot_field( 'annualrevenue', \$this->wps_pgfw_get_store_annual_revenue() )" ) ) {
	fwrite( STDERR, "Expected annualrevenue HubSpot field to use the store annual revenue helper.\n" );
	exit( 1 );
}

$reflection = new ReflectionClass( 'Pdf_Generator_For_Wp_Talk_To_Expert_Form' );
$instance   = $reflection->newInstanceWithoutConstructor();
$method     = $reflection->getMethod( 'wps_pgfw_get_store_annual_revenue' );
$method->setAccessible( true );

global $wpdb;
$wpdb = new PGFW_WPDB_Mock();

$stats_revenue = $method->invoke( $instance );

if ( '4321.50' !== $stats_revenue ) {
	fwrite( STDERR, "Expected stats-backed annual revenue to be 4321.50, got {$stats_revenue}.\n" );
	exit( 1 );
}

$wpdb->table_exists         = false;
$GLOBALS['pgfw_mock_orders'] = array(
	new PGFW_Mock_Order( '100.25' ),
	new PGFW_Mock_Order( '50.50' ),
);

$fallback_revenue = $method->invoke( $instance );

if ( '150.75' !== $fallback_revenue ) {
	fwrite( STDERR, "Expected fallback annual revenue to be 150.75, got {$fallback_revenue}.\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert annual revenue uses last-12-month paid order totals.\n" );
