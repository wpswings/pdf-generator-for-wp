<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpswings.com/
 * @since             1.0.0
 * @package           Pdf_Generator_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       PDF Generator For WP
 * Plugin URI:        https://wordpress.org/plugins/pdf-generator-for-wp/
 * Description:       <code><strong>PDF Generator for WordPress</strong></code> plugin allows to generate and download PDF files from WordPress sites across multiple platforms in just one click. Elevate your eCommerce store by exploring more on WP Swings.<a href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-pdf-shop&utm_medium=pdf-org-backend&utm_campaign=shop-page" target="_blank"> Elevate your e-commerce store by exploring more on <strong> WP Swings </strong></a>
 * Version:           1.1.0
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-official&utm_medium=pdf-org-backend&utm_campaign=official
 * Text Domain:       pdf-generator-for-wp
 * Domain Path:       /languages
 *
 * Requires at least:    5.2.0
 * Tested up to:         6.0.2
 * WC requires at least: 5.2.0
 * WC tested up to:      6.9.3
 * Stable tag:           1.1.0
 * Requires PHP:         7.2
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

require_once ABSPATH . '/wp-admin/includes/plugin.php';
$pgfw_old_plugin_exists = false;
$plug           = get_plugins();
if ( isset( $plug['wordpress-pdf-generator/wordpress-pdf-generator.php'] ) ) {
	if ( version_compare( $plug['wordpress-pdf-generator/wordpress-pdf-generator.php']['Version'], '3.0.5', '<' ) ) {
		$pgfw_old_plugin_exists = true;
	}
}

/**
 * Define plugin constants.
 *
 * @since 1.0.0
 */
function define_pdf_generator_for_wp_constants() {
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_VERSION', '1.1.0' );
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_DIR_PATH', plugin_dir_path( __FILE__ ) );
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_DIR_URL', plugin_dir_url( __FILE__ ) );
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_SERVER_URL', 'https://wpswings.com' );
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_ITEM_REFERENCE', 'PDF Generator For Wp' );
}


/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since   1.0.0
 */
