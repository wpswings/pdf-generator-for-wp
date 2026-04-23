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

		register_rest_route(
			'pgfw-route/v1',
			'/tab-content',
			array(
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'wps_pgfw_tab_content' ),
				'permission_callback' => array( $this, 'wps_pgfw_tab_permission_check' ),
				'args'                => array(
					'tab' => array(
						'required' => true,
						'type'     => 'string',
						'pattern'  => '[a-zA-Z0-9\-]+',
					),
				),
			),
		);
	}

	/**
	 * Permission check for tab content endpoint.
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return bool|
	 */
	public function wps_pgfw_tab_permission_check( $request ) {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Return rendered tab HTML so the admin UI can switch without full reload.
	 *
	 * @param WP_REST_Request $request Request data.
	 * @return WP_REST_Response|WP_Error
	 */
	public function wps_pgfw_tab_content( $request ) {
		$tab = sanitize_key( $request->get_param( 'tab' ) );
		if ( empty( $tab ) ) {
			return new WP_Error( 'pgfw_invalid_tab', __( 'Invalid tab.', 'pdf-generator-for-wp' ), array( 'status' => 400 ) );
		}

		global $pgfw_wps_pgfw_obj;
		if ( empty( $pgfw_wps_pgfw_obj ) || ! method_exists( $pgfw_wps_pgfw_obj, 'wps_pgfw_plug_load_template' ) ) {
			return new WP_Error( 'pgfw_loader_missing', __( 'Unable to load tab content.', 'pdf-generator-for-wp' ), array( 'status' => 500 ) );
		}

		if ( method_exists( $pgfw_wps_pgfw_obj, 'wps_pgfw_normalize_dashboard_tab' ) ) {
			$tab = $pgfw_wps_pgfw_obj->wps_pgfw_normalize_dashboard_tab( $tab );
		}

		$pgfw_tab_content_path = 'admin/partials/' . $tab . '.php';

		// Ensure tab value is available to partials that read $_GET['pgfw_tab'] directly.
		$_GET['pgfw_tab'] = $tab; // phpcs:ignore WordPress.Security.NonceVerification

		ob_start();
		do_action( 'wps_pgfw_before_general_settings_form' );
		echo '<div class="pgfw-secion-wrap">';
		$pgfw_wps_pgfw_obj->wps_pgfw_plug_load_template( $pgfw_tab_content_path );
		echo '</div>';
		do_action( 'wps_pgfw_after_general_settings_form' );
		$html   = ob_get_clean();
		$header = method_exists( $pgfw_wps_pgfw_obj, 'wps_pgfw_get_dashboard_header_content' )
			? $pgfw_wps_pgfw_obj->wps_pgfw_get_dashboard_header_content( $tab )
			: array();

		return rest_ensure_response( array(
			'tab'    => $tab,
			'html'   => $html,
			'header' => $header,
		) );
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
