<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}

global $pgfw_mwb_pgfw_obj, $mwb_pgfw_gen_flag, $pgfw_save_check_flag;
$pgfw_active_tab   = isset( $_GET['pgfw_tab'] ) ? sanitize_key( $_GET['pgfw_tab'] ) : 'pdf-generator-for-wordpress-general'; // phpcs:ignore
$pgfw_default_tabs = $pgfw_mwb_pgfw_obj->mwb_pgfw_plug_default_tabs();
?>
<header>
	<div class="mwb-header-container mwb-bg-white mwb-r-8">
		<h1 class="mwb-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', $pgfw_mwb_pgfw_obj->pgfw_get_plugin_name() ) ) ); ?></h1>
		<a href="https://docs.makewebbetter.com/" target="_blank" class="mwb-link"><?php esc_html_e( 'Documentation', 'pdf-generator-for-wordpress' ); ?></a>
		<span>|</span>
		<a href="https://makewebbetter.com/contact-us/" target="_blank" class="mwb-link"><?php esc_html_e( 'Support', 'invoice-system-for-woocommerce' ); ?></a>
	</div>
</header>
<?php
if ( $pgfw_save_check_flag ) {
	if ( ! $mwb_pgfw_gen_flag ) {
		$mwb_pgfw_error_text = esc_html__( 'Settings saved successfully !', 'pdf-generator-for-wordpress' );
		$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_admin_notice( $mwb_pgfw_error_text, 'success' );
	} elseif ( $mwb_pgfw_gen_flag ) {
		$mwb_pgfw_error_text = esc_html__( 'There might be some error, Please reload the page and try again.', 'pdf-generator-for-wordpress' );
		$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_admin_notice( $mwb_pgfw_error_text, 'error' );
	}
}
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
					} elseif ( ! empty( $pgfw_active_tab ) && in_array( $pgfw_active_tab, array( 'pdf-generator-for-wordpress-header', 'pdf-generator-for-wordpress-body', 'pdf-generator-for-wordpress-footer' ), true ) ) {
						if ( 'pdf-generator-for-wordpress-customize' === $pgfw_tab_key ) {
							$pgfw_tab_classes .= 'active';
						}
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $pgfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wordpress_menu' ) . '&pgfw_tab=' . esc_attr( $pgfw_tab_key ) ); ?>" class="<?php echo esc_attr( $pgfw_tab_classes ); ?>"><?php echo esc_html( $pgfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<section class="mwb-section">
		<div>
			<?php
			do_action( 'mwb_pgfw_before_general_settings_form' );
			// if submenu is directly clicked on woocommerce.
			if ( empty( $pgfw_active_tab ) ) {
				$pgfw_active_tab = 'mwb_pgfw_plug_general';
			}

			// look for the path based on the tab id in the admin templates.
			$pgfw_tab_content_path = 'admin/partials/' . $pgfw_active_tab . '.php';

			$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_load_template( $pgfw_tab_content_path );

			do_action( 'mwb_pgfw_after_general_settings_form' );
			?>
		</div>
	</section>
