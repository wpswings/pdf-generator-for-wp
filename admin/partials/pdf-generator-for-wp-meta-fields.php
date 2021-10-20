<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pgfw_mwb_pgfw_obj;
$pgfw_meta_settings = apply_filters( 'pgfw_meta_fields_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-pgfw-gen-section-form">
	<div class="pgfw-section-wrap">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'pgfw_nonce_field' );
		$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_meta_settings );
		do_action( 'mwb_pgfw_below_meta_fields_setting_form' );
		?>
	</div>
</form>
