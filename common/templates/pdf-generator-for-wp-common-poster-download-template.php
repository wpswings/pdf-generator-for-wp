<?php
/**
 * Provide a common view for the plugin
 *
 * This file is used to markup the common aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/common/partials
 */

/**
 * Return download button for poster shortcodes.
 *
 * @param string $poster_image_url url of the poster.
 * @return string
 */
function pgfw_poster_download_button_for_shortcode( $poster_image_url ) {
	$html = '<div id="pgfw-poster-dowload-url-link">
				<a href="' . esc_url( $poster_image_url ) . '" download title="' . esc_html__( 'Download Poster', 'pdf-generator-for-wp' ) . '"><img src="' . esc_attr( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/postericon.svg" alt="' . esc_attr__( 'Download Poster', 'pdf-generator-for-wp' ) . '"/></a>
			</div>';
	return $html;
}
