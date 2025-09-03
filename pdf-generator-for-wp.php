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
 * Version:           1.5.5
 * Author:            WP Swings
 * Author URI:        https://wpswings.com/?utm_source=wpswings-official&utm_medium=pdf-org-backend&utm_campaign=official
 * Text Domain:       pdf-generator-for-wp
 * Domain Path:       /languages
 *
 * Requires at least:    6.7.0
 * Tested up to:         6.8.2
 * WC requires at least: 6.5.0
 * WC tested up to:      10.1.2
 * Stable tag:           1.5.5
 * Requires PHP:         7.4
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

use Automattic\WooCommerce\Utilities\OrderUtil;
// HPOS Compatibility.
add_action(
	'before_woocommerce_init',
	function () {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
		}
	}
);
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
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_VERSION', '1.5.5' );
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
	$wps_pgfw_active_plugin = get_option( 'wps_all_plugins_active', array() );
	if ( ! is_array( $wps_pgfw_active_plugin ) ) {
		// If someone stored JSON in the past, try to decode it.
		$decoded = is_string( $wps_pgfw_active_plugin ) ? json_decode( $wps_pgfw_active_plugin, true ) : null;
		$wps_pgfw_active_plugin = is_array( $decoded ) ? $decoded : array();
	}

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
	wp_clear_scheduled_hook( 'wps_wgm_check_for_notification_update' );
}

