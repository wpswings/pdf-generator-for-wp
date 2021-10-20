<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/?utm_source=MWB-pdf-backend&utm_medium=MWB-pdf-ORG-backend&utm_campaign=MWB-backend
 * @since             1.0.0
 * @package           Pdf_Generator_For_Wp
 *
 * @wordpress-plugin
 * Plugin Name:       PDF Generator For Wp
 * Plugin URI:        http://wordpress.org/plugins/pdf-generator-for-wp/
 * Description:       Let your users download pages, posts, and products in PDF format using this plugin allowing you to add technical and marketing utility for your WordPress site.
 * Version:           1.0.3
 * Author:            MakeWebBetter
 * Author URI:        https://makewebbetter.com/?utm_source=MWB-pdf-backend&utm_medium=MWB-pdf-ORG-backend&utm_campaign=MWB-backend
 * Text Domain:       pdf-generator-for-wp
 * Domain Path:       /languages
 *
 * Requires at least:    4.6
 * Tested up to:         5.8.1
 * WC requires at least: 4.0.0
 * WC tested up to:      5.8.0
 * Stable tag:           1.0.3
 * Requires PHP:         7.2
 *
 * License:           GNU General Public License v3.0
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 */

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * Define plugin constants.
 *
 * @since 1.0.0
 */
function define_pdf_generator_for_wp_constants() {
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_VERSION', '1.0.3' );
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_DIR_PATH', plugin_dir_path( __FILE__ ) );
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_DIR_URL', plugin_dir_url( __FILE__ ) );
	pdf_generator_for_wp_constants( 'PDF_GENERATOR_FOR_WP_SERVER_URL', 'https://makewebbetter.com' );
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
	$mwb_pgfw_active_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_pgfw_active_plugin ) && ! empty( $mwb_pgfw_active_plugin ) ) {
		$mwb_pgfw_active_plugin['pdf-generator-for-wp'] = array(
			'plugin_name' => __( 'PDF Generator For WordPress', 'pdf-generator-for-wp' ),
			'active'      => '1',
		);
	} else {
		$mwb_pgfw_active_plugin                         = array();
		$mwb_pgfw_active_plugin['pdf-generator-for-wp'] = array(
			'plugin_name' => __( 'PDF Generator For WordPress', 'pdf-generator-for-wp' ),
			'active'      => '1',
		);
	}
	update_option( 'mwb_all_plugins_active', $mwb_pgfw_active_plugin );
}

/**
 * Update default values when new site is created.
 *
 * @param object $new_site current blog object.
 * @since 1.0.3
 * @return void
 */
function mwb_pgfw_new_site_created_options( $new_site ) {
	if ( ! function_exists( 'is_plugin_active_for_network' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}
	if ( is_plugin_active_for_network( 'pdf-generator-for-wp/pdf-generator-for-wp.php' ) ) {
		$blog_id = $new_site->blog_id;
		switch_to_blog( $blog_id );
		require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wp-activator.php';
		Pdf_Generator_For_Wp_Activator::pgfw_updating_default_settings_indb();
		restore_current_blog();
	}

}

add_action( 'wp_initialize_site', 'mwb_pgfw_new_site_created_options', 900 );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pdf-generator-for-wp-deactivator.php
 */
function deactivate_pdf_generator_for_wp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wp-deactivator.php';
	Pdf_Generator_For_Wp_Deactivator::pdf_generator_for_wp_deactivate();
	$mwb_pgfw_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_pgfw_deactive_plugin ) && ! empty( $mwb_pgfw_deactive_plugin ) ) {
		foreach ( $mwb_pgfw_deactive_plugin as $mwb_pgfw_deactive_key => $mwb_pgfw_deactive ) {
			if ( 'pdf-generator-for-wp' === $mwb_pgfw_deactive_key ) {
				$mwb_pgfw_deactive_plugin[ $mwb_pgfw_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'mwb_all_plugins_active', $mwb_pgfw_deactive_plugin );
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
	$GLOBALS['pgfw_mwb_pgfw_obj'] = $pgfw_plugin_standard;
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
		$my_link[] = '<a href="https://makewebbetter.com/product/wordpress-pdf-generator/?utm_source=MWB-pdf-org-backend&utm_medium=MWB-pdf-proORG-backend&utm_campaign=MWB-backend" target="_blank" class="mwb-pgfw-go-pro-link-backend">' . esc_html__( 'Go Pro', 'pdf-generator-for-wp' ) . '</a>';
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
		$links_array[] = '<a href="https://demo.makewebbetter.com/pdf-generator-for-wp/?utm_source=MWB-pdf-org-backend&utm_medium=MWB-demoORG-backend&utm_campaign=MWB-backend" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Demo.svg" class="mwb-info-img" alt="Demo image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Demo', 'pdf-generator-for-wp' ) . '</a>';
		$links_array[] = '<a href="https://docs.makewebbetter.com/pdf-generator-for-wp/?utm_source=MWB-pdf-org-backend&utm_medium=MWB-docORG-backend&utm_campaign=MWB-backend" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Documentation.svg" class="mwb-info-img" alt="documentation image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Documentation', 'pdf-generator-for-wp' ) . '</a>';
		$links_array[] = '<a href="https://makewebbetter.com/submit-query/" target="_blank"><img src="' . esc_html( PDF_GENERATOR_FOR_WP_DIR_URL ) . 'admin/src/images/Support.svg" class="mwb-info-img" alt="support image" style="width: 20px;height: 20px;padding-right:2px;">' . __( 'Support', 'pdf-generator-for-wp' ) . '</a>';
	}
	return $links_array;
}
add_filter( 'plugin_row_meta', 'pdf_generator_for_wp_custom_settings_at_plugin_tab', 10, 2 );
