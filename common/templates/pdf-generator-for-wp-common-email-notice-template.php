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

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}
?>
<span style="<?php echo esc_attr( $color ); ?>"><?php echo esc_html( $message ); ?></span>

