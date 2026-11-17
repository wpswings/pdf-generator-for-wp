<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for the cloud storage tab.
 *
 * @link       https://wpswings.com/
 * @since      1.6.6
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pgfw_wps_pgfw_obj;

if ( isset( $_GET['pgfw_cloud_status'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$pgfw_cloud_status = sanitize_key( $_GET['pgfw_cloud_status'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	if ( in_array( $pgfw_cloud_status, array( 'gdrive_connected', 'dropbox_connected' ), true ) ) {
		Pdf_Generator_For_Wp::wps_pgfw_plug_admin_notice( __( 'Successfully connected!', 'pdf-generator-for-wp' ), 'success' );
	} else {
		Pdf_Generator_For_Wp::wps_pgfw_plug_admin_notice( __( 'Could not connect. Please check your credentials and try again.', 'pdf-generator-for-wp' ), 'error' );
	}
}

$pgfw_cloud_storage_settings = apply_filters( 'pgfw_cloud_storage_settings_array', array() );
?>
<!--  template file for cloud storage admin settings. -->
<form action="" method="POST" class="wps-pgfw-gen-section-form">
	<div class="pgfw-secion-wrap">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'pgfw_nonce_field' );
		$pgfw_wps_pgfw_obj->wps_pgfw_plug_generate_html( $pgfw_cloud_storage_settings );
		?>
	</div>
</form>