register_activation_hook( __FILE__, 'activate_pdf_generator_for_wp' );
register_deactivation_hook( __FILE__, 'deactivate_pdf_generator_for_wp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wp.php';


/**
 * This function is used to check PAR pro plugin is active or not.
 *
 * @return bool
 */
function wps_pgfw_is_pdf_pro_plugin_active() {
	$flag = false;
	if ( is_plugin_active( 'wordpress-pdf-generator/wordpress-pdf-generator.php' ) ) {

		$flag = true;
	}
	return $flag;
}

/**
 * Register new Elementor widgets.
 *
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function wps_register_new_widgets( $widgets_manager ) {
	$wps_pgfw_sources = array(
		'wps_tracking_info',
		'wps_pdf_shortcode',
		'wps_single_image',
		'wps_calendly',
		'wps_linkedln',
		'wps_loom',
		'wps_twitch',
		'wps_ai_chatbot',
		'wps_canva',
		'wps_reddit',
		'wps_google_elements',
		'wps_strava',
		'wps_rss_feed',
		'wps_x',
		'wps_pdf_embed',
	);

	$always_include = array( 'wps_pdf_shortcode', 'wps_single_image' );
	$pro_only_widgets = array( 'wps_ai_chatbot', 'wps_pdf_embed', 'wps_rss_feed' );
	$tofw_only_widgets = array( 'wps_tracking_info' );

	// Check if Pro plugin is active.
	include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	$pdf_is_pro_plugin_active = is_plugin_active( 'wordpress-pdf-generator/wordpress-pdf-generator.php' );
	$tofw_is_pro_plugin_active = is_plugin_active( 'track-orders-for-woocommerce/track-orders-for-woocommerce.php' );

	foreach ( $wps_pgfw_sources as $source ) {
		$should_load = false;

		if ( in_array( $source, $always_include ) ) {
			$should_load = true;
		} elseif ( in_array( $source, $pro_only_widgets ) ) {
			$option_key = str_replace( 'wps_', '', $source );
			$option_key = "wps_embed_source_{$option_key}";

			if ( 'on' === get_option( $option_key, '' ) && $pdf_is_pro_plugin_active ) {
				$should_load = true;
			}
		} elseif ( in_array( $source, $tofw_only_widgets ) ) {
			$option_key = str_replace( 'wps_', '', $source );
			$option_key = "wps_embed_source_{$option_key}";

			if ( 'on' === get_option( $option_key, '' ) && $tofw_is_pro_plugin_active ) {
				$should_load = true;
			}
		} else {
			$option_key = str_replace( 'wps_', '', $source );
			$option_key = "wps_embed_source_{$option_key}";
			if ( 'on' === get_option( $option_key, '' ) ) {
				$should_load = true;
			}
		}

		if ( ! $should_load ) {
			continue;
		}

		$wps_source = str_replace( '_', '-', $source );
		$wps_sources_class = strtoupper( strtok( $source, '_' ) ) . '_' . ucfirst( substr( $source, strpos( $source, '_' ) + 1 ) );
		$wps_widget_file = plugin_dir_path( __FILE__ ) . "Elementor/class-elementor-widget-{$wps_source}.php";

		if ( file_exists( $wps_widget_file ) ) {
			require_once( $wps_widget_file );
		}

		$wps_class_name = "Elementor_Widget_{$wps_sources_class}";
		if ( class_exists( $wps_class_name ) ) {
			$widgets_manager->register( new $wps_class_name() );
		}
	}
}

add_action( 'elementor/widgets/register', 'wps_register_new_widgets' );
add_action(
	'elementor/elements/categories_registered',
	function ( $elements_manager ) {
		$elements_manager->add_category(
			'wps_pdf_widgets',
			array(
				'title' => __( 'WPSwings PDF Widgets', 'pdf-generator-for-wp' ),
				'icon'  => 'eicon-file-download',
			)
		);
	}
);


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
		$links_array[] = '<a href="https://www.youtube.com/watch?v=RljECeP3JJk" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/YouTube32px.svg" class="wps-info-img" alt="documentation image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Video', 'pdf-generator-for-wp' ) . '</a>';
		$links_array[] = '<a href="https://wpswings.com/submit-query/?utm_source=wpswings-pdf-support&utm_medium=pdf-org-backend&utm_campaign=submit-query" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Support.svg" class="wps-info-img" alt="support image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Support', 'pdf-generator-for-wp' ) . '</a>';
		$links_array[] = '<a href="https://wpswings.com/wordpress-woocommerce-solutions/?utm_source=wpswings-pdf-service&utm_medium=pdf-org-backend&utm_campaign=service-page" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Services.svg" class="wps-info-img" alt="support image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Services', 'pdf-generator-for-wp' ) . '</a>';
	}
	return $links_array;
}
add_filter( 'plugin_row_meta', 'pdf_generator_for_wp_custom_settings_at_plugin_tab', 10, 2 );


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
			.wps-notice-section>p:before {
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
function wps_wpg_migrate_notice() {      // phpcs:disable WordPress.Security.NonceVerification.Recommended
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
				.wps-notice-section>p:before {
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
						<?php esc_html_e( 'The latest update includes some substantial changes across different areas of the plugin. Hence, if you are not a new user then', 'pdf-generator-for-wp' ); ?><strong><?php esc_html_e( ' Please migrate your old data and settings from ', 'pdf-generator-for-wp' ); ?><a style="text-decoration:none;" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ) ); ?>"><?php esc_html_e( 'Dashboard', 'pdf-generator-for-wp' ); ?></strong></a><?php esc_html_e( ' page then Click On Start Import Button.', 'pdf-generator-for-wp' ); ?>
					</p>
				</div>
			</td>
		</tr>
		<style>
			.wps-notice-section>p:before {
				content: none;
			}
		</style>

		<?php
	}
}

/**
 * Description: Embeds a Calendly Meeting.
 *
 * @param array $atts post id to print PDF for.
 */
function wps_calendly_embed_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'url' => '',
		),
		$atts,
		'wps_calendly'
	);

	return '<iframe src="' . esc_url( $atts['url'] ) . '" width="100%" height="600px" style="border: none;"></iframe>';
}

/**
 * Adding shortcode to show calendly Events anywhere on the page.
 */
if ( 'on' === get_option( 'wps_embed_source_calendly', '' ) ) {
	add_shortcode( 'wps_calendly', 'wps_calendly_embed_shortcode' );
}
if ( 'on' === get_option( 'wps_embed_source_twitch', '' ) ) {
	add_shortcode( 'wps_twitch', 'wps_twitch_stream_with_chat_shortcode' );
}

