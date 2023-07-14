<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://wpswings.com/
 * @since      3.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pgfw_wps_pgfw_obj;
$pgfw_taxonomy_settings_arr = apply_filters( 'pgfw_taxonomy_settings_array_dummy', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="wps-wpg-gen-section-form">
	<div class="wpg-secion-wrap">
		<?php
				wp_nonce_field( 'nonce_settings_save', 'wpg_nonce_field' );
				$pgfw_wps_pgfw_obj->wps_pgfw_plug_generate_html($pgfw_taxonomy_settings_arr);
		?>
	</div>
</form>
