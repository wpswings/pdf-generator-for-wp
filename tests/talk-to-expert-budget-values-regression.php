<?php
/**
 * Regression check for Talk to an Expert budget value mappings.
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
	"'budget'      => array(",
	"'type'        => 'select'",
	"'options'     => \$this->wps_pgfw_get_talk_to_expert_budget_options()",
	"'Please Select'",
	"'\$500 - \$1000'",
	"'\$1001 - \$5000'",
	"'\$5001 - \$10000' => '\$5001 - \$10000'",
	"'\$10001 - \$15000'",
	'foreach ( $field[\'options\'] as $option_value => $option_label )',
	'<option value="<?php echo esc_attr( $option_value ); ?>" <?php selected( $field_value, $option_value ); ?> <?php echo empty( $option_value ) ? \'disabled\' : \'\'; ?>>',
	'<?php echo esc_html( $option_label ); ?>',
	'$allowed_budgets   = array_keys( $this->wps_pgfw_get_talk_to_expert_budget_options() );',
	'$budget = isset( $form_data[\'budget\'] ) ? sanitize_text_field( $form_data[\'budget\'] ) : \'\';',
	'$budget = in_array( $budget, $allowed_budgets, true ) ? $budget : \'\';',
);

foreach ( $required_needles as $needle ) {
	if ( false === strpos( $source, $needle ) ) {
		fwrite( STDERR, "Missing Talk to an Expert budget contract:\n{$needle}\n" );
		exit( 1 );
	}
}

$reflection = new ReflectionClass( 'Pdf_Generator_For_Wp_Talk_To_Expert_Form' );
$instance   = $reflection->newInstanceWithoutConstructor();
$method     = $reflection->getMethod( 'wps_pgfw_get_talk_to_expert_budget_options' );
$method->setAccessible( true );

$options = $method->invoke( $instance );

$expected = array(
	''               => 'Please Select',
	'$500 - $1000'   => '$500 - $1000',
	'$1001 - $5000'  => '$1001 - $5000',
	'$5001 - $10000' => '$5001 - $10000',
	'$10001 - $15000' => '$10001 - $15000',
);

if ( $expected !== $options ) {
	fwrite( STDERR, "Unexpected Talk to an Expert budget mappings.\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert budget labels and submitted values are mapped correctly.\n" );