function pdf_generator_for_wp_constants( $key, $value ) {

	if ( ! defined( $key ) ) {

		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pdf-generator-for-wp-activator.php
 *
 * @param boolean $network_wide if activated network wide.
 * @since 1.0.3
 * @return void
 */
function activate_pdf_generator_for_wp( $network_wide ) {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wp-activator.php';
	Pdf_Generator_For_Wp_Activator::pdf_generator_for_wp_activate( $network_wide );
	$wps_pgfw_active_plugin                         = get_option( 'wps_all_plugins_active', array() );
	$wps_pgfw_active_plugin['pdf-generator-for-wp'] = array(
		'plugin_name' => __( 'PDF Generator For WordPress', 'pdf-generator-for-wp' ),
		'active'      => '1',
	);
	update_option( 'wps_all_plugins_active', $wps_pgfw_active_plugin );
}

/**
 * Update default values when new site is created.
 *
 * @param object $new_site current blog object.
 * @since 1.0.3
 * @return void
 */
function wps_pgfw_new_site_created_options( $new_site ) {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}
	if ( is_plugin_active_for_network( 'pdf-generator-for-wp/pdf-generator-for-wp.php' ) ) {
		$blog_id = $new_site->blog_id;
		switch_to_blog( $blog_id );
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wp-activator.php';
		Pdf_Generator_For_Wp_Activator::pgfw_updating_default_settings_indb();
		$wps_pgfw_active_plugin                         = get_option( 'wps_all_plugins_active', array() );
		$wps_pgfw_active_plugin['pdf-generator-for-wp'] = array(
			'plugin_name' => __( 'PDF Generator For WordPress', 'pdf-generator-for-wp' ),
			'active'      => '1',
		);
		update_option( 'wps_all_plugins_active', $wps_pgfw_active_plugin );
		restore_current_blog();
	}

}

add_action( 'wp_initialize_site', 'wps_pgfw_new_site_created_options', 900 );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pdf-generator-for-wp-deactivator.php
 */
function deactivate_pdf_generator_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wp-deactivator.php';
	Pdf_Generator_For_Wp_Deactivator::pdf_generator_for_wp_deactivate();
	$wps_pgfw_deactive_plugin = get_option( 'wps_all_plugins_active', false );
	if ( is_array( $wps_pgfw_deactive_plugin ) && ! empty( $wps_pgfw_deactive_plugin ) ) {
		foreach ( $wps_pgfw_deactive_plugin as $wps_pgfw_deactive_key => $wps_pgfw_deactive ) {
			if ( 'pdf-generator-for-wp' === $wps_pgfw_deactive_key ) {
				$wps_pgfw_deactive_plugin[ $wps_pgfw_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'wps_all_plugins_active', $wps_pgfw_deactive_plugin );
}

register_activation_hook( __FILE__, 'activate_pdf_generator_for_wp' );
register_deactivation_hook( __FILE__, 'deactivate_pdf_generator_for_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wp.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pdf_generator_for_wp() {
	define_pdf_generator_for_wp_constants();
	$pgfw_plugin_standard = new Pdf_Generator_For_Wp();
	$pgfw_plugin_standard->pgfw_run();
	$GLOBALS['pgfw_wps_pgfw_obj'] = $pgfw_plugin_standard;
	require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'includes/pdf-generator-for-wp-global-functions.php';
}
run_pdf_generator_for_wp();


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pdf_generator_for_wp_settings_link' );

/**
 * Settings link.
 *
 * @since 1.0.0
 * @param array $links    Settings link array.
 */
function pdf_generator_for_wp_settings_link( $links ) {

	$my_link = array(
		'<a href="' . admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ) . '">' . __( 'Settings', 'pdf-generator-for-wp' ) . '</a>',
	);
	if ( ! in_array( 'wordpress-pdf-generator/wordpress-pdf-generator.php', get_option( 'active_plugins' ), true ) ) {
		$my_link[] = '<a href="https://wpswings.com/product/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-pro&utm_medium=pdf-org-backend&utm_campaign=go-pro" target="_blank" class="wps-pgfw-go-pro-link-backend">' . esc_html__( 'GO PRO', 'pdf-generator-for-wp' ) . '</a>';
	}
	return array_merge( $my_link, $links );
}
/**
 * Adding custom setting links at the plugin activation list.
 *
 * @param array  $links_array array containing the links to plugin.
 * @param string $plugin_file_name plugin file name.
 * @return array
 */
function pdf_generator_for_wp_custom_settings_at_plugin_tab( $links_array, $plugin_file_name ) {
	if ( strpos( $plugin_file_name, basename( __FILE__ ) ) ) {
		$links_array[] = '<a href="https://demo.wpswings.com/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-demo&utm_medium=wpswings-org-backend&utm_campaign=View-demo" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Demo.svg" class="wps-info-img" alt="Demo image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Demo', 'pdf-generator-for-wp' ) . '</a>';
		$links_array[] = '<a href="https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Documentation.svg" class="wps-info-img" alt="documentation image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Documentation', 'pdf-generator-for-wp' ) . '</a>';
		$links_array[] = '<a href="https://wpswings.com/submit-query/?utm_source=wpswings-pdf-support&utm_medium=pdf-org-backend&utm_campaign=submit-query" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Support.svg" class="wps-info-img" alt="support image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Support', 'pdf-generator-for-wp' ) . '</a>';
		$links_array[] = '<a href="https://wpswings.com/wordpress-woocommerce-solutions/?utm_source=wpswings-pdf-service&utm_medium=pdf-org-backend&utm_campaign=service-page" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Services.svg" class="wps-info-img" alt="support image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Services', 'pdf-generator-for-wp' ) . '</a>';
	}
	return $links_array;
}
add_filter( 'plugin_row_meta', 'pdf_generator_for_wp_custom_settings_at_plugin_tab', 10, 2 );

// Adding notice code ///////////////////////////////////// Upgrade notice. /////.


// Update now link in pro.

if ( true === $pgfw_old_plugin_exists ) {

	add_action( 'admin_notices', 'wps_wpg_check_and_inform_update' );
	/**
	 * Check update if pro is old.
	 */
	function wps_wpg_check_and_inform_update() {

		$update_file = plugin_dir_path( dirname( __FILE__ ) ) . 'wordpress-pdf-generator/class-mwb-wordpress-pdf-generator-update.php';

			// If present but not active.
		if ( ! is_plugin_active( 'wordpress-pdf-generator/wordpress-pdf-generator.php' ) ) {
			if ( file_exists( $update_file ) ) {
					$mwb_wpg_license_key = get_option( 'mwb_wpg_license_key', '' );
					! defined( 'WORDPRESS_PDF_GENERATOR_LICENSE_KEY' ) && define( 'WORDPRESS_PDF_GENERATOR_LICENSE_KEY', $mwb_wpg_license_key );
					! defined( 'WORDPRESS_PDF_GENERATOR_BASE_FILE' ) && define( 'WORDPRESS_PDF_GENERATOR_BASE_FILE', 'wordpress-pdf-generator/wordpress-pdf-generator.php' );
					! defined( 'WORDPRESS_PDF_GENERATOR_VERSION' ) && define( 'WORDPRESS_PDF_GENERATOR_VERSION', '3.0.4' );

			}
			require_once $update_file;
		}
		if ( defined( 'WORDPRESS_PDF_GENERATOR_BASE_FILE' ) ) {

				$wps_wpg_version_old_pro = new Mwb_WordPress_Pdf_Generator_Update();
				$wps_wpg_version_old_pro->mwb_check_update();
		}
	}
}

