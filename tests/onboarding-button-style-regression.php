<?php
/**
 * Regression check for onboarding modal action button consistency.
 */

$plugin_dir    = dirname( __DIR__ );
$template_file = $plugin_dir . '/onboarding/templates/pdf-generator-for-wp-onboarding-template.php';
$css_file      = $plugin_dir . '/onboarding/css/pdf-generator-for-wp-onboarding.css';

$files = array(
	$template_file,
	$css_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$template = file_get_contents( $template_file );
$css      = file_get_contents( $css_file );

$checks = array(
	array(
		'haystack' => $template,
		'needle'   => 'class="wps-pgfw-on-boarding-form-btn__wrapper wps-pgfw-onboarding-actions mdc-dialog__actions"',
	),
	array(
		'haystack' => $template,
		'needle'   => 'class="wps-pgfw-on-boarding-submit wps-on-boarding-verify mdc-button mdc-button--raised"',
	),
	array(
		'haystack' => $template,
		'needle'   => 'class="wps-pgfw-on-boarding-no_thanks mdc-button"',
	),
	array(
		'haystack' => $css,
		'needle'   => '.wps-pgfw-onboarding-actions .wps-pgfw-on-boarding-form-submit,',
	),
	array(
		'haystack' => $css,
		'needle'   => 'flex: 0 1 192px;',
	),
	array(
		'haystack' => $css,
		'needle'   => 'box-sizing: border-box;',
	),
	array(
		'haystack' => $css,
		'needle'   => 'white-space: nowrap;',
	),
	array(
		'haystack' => $css,
		'needle'   => 'padding: 0 46px !important;',
	),
	array(
		'haystack' => $css,
		'needle'   => 'padding: 0 24px !important;',
	),
);

$missing = array();

foreach ( $checks as $check ) {
	if ( false === strpos( $check['haystack'], $check['needle'] ) ) {
		$missing[] = $check['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Missing onboarding button style contract:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Onboarding button style contract present.\n" );
