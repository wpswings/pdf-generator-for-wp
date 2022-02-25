<?php
/**
 * The file that defines the core plugin api class
 *
 * A class definition that includes api's endpoints and functions used across the plugin
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/package/rest-api/version1
 */

/**
 * The core plugin  api class.
 *
 * This is used to define internationalization, api-specific hooks, and
 * endpoints for plugin.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/package/rest-api/version1
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Pdf_Generator_For_Wp_Rest_Api {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin api.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the merthods, and set the hooks for the api and
	 *
	 * @since    1.0.0
	 * @param   string $plugin_name    Name of the plugin.
	 * @param   string $version        Version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}


	/**
	 * Define endpoints for the plugin.
	 *
	 * Uses the Pdf_Generator_For_Wp_Rest_Api class in order to create the endpoint
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function wps_pgfw_add_endpoint() {
		register_rest_route(
			'pgfw-route/v1',
			'/pgfw-dummy-data/',
			array(
				'methods'             => WP_REST_Server::CREATABLE,
				'callback'            => array( $this, 'wps_pgfw_default_callback' ),
				'permission_callback' => array( $this, 'wps_pgfw_default_permission_check' ),
			)
		);
	}


	/**
	 * Begins validation process of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $result   return rest response to server from where the endpoint hits.
	 * @since    1.0.0
	 */
	public function wps_pgfw_default_permission_check( $request ) {

		// Add rest api validation for each request.
		$result = true;
		return $result;
	}


	/**
	 * Begins execution of api endpoint.
	 *
	 * @param   Array $request    All information related with the api request containing in this array.
	 * @return  Array   $wps_pgfw_response   return rest response to server from where the endpoint hits.
	 * @since    1.0.0
	 */
	public function wps_pgfw_default_callback( $request ) {

		require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'package/rest-api/version1/class-pdf-generator-for-wp-api-process.php';
		$wps_pgfw_api_obj     = new Pdf_Generator_For_Wp_Api_Process();
		$wps_pgfw_resultsdata = $wps_pgfw_api_obj->wps_pgfw_default_process( $request );
		if ( is_array( $wps_pgfw_resultsdata ) && isset( $wps_pgfw_resultsdata['status'] ) && 200 == $wps_pgfw_resultsdata['status'] ) {
			unset( $wps_pgfw_resultsdata['status'] );
			$wps_pgfw_response = new WP_REST_Response( $wps_pgfw_resultsdata, 200 );
		} else {
			$wps_pgfw_response = new WP_Error( $wps_pgfw_resultsdata );
		}
		return $wps_pgfw_response;
	}
}
