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
$pgfw_genaral_settings = apply_filters( 'pgfw_general_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-pgfw-gen-section-form">
	<div class="pgfw-secion-wrap">
		<?php
		$pgfw_general_html = $pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_genaral_settings );
		echo esc_html( $pgfw_general_html );
		?>
	</div>
</form>