if ( 'on' === get_option( 'wps_embed_source_strava', '' ) ) {
	add_shortcode( 'wps_strava', 'wps_strava_embed_shortcode' );
}

if ( is_plugin_active( 'wordpress-pdf-generator/wordpress-pdf-generator.php' ) ) {
	if ( 'on' === get_option( 'wps_embed_source_ai_chatbot', '' ) ) {
		add_shortcode( 'wps_ai_chatbot', 'wps_chatbot_ai_shortcode' );
	}
	if ( 'on' === get_option( 'wps_embed_source_rss_feed', '' ) ) {
		add_shortcode( 'wps_rssapp_feed', 'wps_rssapp_feed_shortcode' );
	}
}

if ( 'on' === get_option( 'wps_embed_source_wps_track_order', '' ) && is_plugin_active( 'track-orders-for-woocommerce/track-orders-for-woocommerce.php' ) ) {
	add_shortcode( 'wps_tracking_info', 'wps_pgfw_tracking_info_shortcode' );
}

/**
 * Shortcode: [wps_tracking_info].
 * Description: Displays tracking information for a WooCommerce order.
 *
 * @param array $atts Attributes for the shortcode.
 * - order_id: (int) The ID of the WooCommerce order (required).
 * - align: (string) Text alignment ('left', 'center', 'right', default: 'center').
 *
 * Example usage:
 * [wps_tracking_info order_id="12345" align="left"].
 */
