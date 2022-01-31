<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pgfw_mwb_pgfw_obj;
$pgfw_advanced_settings = apply_filters( 'pgfw_advanced_settings_array', array() );
?>
<!--  advanced file for admin settings. -->
<form action="" method="POST" class="mwb-pgfw-gen-section-form" enctype="multipart/form-data">
	<div class="pgfw-section-wrap">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'pgfw_nonce_field' );
		$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_advanced_settings );
		?>
	</div>
</form>
