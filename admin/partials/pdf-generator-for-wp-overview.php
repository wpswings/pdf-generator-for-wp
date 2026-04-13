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

$plugin_title = ucwords( str_replace( '-', ' ', apply_filters( 'wps_pgfw_update_plugin_name_dashboard', $pgfw_wps_pgfw_obj->pgfw_get_plugin_name() ) ) );
$plugin_badge = 'PDF';

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
		'image' => 'includedetails.jpg',
		'alt'   => __( 'Include details image', 'pdf-generator-for-wp' ),
		'title' => __( 'Include Details', 'pdf-generator-for-wp' ),
		'body'  => __( 'The general features in the PDF generator plugin allow you to set the name for your generated PDF. Also, the general settings in the PDF generator allow you to choose if you want to display the author name, date of publication, and different download options.', 'pdf-generator-for-wp' ),
	),
	array(
		'image' => 'seticonalignment.jpg',
		'alt'   => __( 'Set icon alignment image', 'pdf-generator-for-wp' ),
		'title' => __( 'Set Icon Alignment', 'pdf-generator-for-wp' ),
		'body'  => __( 'By using the display settings, the PDF generator plugin provides flexibility to choose if users and guest users will be able to see the icon. It has the features to let you decide the alignment of your icon in the WordPress site and if you want to send the PDF to the user’s e-mail.', 'pdf-generator-for-wp' ),
	),
	array(
		'image' => 'customizepdf.jpg',
		'alt'   => __( 'Customize PDF image', 'pdf-generator-for-wp' ),
		'title' => __( 'Customize Your PDF', 'pdf-generator-for-wp' ),
		'body'  => __( 'The plugin allows you for the individual customization of the header, footer, and PDF body. You can set your desired margins, watermarks, custom logo, title, tagline, and much more. An exclusive feature in the PDF generator is compatibility with Arabic languages and RTL support.', 'pdf-generator-for-wp' ),
	),
	array(
		'image' => 'releventplacement.jpg',
		'alt'   => __( 'Relevant placement image', 'pdf-generator-for-wp' ),
		'title' => __( 'Relevant Placement', 'pdf-generator-for-wp' ),
		'body'  => __( 'The advanced settings of the PDF generator plugin allow you to place the PDF icon on the relevant pages only. So, you can select the relevant post types of which you want to generate the PDF. PDF generators let you generate PDF files for products, pages, or posts.', 'pdf-generator-for-wp' ),
	),
	array(
		'image' => 'metafields.jpg',
		'alt'   => __( 'Meta fields image', 'pdf-generator-for-wp' ),
		'title' => __( 'Select Appropriate Metafields', 'pdf-generator-for-wp' ),
		'body'  => __( 'Depending upon the purpose of your PDF file, you can select the appropriate meta fields. The plugin lets you select the meta fields specifically for products, pages, and posts respectively. So, you can edit the settings as per your target audience, be it a potential client or a new supplier.', 'pdf-generator-for-wp' ),
	),
	array(
		'image' => 'uploadyourpdf.jpg',
		'alt'   => __( 'Upload PDF image', 'pdf-generator-for-wp' ),
		'title' => __( 'Upload Your Own PDF', 'pdf-generator-for-wp' ),
		'body'  => __( 'Another chic feature in the plugin to serve your purpose of generating PDF files is allowing you to upload your own PDF files. This lets you sell your product, services or content in a manner you have planned in advance.', 'pdf-generator-for-wp' ),
	),
);
?>

<div class="pgfw-overview">
	<section class="pgfw-overview-v3__hero">
		<div class="pgfw-overview-v3__badge"><?php echo esc_html( $plugin_badge ); ?></div>
		<p class="pgfw-overview-v3__eyebrow"><?php esc_html_e( 'Overview', 'pdf-generator-for-wp' ); ?></p>
		<h1><?php echo esc_html( $plugin_title ); ?></h1>
		<p class="pgfw-overview-v3__lead"><?php esc_html_e( 'Generate branded PDFs for posts, pages, and products with configurable layouts, download controls, uploads, and customization options from one dashboard.', 'pdf-generator-for-wp' ); ?></p>
	</section>

	<div class="pgfw-card pgfw-feature-callout">
		<div class="pgfw-feature-heading">
			<span class="pgfw-line"></span>
			<h2><?php esc_html_e( 'Top Features of this plugin', 'pdf-generator-for-wp' ); ?></h2>
			<span class="pgfw-line"></span>
		</div>
		<div class="pgfw-feature-grid">
			<?php foreach ( $feature_cards as $card ) : ?>
				<article class="pgfw-feature-item">
					<span class="pgfw-feature-icon" aria-hidden="true">
						<img src="<?php echo esc_url( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/' . $card['image'] ); ?>" alt="<?php echo esc_attr( $card['alt'] ); ?>" />
					</span>
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
			<a class="pgfw-btn pgfw-btn-dark" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact us!', 'pdf-generator-for-wp' ); ?></a>
			<a class="pgfw-btn" href="<?php echo esc_url( $demo_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Demo', 'pdf-generator-for-wp' ); ?></a>
			<a class="pgfw-btn" href="<?php echo esc_url( $faq_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'pdf-generator-for-wp' ); ?></a>
		</div>
	</div>
</div>