function wps_pgfw_tracking_info_shortcode( $atts ) {
	$atts = shortcode_atts(
		array(
			'order_id' => '',
			'align'    => 'center',
		),
		$atts,
		'wps_tracking_info'
	);

	if ( empty( $atts['order_id'] ) ) {
		return '<div style="color:red;">Order ID is missing.</div>';
	}

	$wps_pgfw_order_id = intval( $atts['order_id'] );
	$wps_pgfw_order = wc_get_order( $wps_pgfw_order_id );

	if ( ! $wps_pgfw_order ) {
		return '<div style="color:red;">Invalid Order ID.</div>';
	}

	// Order meta data.
	$wps_pgfw_estimated_date  = $wps_pgfw_order->get_meta( 'wps_tofw_estimated_delivery_date' );
	$wps_pgfw_estimated_time  = $wps_pgfw_order->get_meta( 'wps_tofw_estimated_delivery_time' );
	$wps_pgfw_carrier_base    = $wps_pgfw_order->get_meta( 'wps_tofwp_enhanced_order_company' );
	$wps_pgfw_tracking_number = $wps_pgfw_order->get_meta( 'wps_tofwp_enhanced_tracking_no' );
	$wps_pgfw_tracking_link   = $wps_pgfw_carrier_base && $wps_pgfw_tracking_number ? esc_url( $wps_pgfw_carrier_base . urlencode( $wps_pgfw_tracking_number ) ) : '';

	$wps_pgfw_saved_settings  = get_option( 'wps_tofwp_general_settings_saved' );
	$wps_pgfw_saved_providers = isset( $wps_pgfw_saved_settings['providers_data'] ) ? $wps_pgfw_saved_settings['providers_data'] : array();

	// Order status.
	$wps_pgfw_status = wc_get_order_status_name( $wps_pgfw_order->get_status() );

	// Text alignment logic.
	$wps_pgfw_allowed_alignments = array( 'left', 'center', 'right' );
	$wps_pgfw_align = in_array( strtolower( $atts['align'] ), $wps_pgfw_allowed_alignments ) ? strtolower( $atts['align'] ) : 'center';

	$wps_pgfw_container_style = 'max-width: 500px; padding: 20px; border-radius: 12px; background: #f8f9fa; box-shadow: 0 4px 12px rgba(0,0,0,0.1); font-family: Arial, sans-serif;';
	if ( 'center' === $wps_pgfw_align ) {
		$wps_pgfw_container_style .= ' margin: 20px auto;';
	} elseif ( 'left' === $wps_pgfw_align ) {
		$wps_pgfw_container_style .= ' margin: 20px 0 20px auto; float: left;';
	} else {
		$wps_pgfw_container_style .= ' margin: 20px auto 20px 0; float: right;';
	}

	ob_start();
	if ( is_plugin_active( 'track-orders-for-woocommerce-pro/track-orders-for-woocommerce-pro.php' ) ) {
		$wps_pgfw_plugin_url = TRACK_ORDERS_FOR_WOOCOMMERCE_PRO_DIR_URL;
		$wps_pgfw_icon_path  = $wps_pgfw_plugin_url . 'admin/partials/assets/icons/';
		$wps_pgfw_matched_carrier_name = '';
		$wps_pgfw_icon_url = '';

		// Detect matched carrier and icon.
		foreach ( $wps_pgfw_saved_providers as $wps_pgfw_name => $wps_pgfw_url ) {
			if ( strpos( $wps_pgfw_carrier_base, $wps_pgfw_url ) !== false ) {
				$wps_pgfw_matched_carrier_name = $wps_pgfw_name;
				$wps_pgfw_icon_file = strtolower( str_replace( ' ', '', $wps_pgfw_matched_carrier_name ) ) . '.png';
				$wps_pgfw_icon_full_path = TRACK_ORDERS_FOR_WOOCOMMERCE_PRO_DIR_PATH . 'admin/partials/assets/icons/' . $wps_pgfw_icon_file;
				$wps_pgfw_icon_url = file_exists( $wps_pgfw_icon_full_path ) ? $wps_pgfw_icon_path . $wps_pgfw_icon_file : $wps_pgfw_icon_path . 'default.png';
				break;
			}
		}
	}
	?>
	<div style="<?php echo esc_attr( $wps_pgfw_container_style ); ?>">
		<h2 style="margin-top: 0; color: #333;"><?php echo esc_html__( 'Order Tracking Information', 'pdf-generator-for-wp' ); ?></h2>
		<p><strong><?php echo esc_html__( 'Order ID:', 'pdf-generator-for-wp' ); ?></strong> <?php echo esc_html( $wps_pgfw_order_id ); ?></p>
		<p><strong><?php echo esc_html__( 'Order Status:', 'pdf-generator-for-wp' ); ?></strong> <span style="color: green;"><?php echo esc_html( $wps_pgfw_status ); ?></span></p>
		<?php if ( is_plugin_active( 'track-orders-for-woocommerce-pro/track-orders-for-woocommerce-pro.php' ) ) { ?>
			<?php if ( $wps_pgfw_estimated_date || $wps_pgfw_estimated_time ) : ?>
				<p><strong><?php echo esc_html__( 'Estimated Delivery:', 'pdf-generator-for-wp' ); ?></strong><br>
					<?php if ( $wps_pgfw_estimated_date ) : ?>
						📅 <?php echo esc_html( $wps_pgfw_estimated_date ); ?><br>
					<?php endif; ?>
					<?php if ( $wps_pgfw_estimated_time ) : ?>
						⏰ <?php echo esc_html( $wps_pgfw_estimated_time ); ?>
					<?php endif; ?>
				</p>
			<?php endif; ?>


			<?php if ( $wps_pgfw_tracking_link ) : ?>
				<div style="margin-top: 15px;">
					<strong><?php echo esc_html__( 'Carrier Tracking:', 'pdf-generator-for-wp' ); ?></strong>
					<div style="display: flex; align-items: center; gap: 10px; margin-top: 8px;">
						<?php if ( $wps_pgfw_icon_url ) : ?>
							<img src="<?php echo esc_url( $wps_pgfw_icon_url ); ?>" alt="<?php echo esc_attr( $wps_pgfw_matched_carrier_name ); ?>" style="height: 35px;">
						<?php endif; ?>
						<?php if ( $wps_pgfw_matched_carrier_name ) : ?>
							<span style="font-weight: bold;"><?php echo esc_html( $wps_pgfw_matched_carrier_name ); ?></span>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
		<?php } ?>

		<div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px;">
			<?php if ( is_plugin_active( 'track-orders-for-woocommerce-pro/track-orders-for-woocommerce-pro.php' ) ) { ?>
				<?php if ( $wps_pgfw_tracking_link ) { ?>
					<a href="<?php echo esc_url( $wps_pgfw_tracking_link ); ?>" class="button wc-forward" target="_blank" style="flex: 1; text-align: center; background-color: #0071a1; color: #fff; padding: 10px 15px; border-radius: 6px; text-decoration: none;">Track with Carrier</a>
					<?php
				}
			}
			?>
			<a href="<?php echo esc_url( home_url( '/track-your-order/?' . $wps_pgfw_order_id ) ); ?>" class="button wc-forward" style="flex: 1; text-align: center; background-color: #28a745; color: #fff; padding: 10px 15px; border-radius: 6px; text-decoration: none;">Track Your Order</a>
		</div>
	</div>
	<?php
	return ob_get_clean();
}


