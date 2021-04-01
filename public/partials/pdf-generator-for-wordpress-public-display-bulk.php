<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/public/partials
 */

?>
<div class="mwb_pgw-button_wrapper">
	<div class="mwb_pgw-button">
		<img src="<?php echo esc_url( PDF_GENERATOR_FOR_WORDPRESS_DIR_URL ); ?>admin/src/images/Download_Full_PDF.svg" title="<?php esc_html_e( 'Bulk PDF', 'pdf-generator-for-wordpress' ); ?>" alt="<?php esc_html_e( 'pdf bulk image', 'pdf-generator-for-wordpress' ); ?>">
	</div>
	<div id="pgfw-download-zip-parent"></div>
	<div class="mwb_pgw-button_content">Loreum ipsum dollar set is the basic for this</div>
</div>