// Ending noticed code/////////////////////////////////////.

add_action( 'after_plugin_row_wordpress-pdf-generator/wordpress-pdf-generator.php', 'wps_wpg_old_upgrade_notice', 0, 3 );
/**
 * Migration to ofl pro plugin.
 *
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @param array  $plugin_data An array of plugin data.
 * @param string $status Status filter currently applied to the plugin list.
 */
function wps_wpg_old_upgrade_notice( $plugin_file, $plugin_data, $status ) {

	global $pgfw_old_plugin_exists;
	if ( $pgfw_old_plugin_exists ) {
		?>
		<tr class="plugin-update-tr active notice-warning notice-alt">
		<td colspan="4" class="plugin-update colspanchange">
			<div class="notice notice-error inline update-message notice-alt">
				<p class='wps-notice-title wps-notice-section'>
					<strong><?php esc_html_e( 'This plugin will not work anymore correctly.', 'pdf-generator-for-wp' ); ?></strong><br>
					<?php esc_html_e( 'We highly recommend to update to latest pro version and once installed please migrate the existing settings.', 'pdf-generator-for-wp' ); ?><br>
					<?php esc_html_e( 'If you are not getting automatic update now button here, then don\'t worry you will get in within 24 hours. If you still not get it please visit to your account dashboard and install it manually or connect to our support.', 'pdf-generator-for-wp' ); ?>
				</p>
			</div>
		</td>
	</tr>
	<style>
	.wps-notice-section > p:before {
				content: none;
			}
		</style>
			<?php
	}
}
add_action( 'admin_notices', 'wps_wpg_migrate_notice', 99 );
/**
 * Migration to new domain notice on main dashboard notice.
 */
function wps_wpg_migrate_notice() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	$tab = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';
	global $pgfw_old_plugin_exists;

	if ( 'pdf_generator_for_wp_menu' === $tab ) {

		?>
		<input type="hidden" class="treat-button">
		<?php
		if ( $pgfw_old_plugin_exists ) {

			?>
			<tr class="plugin-update-tr active notice-warning notice-alt">
					<td colspan="4" class="plugin-update colspanchange">
						<div class="notice notice-warning inline update-message notice-alt">
							<p class='wps-notice-title wps-notice-section'>
								<?php esc_html_e( 'If You are using Premium Version of PDF plugin then please update Pro plugin from plugin page by ', 'pdf-generator-for-wp' ); ?><a style="text-decoration:none;" href="<?php echo esc_url( admin_url( 'plugins.php' ) ); ?>"><?php esc_html_e( 'Click Here', 'pdf-generator-for-wp' ); ?></strong></a>
							</p>
						</div>
					</td>
				</tr>
			<style>
				.wps-notice-section > p:before {
						content: none;
				}
			</style>
				<?php
		}
	}
}
add_action( 'after_plugin_row_' . plugin_basename( __FILE__ ), 'wps_wpg_pro_pdf_upgrade_notice', 0, 3 );

/**
 * Displays notice to upgrade to .
 *
 * @param string $plugin_file Path to the plugin file relative to the plugins directory.
 * @param array  $plugin_data An array of plugin data.
 * @param string $status Status filter currently applied to the plugin list.
 */
function wps_wpg_pro_pdf_upgrade_notice( $plugin_file, $plugin_data, $status ) {
	$plugin_admin = new Pdf_Generator_For_Wp_Admin( 'pdf-generator-for-wp', '1.0.7' );
	$count        = $plugin_admin->wps_wpg_get_count( 'settings' );
	$key3 = get_option( 'wps_wpg_activated_timestamp' );
	if ( ! empty( $count ) && ( empty( $key3 ) ) ) {
		?>

		<tr class="plugin-update-tr active notice-warning notice-alt">
			<td colspan="4" class="plugin-update colspanchange">
				<div class="notice notice-error inline update-message notice-alt">
					<p class='wps-notice-title wps-notice-section'>
						<?php esc_html_e( 'The latest update includes some substantial changes across different areas of the plugin. Hence, if you are not a new user then', 'pdf-generator-for-wp' ); ?><strong><?php esc_html_e( ' please migrate your old data and settings from ', 'pdf-generator-for-wp' ); ?><a style="text-decoration:none;" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ) ); ?>"><?php esc_html_e( 'Dashboard', 'pdf-generator-for-wp' ); ?></strong></a><?php esc_html_e( ' page then Click On Start Import Button.', 'pdf-generator-for-wp' ); ?>
					</p>
				</div>
			</td>
		</tr>
		<style>
			.wps-notice-section > p:before {
				content: none;
			}
		</style>

		<?php
	}
}