/**
 * Shortcode: [wps_twitch].
 * Description: Embeds a Twitch stream with optional live chat side-by-side.
 *
 * @param array $atts post id to print PDF for.
 *
 * Attributes:
 * - channel: (string) Twitch channel username (required).
 * - width: (string) Width of the Twitch player iframe (default: '100%').
 * - height: (string|int) Height of the Twitch player iframe (default: '480').
 * - chat_width: (string) Width of the chat iframe (default: '100%').
 * - chat_height: (string|int) Height of the chat iframe (default: '480').
 * - show_chat: (string) Whether to show chat iframe (yes/no, default: 'yes').
 *
 * Example usage:
 * [wps_twitch channel="your_channel" width="70%" chat_width="30%" show_chat="yes"].
 */
function wps_twitch_stream_with_chat_shortcode( $atts ) {
	// Set default attributes and merge with user-provided values.
	$atts = shortcode_atts(
		array(
			'channel'     => '',          // Twitch channel name.
			'width'       => '100%',      // Player width.
			'height'      => '480',       // Player height.
			'chat_width'  => '100%',      // Chat width.
			'chat_height' => '480',       // Chat height.
			'show_chat'   => 'yes',       // Show chat? 'yes' or 'no'.
		),
		$atts,
		'wps_twitch'
	);

	// Return error if no channel provided.
	if ( empty( $atts['channel'] ) ) {
		return '<p>Please provide a Twitch channel name.</p>';
	}

	// Set the parent domain (required by Twitch embed policy).
	$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
	$parent = esc_attr( $host );

	// Start building output.
	$output = '<div class="wps-twitch-embed" style="display: flex; flex-wrap: wrap; gap: 20px;">';

	// Twitch video stream.
	$output .= '<iframe
        src="https://player.twitch.tv/?channel=' . esc_attr( $atts['channel'] ) . '&parent=' . $parent . '"
        frameborder="0"
        allowfullscreen="true"
        scrolling="no"
        height="' . esc_attr( $atts['height'] ) . '"
        width="' . esc_attr( $atts['width'] ) . '">
    </iframe>';

	// Twitch live chat (optional).
	if ( 'yes' === $atts['show_chat'] ) {
		$output .= '<iframe
            src="https://www.twitch.tv/embed/' . esc_attr( $atts['channel'] ) . '/chat?parent=' . $parent . '"
            frameborder="0"
            scrolling="no"
            height="' . esc_attr( $atts['chat_height'] ) . '"
            width="' . esc_attr( $atts['chat_width'] ) . '">
        </iframe>';
	}

	$output .= '</div>';

	return $output;
}

/**
 * Shortcode: [wps_strava].
 * Description: Embeds a Strava activity or segment using Strava Embeds service.
 *
 * @param array $atts post id to print PDF for.
 * Attributes:
 * - id: (string) The Strava activity or segment ID (required).
 * - type: (string) Type of embed: 'activity', 'segment', etc. (default: 'activity').
 * - style: (string) Display style, e.g., 'standard' (default: 'standard').
 * - from_embed: (string) Optional flag for embed source, e.g., 'true' or 'false' (default: 'false').
 *
 * Example usage:
 * [wps_strava id="1234567890" type="activity" style="standard"].
 */
