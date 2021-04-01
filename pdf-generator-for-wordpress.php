<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://makewebbetter.com/
 * @since             1.0.0
 * @package           Pdf_Generator_For_Wordpress
 *
 * @wordpress-plugin
 * Plugin Name:       PDF Generator For WordPress
 * Plugin URI:        https://makewebbetter.com/product/pdf-generator-for-wordpress/
 * Description:       generates pdf for various post types in WordPress.
 * Version:           1.0.0
 * Author:            makewebbetter
 * Author URI:        https://makewebbetter.com/
 * Text Domain:       pdf-generator-for-wordpress
 * Domain Path:       /languages
 *
 * Requires at least: 4.6
 * Tested up to:      4.9.5
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
 * @since             1.0.0
 */
function define_pdf_generator_for_wordpress_constants() {

	pdf_generator_for_wordpress_constants( 'PDF_GENERATOR_FOR_WORDPRESS_VERSION', '1.0.0' );
	pdf_generator_for_wordpress_constants( 'PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH', plugin_dir_path( __FILE__ ) );
	pdf_generator_for_wordpress_constants( 'PDF_GENERATOR_FOR_WORDPRESS_DIR_URL', plugin_dir_url( __FILE__ ) );
	pdf_generator_for_wordpress_constants( 'PDF_GENERATOR_FOR_WORDPRESS_SERVER_URL', 'https://makewebbetter.com' );
	pdf_generator_for_wordpress_constants( 'PDF_GENERATOR_FOR_WORDPRESS_ITEM_REFERENCE', 'PDF Generator For WordPress' );
}


/**
 * Callable function for defining plugin constants.
 *
 * @param   String $key    Key for contant.
 * @param   String $value   value for contant.
 * @since             1.0.0
 */
function pdf_generator_for_wordpress_constants( $key, $value ) {

	if ( ! defined( $key ) ) {

		define( $key, $value );
	}
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pdf-generator-for-wordpress-activator.php
 */
function activate_pdf_generator_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wordpress-activator.php';
	Pdf_Generator_For_WordPress_Activator::pdf_generator_for_wordpress_activate();
	$mwb_pgfw_active_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_pgfw_active_plugin ) && ! empty( $mwb_pgfw_active_plugin ) ) {
		$mwb_pgfw_active_plugin['pdf-generator-for-wordpress'] = array(
			'plugin_name' => __( 'PDF Generator For WordPress', 'pdf-generator-for-wordpress' ),
			'active'      => '1',
		);
	} else {
		$mwb_pgfw_active_plugin                                = array();
		$mwb_pgfw_active_plugin['pdf-generator-for-wordpress'] = array(
			'plugin_name' => __( 'PDF Generator For WordPress', 'pdf-generator-for-wordpress' ),
			'active'      => '1',
		);
	}
	update_option( 'mwb_all_plugins_active', $mwb_pgfw_active_plugin );
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pdf-generator-for-wordpress-deactivator.php
 */
function deactivate_pdf_generator_for_wordpress() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wordpress-deactivator.php';
	Pdf_Generator_For_WordPress_Deactivator::pdf_generator_for_wordpress_deactivate();
	$mwb_pgfw_deactive_plugin = get_option( 'mwb_all_plugins_active', false );
	if ( is_array( $mwb_pgfw_deactive_plugin ) && ! empty( $mwb_pgfw_deactive_plugin ) ) {
		foreach ( $mwb_pgfw_deactive_plugin as $mwb_pgfw_deactive_key => $mwb_pgfw_deactive ) {
			if ( 'pdf-generator-for-wordpress' === $mwb_pgfw_deactive_key ) {
				$mwb_pgfw_deactive_plugin[ $mwb_pgfw_deactive_key ]['active'] = '0';
			}
		}
	}
	update_option( 'mwb_all_plugins_active', $mwb_pgfw_deactive_plugin );
}

register_activation_hook( __FILE__, 'activate_pdf_generator_for_wordpress' );
register_deactivation_hook( __FILE__, 'deactivate_pdf_generator_for_wordpress' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-pdf-generator-for-wordpress.php';


/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_pdf_generator_for_wordpress() {
	define_pdf_generator_for_wordpress_constants();

	$pgfw_plugin_standard = new Pdf_Generator_For_WordPress();
	$pgfw_plugin_standard->pgfw_run();
	$GLOBALS['pgfw_mwb_pgfw_obj'] = $pgfw_plugin_standard;

}
run_pdf_generator_for_wordpress();


// Add settings link on plugin page.
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'pdf_generator_for_wordpress_settings_link' );

/**
 * Settings link.
 *
 * @since    1.0.0
 * @param   Array $links    Settings link array.
 */
function pdf_generator_for_wordpress_settings_link( $links ) {

	$my_link = array(
		'<a href="' . admin_url( 'admin.php?page=pdf_generator_for_wordpress_menu' ) . '">' . __( 'Settings', 'pdf-generator-for-wordpress' ) . '</a>',
	);
	return array_merge( $my_link, $links );
}
