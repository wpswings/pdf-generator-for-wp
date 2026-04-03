<?php
/**
 * Overview tab content (redesigned dashboard).
 *
 * @package Pdf_Generator_For_Wp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $pgfw_wps_pgfw_obj;

$plugin_title = strtoupper( str_replace( '-', ' ', apply_filters( 'wps_pgfw_update_plugin_name_dashboard', $pgfw_wps_pgfw_obj->pgfw_get_plugin_name() ) ) );

$docs_url       = 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation';
$video_url      = 'https://www.youtube.com/watch?v=RljECeP3JJk';
$faq_url        = 'https://wpswings.com/submit-query/?utm_source=wpswings-pdf-support&utm_medium=pdf-org-backend&utm_campaign=submit-query';
$contact_url    = 'https://wpswings.com/contact-us/';
$services_url   = 'https://wpswings.com/wordpress-woocommerce-solutions/?utm_source=wpswings-pdf-service&utm_medium=pdf-org-backend&utm_campaign=service-page';
$upgrade_url    = 'https://wpswings.com/product/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-pro&utm_medium=pdf-org-backend&utm_campaign=go-pro';
$demo_url       = 'https://demo.wpswings.com/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-demo&utm_medium=wpswings-org-backend&utm_campaign=View-demo';
$support_email  = 'support@wpswings.com';

$feature_cards = array(
	array(
		'icon'  => 'dashicons-yes-alt',
		'title' => __( 'Top three features of this plugin?', 'pdf-generator-for-wp' ),
		'body'  => __( 'Detailed Report View With Churn Rate and ARR', 'pdf-generator-for-wp' ),
	),
	array(
		'icon'  => 'dashicons-chart-area',
		'title' => __( 'Top three features of this plugin?', 'pdf-generator-for-wp' ),
		'body'  => __( 'Detailed Report View With Churn Rate and ARR', 'pdf-generator-for-wp' ),
	),
	array(
		'icon'  => 'dashicons-database-view',
		'title' => __( 'Top three features of this plugin?', 'pdf-generator-for-wp' ),
		'body'  => __( 'Detailed Report View With Churn Rate and ARR', 'pdf-generator-for-wp' ),
	),
);
?>

<div class="pgfw-overview">
	<div class="pgfw-hero">
		<div class="pgfw-hero__illustration">
			<img src="<?php echo esc_url( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/wps-pdf-icon.png' ); ?>" alt="PDF Generator" />
		</div>
		<div class="pgfw-hero__text">
			<p class="pgfw-badge">v<?php echo esc_html( PDF_GENERATOR_FOR_WP_VERSION ); ?></p>
			<h1><?php esc_html_e( 'Product Recommendation using AI', 'pdf-generator-for-wp' ); ?></h1>
			<p class="pgfw-lead"><?php esc_html_e( 'Subscriptions for WooCommerce Pro enables seamless recurring payments, flexible subscription plans, and effortless management, boosting customer retention and revenue. Perfect for businesses seeking to optimize their subscription-based WooCommerce store.', 'pdf-generator-for-wp' ); ?></p>
		</div>
	</div>

	<div class="pgfw-card pgfw-feature-callout">
		<div class="pgfw-feature-heading">
			<span class="pgfw-line"></span>
			<h2><?php esc_html_e( 'Top Features of this plugin', 'pdf-generator-for-wp' ); ?></h2>
			<span class="pgfw-line"></span>
		</div>
		<div class="pgfw-feature-grid">
			<?php foreach ( $feature_cards as $card ) : ?>
				<article class="pgfw-feature-item">
					<span class="pgfw-feature-icon dashicons <?php echo esc_attr( $card['icon'] ); ?>" aria-hidden="true"></span>
					<h3><?php echo esc_html( $card['title'] ); ?></h3>
					<p><?php echo esc_html( $card['body'] ); ?></p>
				</article>
			<?php endforeach; ?>
		</div>
	</div>

	<div class="pgfw-support-strip">
		<div class="pgfw-support-text">
			<strong><?php esc_html_e( 'Facing issues?', 'pdf-generator-for-wp' ); ?></strong>
			<span><?php esc_html_e( 'We are ready to resolve your problems.', 'pdf-generator-for-wp' ); ?></span>
		</div>
		<div class="pgfw-support-actions">
			<a class="pgfw-btn pgfw-btn-dark" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Hire us!', 'pdf-generator-for-wp' ); ?></a>
			<a class="pgfw-btn" href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Demo', 'pdf-generator-for-wp' ); ?></a>
			<a class="pgfw-btn" href="<?php echo esc_url( $faq_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'pdf-generator-for-wp' ); ?></a>
		</div>
	</div>
</div>