function wps_strava_embed_shortcode( $atts ) {
	// Merge user-provided attributes with defaults.
	$atts = shortcode_atts(
		array(
			'id' => '', // Strava Activity or Segment ID.
			'type' => 'activity', // Type of embed (activity, segment, etc.).
			'style' => 'standard', // Visual style of embed.
			'from_embed' => 'false', // Optional flag for embed behavior.
		),
		$atts,
		'wps_strava'
	);

	// If no ID is provided, return an error message.
	if ( empty( $atts['id'] ) ) {
		return '<p>Please provide a valid Strava activity ID.</p>';
	}

	// Start capturing output.
	ob_start();
	?>
	<!-- Strava Embed Placeholder -->
	<div class="strava-embed-placeholder"
		data-embed-type="<?php echo esc_attr( $atts['type'] ); ?>"
		data-embed-id="<?php echo esc_attr( $atts['id'] ); ?>"
		data-style="<?php echo esc_attr( $atts['style'] ); ?>"
		data-from-embed="<?php echo esc_attr( $atts['from_embed'] ); ?>">
	</div>

	<!-- Strava Embed Script -->
	<?php
	if ( ! wp_script_is( 'strava-embed', 'enqueued' ) ) {
		wp_enqueue_script( 'strava-embed', 'https://strava-embeds.com/embed.js', array(), time(), true );
	}
	// Return the buffered output.
	return ob_get_clean();
}

/**
 * Shortcode: [wps_ai_chatbot].
 * Description: Embeds a customizable AI chatbot iframe widget.
 *
 * @param array $atts Post ID to print PDF for.
 * Attributes:
 * - url: (string) The chatbot URL to be embedded (required).
 * - height: (string) Height of the chatbot iframe (default: 700px).
 * - header_color: (string) Background color or gradient of the chatbot header (default: #4e54c8).
 * - header_title: (string) Title text shown in the header (default: "AI Chat Assistant").
 *
 * Example:
 * [wps_ai_chatbot url="https://yourbot.com/chat" height="600px" header_title="Support Bot"].
 */
