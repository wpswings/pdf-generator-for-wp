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
 * Return notices for the emails.
 *
 * @param string $color color of the message.
 * @param string $message message to show.
 * @return string
 */
function notice_template_for_email( $color, $message ) {
	$html = '<span style="' . $color . '">' . esc_html( $message ) . '</span>';
	return $html;
}

