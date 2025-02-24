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

global $pgfw_wps_pgfw_obj, $wps_pgfw_gen_flag, $pgfw_save_check_flag;
$pgfw_active_tab   = isset( $_GET['pgfw_tab'] ) ? sanitize_key( $_GET['pgfw_tab'] ) : 'pdf-generator-for-wp-general'; // phpcs:ignore
do_action( 'pgfw_license_activation_notice_on_dashboard' );
$pgfw_default_tabs = $pgfw_wps_pgfw_obj->wps_pgfw_plug_default_tabs();

$wps_wpg_plugin_list = get_option( 'active_plugins' );
$wps_wpg_plugin = 'wordpress-pdf-generator/wordpress-pdf-generator.php';
$wps_wpg_is_pro_active = false;
if ( in_array( $wps_wpg_plugin, $wps_wpg_plugin_list ) ) {
	$wps_wpg_is_pro_active = true;
}
?>
<header>
	<?php
	// desc - This hook is used for trial.
	do_action( 'wps_wpg_settings_saved_notice' );
	?>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php echo esc_attr( strtoupper( str_replace( '-', ' ', apply_filters( 'wps_pgfw_update_plugin_name_dashboard', $pgfw_wps_pgfw_obj->pgfw_get_plugin_name() ) ) ) ); ?></h1>
		<?php if ( ! $wps_wpg_is_pro_active ) { ?>
		<a href="https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation" target="_blank" class="wps-link"><?php esc_html_e( 'Documentation', 'pdf-generator-for-wp' ); ?></a>
		<?php } else { ?>
		<a href="https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation" target="_blank" class="wps-link"><?php esc_html_e( 'Documentation', 'pdf-generator-for-wp' ); ?></a>	
		<?php } ?>
		<span>|</span>
		<a href="https://wpswings.com/submit-query/?utm_source=wpswings-pdf-support&utm_medium=pdf-org-backend&utm_campaign=submit-query" target="_blank" class="wps-link"><?php esc_html_e( 'Support', 'pdf-generator-for-wp' ); ?></a>
	</div>
</header>
<?php
if ( $pgfw_save_check_flag ) {
	if ( ! $wps_pgfw_gen_flag ) {
		$wps_pgfw_error_text = esc_html__( 'Settings saved successfully !', 'pdf-generator-for-wp' );
		$pgfw_wps_pgfw_obj->wps_pgfw_plug_admin_notice( $wps_pgfw_error_text, 'success' );
	} elseif ( $wps_pgfw_gen_flag ) {
		$wps_pgfw_error_text = esc_html__( 'There might be some error, Please reload the page and try again.', 'pdf-generator-for-wp' );
		$pgfw_wps_pgfw_obj->wps_pgfw_plug_admin_notice( $wps_pgfw_error_text, 'error' );
	}
}
?>
<div class="wps-pdf__popup-for-pro-wrap">
	<div class="wps-pdf__popup-for-pro-shadow"></div>
	<div class="wps-pdf__popup-for-pro">
		<span class="wps-pdf__popup-for-pro-close">+</span>
		<h2 class="wps-pdf__popup-for-pro-title">Want More ? <b>Go Pro !</b></h2>
		<p class="wps-pdf__popup-for-pro-content"><i>This will offer features like WhatsApp sharing, Multiple Customization options, Creation of Custom Templates, Downloading in Bulk, and Many more exciting features.</i></p>
		<div class="wps-pdf__popup-for-pro-link-wrap">
			<a target="_blank" href="https://wpswings.com/product/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-pro&utm_medium=pdf-org-backend&utm_campaign=go-pro" class="wps-pdf__popup-for-pro-link">Go pro now</a>
		</div>
	</div>
</div>
<main class="wps-main wps-bg-white wps-r-8">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $pgfw_default_tabs ) && ! empty( $pgfw_default_tabs ) ) {

				foreach ( $pgfw_default_tabs as $pgfw_tab_key => $pgfw_default_tabs ) {

					$pgfw_tab_classes = 'wps-link ';

					$pgfw_tab_classe_pro = '';
					if ( ! empty( $pgfw_active_tab ) && $pgfw_active_tab === $pgfw_tab_key ) {
						$pgfw_tab_classes .= 'active';
					} elseif ( ! empty( $pgfw_active_tab ) && in_array( $pgfw_active_tab, array( 'pdf-generator-for-wp-header', 'pdf-generator-for-wp-body', 'pdf-generator-for-wp-footer', 'pdf-generator-for-wp-pdf-icon-setting' ), true ) ) {
						if ( 'pdf-generator-for-wp-pdf-setting' === $pgfw_tab_key ) {
							$pgfw_tab_classes .= 'active';
						}
					} elseif ( ! empty( $pgfw_active_tab ) && in_array( $pgfw_active_tab, array( 'pdf-generator-for-wp-cover-page-setting', 'pdf-generator-for-wp-internal-page-setting' ), true ) ) {
						if ( 'pdf-generator-for-wp-layout-settings' === $pgfw_tab_key ) {
							$pgfw_tab_classes .= 'active';

						}
					}
					if ( 'Taxonomy Settings' == $pgfw_default_tabs['title'] || 'Layout Settings' == $pgfw_default_tabs['title'] || 'PDF Logs' == $pgfw_default_tabs['title'] || 'Invoice settings' == $pgfw_default_tabs['title'] || 'Invoice page settings' == $pgfw_default_tabs['title'] ) {
						if ( ! $wps_wpg_is_pro_active ) {
							$pgfw_tab_classe_pro .= 'wps_pgfw_pro_tag_lable_main';
						}
					}
					?>
					<li class="<?php echo esc_attr( $pgfw_tab_classe_pro ); ?>">
						<a id="<?php echo esc_attr( $pgfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ) . '&pgfw_tab=' . esc_attr( $pgfw_tab_key ) ); ?>" class="<?php echo esc_attr( $pgfw_tab_classes ); ?>"><?php echo esc_html( $pgfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>
	<?php
		$plugin_admin = new Pdf_Generator_For_Wp_Admin( 'pdf-generator-for-wp', '1.0.7' );
		$count        = $plugin_admin->wps_wpg_get_count( 'settings' );
		$key3 = get_option( 'wps_wpg_activated_timestamp' );
	if ( ! empty( $count ) && ( empty( $key3 ) ) ) {
			$global_custom_css = 'const triggerError = () => {
				swal({
					title: "Attention Required!",
					text: "Please Migrate Your Database keys first by click on the below button then you can access the dashboard page.",
					icon: "error",
					button: "Click to Import",
					closeOnClickOutside: false,
				}).then(function() {
					jQuery( ".treat-button" ).click();
				});
			}
			triggerError();';
			wp_register_script( 'wps_wpg_incompatible_css', false, array(), $this->version, 'all' );
			wp_enqueue_script( 'wps_wpg_incompatible_css' );
			wp_add_inline_script( 'wps_wpg_incompatible_css', $global_custom_css );
	}
	?>
	<section class="wps-section">
		<div>
			<?php
			do_action( 'wps_pgfw_before_general_settings_form' );
			// if submenu is directly clicked on woocommerce.
			if ( empty( $pgfw_active_tab ) ) {
				$pgfw_active_tab = 'wps_pgfw_plug_general';
			}

			// look for the path based on the tab id in the admin templates.
			$pgfw_tab_content_path = 'admin/partials/' . $pgfw_active_tab . '.php';

			$pgfw_wps_pgfw_obj->wps_pgfw_plug_load_template( $pgfw_tab_content_path );

			do_action( 'wps_pgfw_after_general_settings_form' );
			?>
		</div>
		</section>