function wps_chatbot_ai_shortcode( $atts ) {
	// Set default values and merge with user-supplied attributes.
	$atts = shortcode_atts(
		array(
			'url' => '',
			'height' => '700px',
			'header_color' => '#4e54c8',
			'header_title' => 'AI Chat Assistant',
		),
		$atts
	);

	// Show an error message if the required URL is missing.
	if ( empty( $atts['url'] ) ) {
		return '<div style="color: red; font-weight: bold;">Chatbot URL is missing.</div>';
	}

	// Start output buffering to return generated HTML.
	ob_start();
	?>
	<style>
		/* Main chatbot wrapper styling */
		.wps-chatbot-wrapper {
			max-width: 960px;
			margin: 40px auto;
			border-radius: 16px;
			overflow: hidden;
			box-shadow: 0 12px 30px rgba(0, 0, 0, 0.1);
			background: #fff;
			animation: fadeIn 1s ease-in-out;
			font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
		}

		/* Header styling with dynamic background color */
		.wps-chatbot-header {
			background: <?php echo esc_attr( $atts['header_color'] ); ?>;
			color: #fff;
			padding: 20px 30px;
			font-size: 20px;
			font-weight: bold;
			display: flex;
			align-items: center;
			gap: 10px;
		}

		/* Optional icon style */
		.wps-chatbot-header svg {
			width: 24px;
			height: 24px;
			fill: #fff;
		}

		/* Iframe container */
		.wps-chatbot-iframe-container iframe {
			width: 100%;
			min-height: <?php echo esc_attr( $atts['height'] ); ?>;
			border: none;
			display: block;
		}

		/* Simple fade-in animation */
		@keyframes fadeIn {
			from {
				opacity: 0;
				transform: translateY(20px);
			}

			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
	</style>

	<!-- Chatbot Embed Structure -->
	<div class="wps-chatbot-wrapper wps-no-print">
		<!-- Header with title and optional icon -->
		<div class="wps-chatbot-header">
			<svg viewBox="0 0 24 24">
				<path d="M12 2a10 10 0 0 0-10 10 9.99 9.99 0 0 0 5.29 8.75c-.1.75-.32 1.84-.79 3.01 0 0-.04.09.01.14.05.05.13.02.13.02 1.72-.24 3.05-.99 3.58-1.33A10.01 10.01 0 0 0 22 12 10 10 0 0 0 12 2z" />
			</svg>
			<?php echo esc_html( $atts['header_title'] ); ?>
		</div>

		<!-- Embedded chatbot iframe -->
		<div class="wps-chatbot-iframe-container">
			<iframe src="<?php echo esc_url( $atts['url'] ); ?>" style="min-height: <?php echo esc_attr( $atts['height'] ); ?>;"></iframe>
		</div>
	</div>
	<?php
	// Return the output buffer content.
	return ob_get_clean();
}

/**
 * Shortcode: [wps_rssapp_feed].
 * Description: Embeds an RSS.app widget feed with customizable styles via shortcode attributes.
 *
 * @param array $atts array.
 *
 * Attributes:
 * - url: (string) The RSS.app embed URL (required).
 * - height: (string) Height of the embedded iframe (default: 600px).
 * - title: (string) Title displayed above the feed (default: "📰 Latest News").
 * - bg_color: (string) Background color of the header (default: #ffffff).
 * - text_color: (string) Text color of the header and content (default: #333333).
 * - border_color: (string) Border color for the widget container (default: #eeeeee).
 *
 * Usage:
 * [wps_rssapp_feed url="https://rss.app/embed/your-widget" height="500px" title="My Feed"].
 */
function wps_rssapp_feed_shortcode( $atts ) {
	// Define default attribute values and merge with user-supplied attributes.
	$atts = shortcode_atts(
		array(
			'url' => '',
			'height' => '600px',
			'title' => '📰 Latest News',
			'bg_color' => '#ffffff',
			'text_color' => '#333333',
			'border_color' => '#eeeeee',
		),
		$atts
	);

	// If URL is missing, show a helpful message.
	if ( empty( $atts['url'] ) ) {
		return '<p>Please provide a valid RSS.app widget URL.</p>';
	}

	// Start output buffering to return HTML content as a string.
	ob_start();
	?>
	<div class="wps-rssapp-news-wrapper wps-no-print" style="
		color: <?php echo esc_attr( $atts['text_color'] ); ?>;
		border: 1px solid <?php echo esc_attr( $atts['border_color'] ); ?>;
		border-radius: 12px;
		overflow: hidden;
		box-shadow: 0 5px 15px rgba(0, 0, 0, 0.07);
		margin: 30px auto;
		max-width: 1000px;
	">
		<!-- Feed Header -->
		<div class="wps-rssapp-header wps-no-print" style="
			padding: 16px 24px;
			font-size: 22px;
			font-weight: bold;
			border-bottom: 1px solid <?php echo esc_attr( $atts['border_color'] ); ?>;
			background-color: <?php echo esc_attr( $atts['bg_color'] ); ?>;
			color: <?php echo esc_attr( $atts['text_color'] ); ?>;
		">
			<?php echo esc_html( $atts['title'] ); ?>
		</div>

		<!-- RSS App Iframe -->
		<iframe
			src="<?php echo esc_url( $atts['url'] ); ?>"
			width="100%"
			height="<?php echo esc_attr( $atts['height'] ); ?>"
			style="border: none; overflow-y: auto;"
			scrolling="yes"></iframe>
	</div>
	<?php
	// Return the buffered content.
	return ob_get_clean();
}

/**
 * Adding shortcode to show create pdf icon anywhere on the page.
 */
add_shortcode( 'WPS_SINGLE_IMAGE', 'wps_display_uploaded_image_shortcode' );

/**
 * Callback function for shortcode.
 *
 * @since 1.0.0
 * @param array $atts An array of shortcode.
 * @return string
 */
function wps_display_uploaded_image_shortcode( $atts ) {
	// Set default attributes for the shortcode.
	$atts = shortcode_atts(
		array(
			'id'  => '',    // Attachment ID.
			'url' => '',    // Image URL if no ID is given.
			'alt' => 'Image', // Alt text for accessibility.
			'width'  => '100%', // Width of the image.
			'height' => 'auto',  // Height of the image.
		),
		$atts,
		'wps_image'
	);

	// Get image URL from attachment ID if provided.
	if ( ! empty( $atts['id'] ) ) {
		$image_src = wp_get_attachment_image_url( $atts['id'], 'full' );
	} else {
		$image_src = esc_url( $atts['url'] );
	}

	// Check if image source exists.
	if ( empty( $image_src ) ) {
		return '<p>No image found.</p>';
	}
	// Return the image HTML.
	return '<img src="' . esc_url( $image_src ) . '" alt="' . esc_attr( $atts['alt'] ) . '" style="width: ' . esc_attr( $atts['width'] ) . '; height: ' . esc_attr( $atts['height'] ) . ';">';
}


/**
 * Notification update.
 */
function wps_pgfw_remove_cron_for_notification_update() {
	wp_clear_scheduled_hook( 'wps_wgm_check_for_notification_update' );
}

add_action( 'admin_notices', 'wps_banner_notification_plugin_html' );

if ( ! function_exists( 'wps_banner_notification_plugin_html' ) ) {
	/**
	 * Notification.
	 */
	function wps_banner_notification_plugin_html() {
		$screen = get_current_screen();
		if ( ! $screen || empty( $screen->id ) ) {
			return;
		}

		$target_screens = array( 'plugins', 'dashboard', 'wp-swings_page_home' );
		$page_param     = isset( $_GET['page'] ) ? sanitize_text_field( wp_unslash( $_GET['page'] ) ) : '';

		// Check whether to show on specific pages or screens.
		if ( 'wc-settings' === $page_param || in_array( $screen->id, $target_screens, true ) ) {
			$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );
			if ( isset( $banner_id ) && '' !== $banner_id ) {
				$hidden_banner_id            = get_option( 'wps_wgm_notify_hide_baneer_notification', false );
				$banner_image = get_option( 'wps_wgm_notify_new_banner_image', '' );
				$banner_url = get_option( 'wps_wgm_notify_new_banner_url', '' );
				if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

					if ( '' !== $banner_image && '' !== $banner_url ) {

						?>
						<div class="wps-offer-notice notice notice-warning is-dismissible">
							<div class="notice-container">
								<a href="<?php echo esc_url( $banner_url ); ?>" target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Gift cards" /></a>
							</div>
							<button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
						</div>

						<?php
					}
				}
			}
		}
	}
}

