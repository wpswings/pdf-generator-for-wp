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
$pgfw_meta_settings = apply_filters( 'pgfw_meta_fields_settings_array', array() );
?>
<!--  template file for admin settings. -->
<div class="pgfw-section-wrap">
	<?php
		$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_meta_settings );
	?>
</div>
