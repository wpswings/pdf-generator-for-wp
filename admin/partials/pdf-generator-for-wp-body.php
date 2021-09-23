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
$pgfw_active_tab   = isset( $_GET['pgfw_tab'] ) ? sanitize_key( $_GET['pgfw_tab'] ) : 'pdf-generator-for-wp-header'; // phpcs:ignore
$pgfw_default_tabs = $pgfw_mwb_pgfw_obj->mwb_pgfw_plug_default_sub_tabs();
$pgfw_body_array   = apply_filters( 'pgfw_body_settings_array', array() );
?>
<main class="mwb-main mwb-bg-white mwb-r-8">
	<nav class="mwb-navbar">
		<ul class="mwb-navbar__items">
			<?php
			if ( is_array( $pgfw_default_tabs ) && ! empty( $pgfw_default_tabs ) ) {

				foreach ( $pgfw_default_tabs as $pgfw_tab_key => $pgfw_default_tabs ) {

					$pgfw_tab_classes = 'mwb-link ';

					if ( ! empty( $pgfw_active_tab ) && $pgfw_active_tab === $pgfw_tab_key ) {
						$pgfw_tab_classes .= 'active';
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
<section class="mwb-section">
	<div>
		<form action="" method="POST" class="mwb-pgfw-gen-section-form">
			<div class="pgfw-secion-wrap">
				<?php
				wp_nonce_field( 'nonce_settings_save', 'pgfw_nonce_field' );
				$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_body_array );
				?>
			</div>
		</form>
	</div>
</section>
