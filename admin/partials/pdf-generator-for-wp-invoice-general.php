<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Invoice_system_for_woocommere
 * @subpackage Invoice_system_for_woocommere/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pgfw_wps_pgfw_obj, $pgfw_wps_wpg_obj;
$pgfw_template_pdf_settings = apply_filters( 'pgfw_template_pdf_settings_array_dummy', array() );

?>
<!--  template file for admin settings. -->
<div class="wpg-section-wrap">
	<form action="" method="post">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'wpg_nonce_field' );
		$pgfw_wps_pgfw_obj->wps_pgfw_plug_generate_html( $pgfw_template_pdf_settings );
		?>
	</form>
</div>
