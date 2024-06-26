<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
$secure_nonce      = wp_create_nonce( 'wps-pgfw-auth-nonce' );
$id_nonce_verified = wp_verify_nonce( $secure_nonce, 'wps-pgfw-auth-nonce' );
if ( ! $id_nonce_verified ) {
		wp_die( esc_html__( 'Nonce Not verified', 'pdf-generator-for-wp' ) );
}
global $pgfw_wps_pgfw_obj;

$pgfw_active_tab              = isset( $_GET['pgfw_tab'] ) ? sanitize_key( $_GET['pgfw_tab'] ) : 'pdf-generator-for-wp-customize';
$pgfw_default_tabs            = $pgfw_wps_pgfw_obj->wps_pgfw_plug_default_sub_tabs();
$pgfw_settings_display_fields = apply_filters( 'pgfw_display_settings_array', array() );
?>
<main class="wps-main wps-bg-white wps-r-8">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $pgfw_default_tabs ) && ! empty( $pgfw_default_tabs ) ) {
				foreach ( $pgfw_default_tabs as $pgfw_tab_key => $pgfw_default_tabs ) {
					$pgfw_tab_classes = 'wps-link ';
					if ( ! empty( $pgfw_active_tab ) && $pgfw_active_tab === $pgfw_tab_key ) {
						$pgfw_tab_classes .= 'active';
					} elseif ( 'pdf-generator-for-wp-pdf-setting' === $pgfw_active_tab ) {
						if ( 'pdf-generator-for-wp-pdf-icon-setting' === $pgfw_tab_key ) {
							$pgfw_tab_classes .= 'active';
						}
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $pgfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ) . '&pgfw_tab=' . esc_attr( $pgfw_tab_key ) ); ?>" class="<?php echo esc_attr( $pgfw_tab_classes ); ?>"><?php echo esc_html( $pgfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<!--  template file for admin settings. -->
<section class="wps-section">
	<form action="" method="POST" class="wps-pgfw-gen-section-form">
		<div class="pgfw-section-wrap">
			<?php
			wp_nonce_field( 'nonce_settings_save', 'pgfw_nonce_field' );
			$pgfw_wps_pgfw_obj->wps_pgfw_plug_generate_html( $pgfw_settings_display_fields );
			?>
			<div>
				<?php
				/* translators: shortcode name. */
				printf( esc_html__( 'Add %s shortcode anywhere on your page or posts to display PDF generating icon.', 'pdf-generator-for-wp' ), '[WORDPRESS_PDF]' );
				?>
			</div>
		</div>
	</form>
</section>
