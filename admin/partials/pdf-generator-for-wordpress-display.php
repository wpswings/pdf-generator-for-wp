<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
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
global $pgfw_mwb_pgfw_obj;
$pgfw_settings_display_fields = apply_filters( 'pgfw_display_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="pgfw-section-wrap">
	<?php
		$pgfw_template_html = $pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_settings_display_fields );
		echo esc_html( $pgfw_template_html );
	?>
</div>