add_action( 'admin_notices', 'wps_pgfw_notification_plugin_html' );
/**
 * Notification html.
 */
function wps_pgfw_notification_plugin_html() {
	$screen = get_current_screen();
	if ( isset( $screen->id ) ) {
		$pagescreen = $screen->id;
	}
	if ( ( isset( $_GET['page'] ) && 'pdf_generator_for_wp_menu' === $_GET['page'] ) ) {
		$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );
		if ( isset( $banner_id ) && '' !== $banner_id ) {
			$hidden_banner_id            = get_option( 'wps_wgm_notify_hide_baneer_notification', false );
			$banner_image = get_option( 'wps_wgm_notify_new_banner_image', '' );
			$banner_url = get_option( 'wps_wgm_notify_new_banner_url', '' );
			if ( isset( $hidden_banner_id ) && $hidden_banner_id < $banner_id ) {

				if ( '' !== $banner_image && '' !== $banner_url ) {

					?>
					<div class="wps-offer-notice notice notice-warning is-dismissible">
						<div class="notice-container">
							<a href="<?php echo esc_url( $banner_url ); ?>" target="_blank"><img src="<?php echo esc_url( $banner_image ); ?>" alt="Gift cards" /></a>
						</div>
						<button type="button" class="notice-dismiss dismiss_banner" id="dismiss-banner"><span class="screen-reader-text">Dismiss this notice.</span></button>
					</div>

					<?php
				}
			}
		}
	}
}
