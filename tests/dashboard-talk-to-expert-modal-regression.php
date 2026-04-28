<?php
/**
 * Regression check for the dashboard Talk to an Expert modal.
 */

$plugin_dir       = dirname( __DIR__ );
$dashboard_file   = $plugin_dir . '/admin/partials/pdf-generator-for-wp-admin-dashboard.php';
$admin_js_file    = $plugin_dir . '/admin/src/js/pdf-generator-for-wp-admin.js';
$admin_css_file   = $plugin_dir . '/admin/src/css/pdf-generator-for-wp-admin-modern.css';
$include_file     = $plugin_dir . '/includes/class-pdf-generator-for-wp-talk-to-expert-form.php';
$bootstrap_file   = $plugin_dir . '/includes/class-pdf-generator-for-wp.php';

$files = array(
	$dashboard_file,
	$admin_js_file,
	$admin_css_file,
	$include_file,
	$bootstrap_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$dashboard = file_get_contents( $dashboard_file );
$admin_js  = file_get_contents( $admin_js_file );
$admin_css = file_get_contents( $admin_css_file );
$include   = file_get_contents( $include_file );
$bootstrap = file_get_contents( $bootstrap_file );

$checks = array(
	array(
		'haystack' => $dashboard,
		'needle'   => 'class="pgfw-services-card__cta" href="#"',
	),
	array(
		'haystack' => $dashboard,
		'needle'   => 'data-pgfw-open-expert-modal',
	),
	array(
		'haystack' => $bootstrap,
		'needle'   => "includes/class-pdf-generator-for-wp-talk-to-expert-form.php",
	),
	array(
		'haystack' => $include,
		'needle'   => 'class Pdf_Generator_For_Wp_Talk_To_Expert_Form',
	),
	array(
		'haystack' => $include,
		'needle'   => "add_action( 'wp_ajax_wps_pgfw_submit_talk_to_expert'",
	),
	array(
		'haystack' => $include,
		'needle'   => "private static \$wps_pgfw_talk_to_expert_form_id = '91bfc24e-c1a7-4858-878a-9f2fb4728620';",
	),
	array(
		'haystack' => $include,
		'needle'   => "private static \$wps_pgfw_talk_to_expert_portal_id = '25444144';",
	),
	array(
		'haystack' => $include,
		'needle'   => 'class="pgfw-expert-modal"',
	),
	array(
		'haystack' => $include,
		'needle'   => 'data-pgfw-expert-form="true"',
	),
	array(
		'haystack' => $include,
		'needle'   => 'data-pgfw-expert-state="true"',
	),
	array(
		'haystack' => $include,
		'needle'   => 'data-pgfw-expert-thank-you="true"',
	),
	array(
		'haystack' => $include,
		'needle'   => 'data-pgfw-expert-thank-you-message="true"',
	),
	array(
		'haystack' => $include,
		'needle'   => "What services do you need help with?",
	),
	array(
		'haystack' => $include,
		'needle'   => "'budget'      => array(",
	),
	array(
		'haystack' => $include,
		'needle'   => "'type'        => 'select'",
	),
	array(
		'haystack' => $include,
		'needle'   => "'Please Select'",
	),
	array(
		'haystack' => $include,
		'needle'   => "'\$500 - \$1000'",
	),
	array(
		'haystack' => $include,
		'needle'   => "'\$1001 - \$5000'",
	),
	array(
		'haystack' => $include,
		'needle'   => "'\$5001 - \$10000'",
	),
	array(
		'haystack' => $include,
		'needle'   => "'\$10001 - \$15000'",
	),
	array(
		'haystack' => $include,
		'needle'   => "'type'        => 'checkbox_group'",
	),
	array(
		'haystack' => $include,
		'needle'   => "'what_services_do_you_need_help_with'",
	),
	array(
		'haystack' => $include,
		'needle'   => "SEO services",
	),
	array(
		'haystack' => $include,
		'needle'   => "Google Ads Setup and GA4 setup",
	),
	array(
		'haystack' => $include,
		'needle'   => "Speed Optimization",
	),
	array(
		'haystack' => $include,
		'needle'   => "WooCommerce Development Services",
	),
	array(
		'haystack' => $include,
		'needle'   => "check_ajax_referer( 'wps_pgfw_talk_to_expert_nonce', 'nonce' );",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'what_services_do_you_need_help_with'",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'budget'",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'currency'",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'org_plugin_name'",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'company'",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'website'",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'country'",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$this->wps_pgfw_prepare_hubspot_field( 'annualrevenue'",
	),
	array(
		'haystack' => $include,
		'needle'   => "private static \$wps_pgfw_plugin_name_label = 'PDF Generator For Wp';",
	),
	array(
		'haystack' => $include,
		'needle'   => 'get_woocommerce_currency()',
	),
	array(
		'haystack' => $include,
		'needle'   => "get_option( 'woocommerce_default_country', '' )",
	),
	array(
		'haystack' => $include,
		'needle'   => "new WC_Countries()",
	),
	array(
		'haystack' => $include,
		'needle'   => "'firstname' => array(",
	),
	array(
		'haystack' => $include,
		'needle'   => "'lastname'  => array(",
	),
	array(
		'haystack' => $include,
		'needle'   => "id=\"<?php echo esc_attr( \$field_id ); ?>\"",
	),
	array(
		'haystack' => $include,
		'needle'   => "name=\"<?php echo esc_attr( \$field_key ); ?>[]\"",
	),
	array(
		'haystack' => $include,
		'needle'   => "\$field_id = \$field_key;",
	),
	array(
		'haystack' => $include,
		'needle'   => "'firstname' => isset( \$form_data['firstname'] )",
	),
	array(
		'haystack' => $include,
		'needle'   => "'lastname'  => isset( \$form_data['lastname'] )",
	),
	array(
		'haystack' => $include,
		'needle'   => "isset( \$form_data['what_services_do_you_need_help_with'] )",
	),
	array(
		'haystack' => $include,
		'needle'   => "'budget'     => isset( \$form_data['budget'] )",
	),
	array(
		'haystack' => $include,
		'needle'   => "implode( ';', \$field_value )",
	),
	array(
		'haystack' => $include,
		'needle'   => "wp_remote_post( \$url, \$request );",
	),
	array(
		'haystack' => $admin_js,
		'needle'   => '[data-pgfw-open-expert-modal]',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => '.pgfw-expert-modal',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'Escape',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'wps_pgfw_submit_talk_to_expert',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'talk_to_expert_nonce',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'FormData',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'const normalizedKey = key.replace( /\\[\\]$/, \'\' );',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'payload[ normalizedKey ].push( value );',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'data-pgfw-expert-form',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'showExpertThankYou',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'expertFormPanel.hidden = true;',
	),
	array(
		'haystack' => $admin_js,
		'needle'   => 'expertThankYou.hidden = false;',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-modal{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-modal__dialog{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-form{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-form__status{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-form__checkbox-group{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-form__checkbox-label{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-form__control--select{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-expert-thank-you{',
	),
	array(
		'haystack' => $admin_css,
		'needle'   => '.pgfw-thank-you-card--modal{',
	),
);

$forbidden = array(
	"'company'    => array(",
	"'website'    => array(",
	"'first_name' => array(",
	"'last_name'  => array(",
	"'services_required'",
	"'pgfw-expert-' . \$field_key",
	'Selected services: ',
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

foreach ( $forbidden as $needle ) {
	if ( false !== strpos( $include, $needle ) ) {
		$missing[] = 'Forbidden contract still present: ' . $needle;
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing Talk to an Expert modal contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert modal contract present.\n" );
