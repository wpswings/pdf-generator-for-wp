<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for overview tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
do_action( 'mwb_pgfw_pro_overview_content' );
?>
<div class="mwb-overview__wrapper">
	<?php do_action( 'pgfw_overview_content_top' ); ?>
	<div class="mwb-overview__banner">
		<img src="<?php echo esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ); ?>admin/src/images/pdfgeneratorbannerimage.png" alt="Overview banner image">
	</div>
	<div class="mwb-overview__content">
		<div class="mwb-overview__content-description">
			<h2><?php echo esc_html_e( 'What is PDF Generator?', 'pdf-generator-for-wp' ); ?></h2>
			<p>
				<?php
				esc_html_e(
					'PDF Generator allows you to share the content on your website in more than one way. You can convert your posts, product pages, and blogs into PDF files to make them available offline. There are various utilities allowing users and admins to generate PDF files of desired content. These range from dispersing your brand name through watermarks, customized headers and footers, and much more. 
					PDF generator is one such plugin that will let you or your users generate PDF with personalized settings, also allowing access to emails of your leads and potential customers. The PDF Generator plugin is now upgraded to equip you with better control of the feature on your WordPress site.',
					'pdf-generator-for-wp'
				);
				?>
			</p>
			<h3><?php esc_html_e( 'With our PDF Generator for WordPress plugin, you can:', 'pdf-generator-for-wp' ); ?></h3>
			<div class="mwb-overview__features-wrapper">
				<ul class="mwb-overview__features">
					<li><?php esc_html_e( 'Create PDF files to build your company portfolio as per your industry best practices.', 'pdf-generator-for-wp' ); ?></li>
					<li><?php esc_html_e( 'Allow users to download products and content from your website.', 'pdf-generator-for-wp' ); ?></li>
					<li><?php esc_html_e( 'Allow users to share the PDF files on different channels or access them offline.', 'pdf-generator-for-wp' ); ?></li>
					<li><?php esc_html_e( 'Upload PDF files in advance to let your customers download them.', 'pdf-generator-for-wp' ); ?></li>
					<li><?php esc_html_e( 'Request the users’ email id in exchange for information.', 'pdf-generator-for-wp' ); ?></li>
					<li><?php esc_html_e( 'Disperse your branding effectively with the useful information you have to sell.', 'pdf-generator-for-wp' ); ?></li>
				</ul>
				<?php do_action( 'pgfw_add_demo_video_at_overview_form' ); ?>
			</div>
		</div>
		<h2> <?php esc_html_e( 'The Free Plugin Benefits', 'pdf-generator-for-wp' ); ?></h2>
		<div class="mwb-overview__keywords">
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/includedetails.jpg' ); ?>" alt="Advanced-report image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php echo esc_html_e( 'Include Details', 'pdf-generator-for-wp' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'The general features in the PDF generator plugin allow you to set the name for your generated PDF. Also, the general settings in the PDF generator allow you to choose if you want to display the author name, date of publication, and different download options.',
								'pdf-generator-for-wp'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/seticonalignment.jpg' ); ?>" alt="Workflow image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Set Icon Alignment', 'pdf-generator-for-wp' ); ?></h3>
						<p class="mwb-overview__keywords-description"><?php esc_html_e( 'By using the display settings, the PDF generator plugin provides flexibility to choose if users and guest users will be able to see the icon. It has the features to let you decide the alignment of your icon in the WordPress site and if you want to send the PDF to the user’s e-mail.', 'pdf-generator-for-wp' ); ?></p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/customizepdf.jpg' ); ?>" alt="Variable product image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Customize Your PDF', 'pdf-generator-for-wp' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'The plugin allows you for the individual customization of the header, footer, and PDF body. You can set your desired margins, watermarks, custom logo, title, tagline, and much more. An exclusive feature in the PDF generator is compatibility with Arabic languages and RTL support.',
								'pdf-generator-for-wp'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/releventplacement.jpg' ); ?>" alt="List-of-abandoned-users image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Relevant Placement', 'pdf-generator-for-wp' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'The advanced settings of the PDF generator plugin allow you to place the PDF icon on the relevant pages only. So, you can select the relevant post types of which you want to generate the PDF. PDF generators let you generate PDF files for products, pages, or posts.',
								'pdf-generator-for-wp'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card mwb-card-support">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/metafields.jpg' ); ?>" alt="Support image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Select Appropriate Metafields', 'pdf-generator-for-wp' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'Depending upon the purpose of your PDF file, you can select the appropriate meta fields. The plugin lets you select the meta fields specifically for products, pages, and posts respectively. So, you can edit the settings as per your target audience, be it a potential client or a new supplier. ',
								'pdf-generator-for-wp'
							);
							?>
						</p>
					</div>
				</div>
			</div>
			<div class="mwb-overview__keywords-item">
				<div class="mwb-overview__keywords-card mwb-card-support">
					<div class="mwb-overview__keywords-image">
						<img src="<?php echo esc_html( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/uploadyourpdf.jpg' ); ?>" alt="Support image">
					</div>
					<div class="mwb-overview__keywords-text">
						<h3 class="mwb-overview__keywords-heading"><?php esc_html_e( 'Upload Your Own PDF', 'pdf-generator-for-wp' ); ?></h3>
						<p class="mwb-overview__keywords-description">
							<?php
							esc_html_e(
								'Another chic feature in the plugin to serve your purpose of generating PDF files is allowing you to upload your own PDF files. This lets you sell your product, services or content in a manner you have planned in advance.',
								'pdf-generator-for-wp'
							);
							?>
						</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php do_action( 'pgfw_overview_content_bottom' ); ?>
</div>
