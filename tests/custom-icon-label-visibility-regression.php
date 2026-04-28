<?php
/**
 * Regression check for custom-icon label visibility and template selection.
 */

$plugin_dir            = dirname( __DIR__ );
$global_functions_file = $plugin_dir . '/includes/pdf-generator-for-wp-global-functions.php';
$action_template_file  = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-icon-action-template.php';
$button_template_file  = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php';
$modal_template_file   = $plugin_dir . '/public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php';
$public_css_file       = $plugin_dir . '/public/src/scss/pdf-generator-for-wp-public.css';
$admin_settings_file   = $plugin_dir . '/admin/class-pdf-generator-for-wp-admin.php';

$files = array(
	$global_functions_file,
	$action_template_file,
	$button_template_file,
	$modal_template_file,
	$public_css_file,
	$admin_settings_file,
);

foreach ( $files as $file ) {
	if ( ! file_exists( $file ) ) {
		fwrite( STDERR, "File not found: {$file}\n" );
		exit( 1 );
	}
}

$global_functions = file_get_contents( $global_functions_file );
$action_template  = file_get_contents( $action_template_file );
$button_template  = file_get_contents( $button_template_file );
$modal_template   = file_get_contents( $modal_template_file );
$public_css       = file_get_contents( $public_css_file );
$admin_settings   = file_get_contents( $admin_settings_file );

$required_snippets = array(
	array(
		'haystack' => $global_functions,
		'needle'   => "'pgfw_pdf_icon_display_template'",
	),
	array(
		'haystack' => $global_functions,
		'needle'   => 'function pgfw_get_single_action_label',
	),
	array(
		'haystack' => $global_functions,
		'needle'   => "if ( '' !== \$single_icon_url ) {",
	),
	array(
		'haystack' => $global_functions,
		'needle'   => 'function pgfw_should_render_single_action_as_uploaded_icon',
	),
	array(
		'haystack' => $action_template,
		'needle'   => "'label'            => null",
	),
	array(
		'haystack' => $action_template,
		'needle'   => "'image_only'       => false",
	),
	array(
		'haystack' => $action_template,
		'needle'   => "if ( ! empty( \$args['image_only'] ) ) {",
	),
	array(
		'haystack' => $action_template,
		'needle'   => "'pgfw-single-pdf-download-button--image-only'",
	),
	array(
		'haystack' => $action_template,
		'needle'   => "if ( '' !== \$label ) {",
	),
	array(
		'haystack' => $button_template,
		'needle'   => 'pgfw_get_single_action_label( $settings )',
	),
	array(
		'haystack' => $button_template,
		'needle'   => "'image_only'       => pgfw_should_render_single_action_as_uploaded_icon( \$settings )",
	),
	array(
		'haystack' => $modal_template,
		'needle'   => 'pgfw_get_single_action_label( $settings )',
	),
	array(
		'haystack' => $modal_template,
		'needle'   => "'image_only'       => pgfw_should_render_single_action_as_uploaded_icon( \$settings )",
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => "'id'          => 'wps_wpg_single_pdf_icon_name'",
	),
	array(
		'haystack' => $public_css,
		'needle'   => '.pgfw-single-pdf-download-button--image-only',
	),
	array(
		'haystack' => $public_css,
		'needle'   => 'background: transparent !important;',
	),
	array(
		'haystack' => $public_css,
		'needle'   => 'border-radius: 0 !important;',
	),
);

$forbidden_snippets = array(
	array(
		'haystack' => $public_css,
		'needle'   => '.pgfw-single-pdf-download-button__label:empty::before',
	),
	array(
		'haystack' => $admin_settings,
		'needle'   => "'value'       => 'single pdf name'",
	),
	array(
		'haystack' => $action_template,
		'needle'   => "\$label = '' !== \$args['label'] ? \$args['label'] : __( 'Download PDF', 'pdf-generator-for-wp' );",
	),
);

$missing = array();

foreach ( $required_snippets as $snippet ) {
	if ( false === strpos( $snippet['haystack'], $snippet['needle'] ) ) {
		$missing[] = 'missing: ' . $snippet['needle'];
	}
}

foreach ( $forbidden_snippets as $snippet ) {
	if ( false !== strpos( $snippet['haystack'], $snippet['needle'] ) ) {
		$missing[] = 'forbidden: ' . $snippet['needle'];
	}
}

if ( ! empty( $missing ) ) {
	fwrite( STDERR, "Custom icon label visibility contract failed:\n- " . implode( "\n- ", $missing ) . "\n" );
	exit( 1 );
}

fwrite( STDOUT, "Custom icon label visibility contract present.\n" );
