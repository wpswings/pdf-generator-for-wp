<?php
/**
 * Regression check for Talk to an Expert thank-you modal flow.
 */

$plugin_dir    = dirname( __DIR__ );
$dashboard_file = $plugin_dir . '/admin/partials/pdf-generator-for-wp-admin-dashboard.php';
$include_file  = $plugin_dir . '/includes/class-pdf-generator-for-wp-talk-to-expert-form.php';

$files = array(
	$dashboard_file,
	$include_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$dashboard = file_get_contents( $dashboard_file );
$include = file_get_contents( $include_file );

$checks = array(
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
		'needle'   => 'Our team will review the details and contact you with the right next step.',
	),
	array(
		'haystack' => $include,
		'needle'   => 'Thank you for submitting your request.',
	),
	array(
		'haystack' => $include,
		'needle'   => "'message' => \$result['message']",
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if (
	false !== strpos( $dashboard, "'talk_to_expert_thank_you' === \$pgfw_view" ) ||
	false !== strpos( $dashboard, 'pdf-generator-for-wp-admin-thank-you.php' ) ||
	false !== strpos( $include, 'wps_pgfw_get_talk_to_expert_thank_you_url' ) ||
	false !== strpos( $include, "'redirect_url' => \$this->wps_pgfw_get_talk_to_expert_thank_you_url( \$result['message'] )" )
) {
	$missing[] = 'Legacy thank-you page contract still present';
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing Talk to an Expert thank-you modal contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Talk to an Expert thank-you modal contract present.\n" );
