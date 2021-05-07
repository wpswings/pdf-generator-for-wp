<?php
/**
 * The admin-specific on-boarding functionality of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package     pdf_generator_for_wordpress
 * @subpackage  pdf_generator_for_wordpress/includes
 */

/**
 * The Onboarding-specific functionality of the plugin admin side.
 *
 * @package     pdf_generator_for_wordpress
 * @subpackage  pdf_generator_for_wordpress/includes
 * @author      makewebbetter <webmaster@makewebbetter.com>
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if ( class_exists( 'Pdf_Generator_For_WordPress_Onboarding_Steps' ) ) {
	return;
}
/**
 * Define class and module for onboarding steps.
 */
class Pdf_Generator_For_WordPress_Onboarding_Steps {

	/**
	 * The single instance of the class.
	 *
	 * @since   1.0.0
	 * @var $_instance object of onboarding.
	 */
	protected static $_instance = null;

	/**
	 * Base url of hubspot api for pdf-generator-for-wordpress.
	 *
	 * @since 1.0.0
	 * @var string base url of API.
	 */
	private $mwb_pgfw_base_url = 'https://api.hsforms.com/';

	/**
	 * Portal id of hubspot api for pdf-generator-for-wordpress.
	 *
	 * @since 1.0.0
	 * @var string Portal id.
	 */
	private static $mwb_pgfw_portal_id = '6493626';

	/**
	 * Form id of hubspot api for pdf-generator-for-wordpress.
	 *
	 * @since 1.0.0
	 * @var string Form id.
	 */
	private static $mwb_pgfw_onboarding_form_id = 'd94dcb10-c9c1-4155-a9ad-35354f2c3b52';

	/**
	 * Form id of hubspot api for pdf-generator-for-wordpress.
	 *
	 * @since 1.0.0
	 * @var string Form id.
	 */
	private static $mwb_pgfw_deactivation_form_id = '329ffc7a-0e8c-4e11-8b41-960815c31f8d';

	/**
	 * Define some variables for pdf-generator-for-wordpress.
	 *
	 * @since 1.0.0
	 * @var string $mwb_pgfw_plugin_name plugin name.
	 */
	private static $mwb_pgfw_plugin_name;

	/**
	 * Define some variables for pdf-generator-for-wordpress.
	 *
	 * @since 1.0.0
	 * @var string $mwb_pgfw_plugin_name_label plugin name text.
	 */
	private static $mwb_pgfw_plugin_name_label;

	/**
	 * Define some variables for pdf-generator-for-wordpress.
	 *
	 * @var string $mwb_pgfw_store_name store name.
	 * @since 1.0.0
	 */
	private static $mwb_pgfw_store_name;

	/**
	 * Define some variables for pdf-generator-for-wordpress.
	 *
	 * @since 1.0.0
	 * @var string $mwb_pgfw_store_url store url.
	 */
	private static $mwb_pgfw_store_url;

	/**
	 * Define the onboarding functionality of the plugin.
	 *
	 * Set the plugin name and the store name and store url that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		self::$mwb_pgfw_store_name        = get_bloginfo( 'name' );
		self::$mwb_pgfw_store_url         = home_url();
		self::$mwb_pgfw_plugin_name       = 'PDF Generator For WordPress';
		self::$mwb_pgfw_plugin_name_label = 'PDF Generator For WordPress';

		add_action( 'admin_enqueue_scripts', array( $this, 'mwb_pgfw_onboarding_enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mwb_pgfw_onboarding_enqueue_scripts' ) );
		add_action( 'admin_footer', array( $this, 'mwb_pgfw_add_onboarding_popup_screen' ) );
		add_action( 'admin_footer', array( $this, 'mwb_pgfw_add_deactivation_popup_screen' ) );

		add_filter( 'mwb_pgfw_on_boarding_form_fields', array( $this, 'mwb_pgfw_add_on_boarding_form_fields' ) );
		add_filter( 'mwb_pgfw_deactivation_form_fields', array( $this, 'mwb_pgfw_add_deactivation_form_fields' ) );

		// Ajax to send data.
		add_action( 'wp_ajax_mwb_pgfw_send_onboarding_data', array( $this, 'mwb_pgfw_send_onboarding_data' ) );
		add_action( 'wp_ajax_nopriv_mwb_pgfw_send_onboarding_data', array( $this, 'mwb_pgfw_send_onboarding_data' ) );

		// Ajax to Skip popup.
		add_action( 'wp_ajax_pgfw_skip_onboarding_popup', array( $this, 'mwb_pgfw_skip_onboarding_popup' ) );
		add_action( 'wp_ajax_nopriv_pgfw_skip_onboarding_popup', array( $this, 'mwb_pgfw_skip_onboarding_popup' ) );

	}

	/**
	 * Main Onboarding steps Instance.
	 *
	 * Ensures only one instance of Onboarding functionality is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @return Onboarding Steps - Main instance.
	 */
	public static function get_instance() {

		if ( is_null( self::$_instance ) ) {

			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Makewebbetter_Onboarding_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Makewebbetter_Onboarding_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	public function mwb_pgfw_onboarding_enqueue_styles() {
		global $pagenow;
		$is_valid = false;
		if ( ! $is_valid && 'plugins.php' == $pagenow ) {
			$is_valid = true;
		}
		if ( $this->mwb_pgfw_valid_page_screen_check() || $is_valid ) {
			// comment the line of code Only when your plugin doesn't uses the Select2.
			wp_enqueue_style( 'mwb-pgfw-onboarding-select2-style', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/select-2/pdf-generator-for-wordpress-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-pgfw-meterial-css', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-pgfw-meterial-css2', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-pgfw-meterial-lite', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-pgfw-meterial-icons-css', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-pgfw-onboarding-style', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'onboarding/css/pdf-generator-for-wordpress-onboarding.css', array(), time(), 'all' );

		}
	}

	/**
	 * This function is provided for demonstration purposes only.
	 *
	 * An instance of this class should be passed to the run() function
	 * defined in Makewebbetter_Onboarding_Loader as all of the hooks are defined
	 * in that particular class.
	 *
	 * The Makewebbetter_Onboarding_Loader will then create the relationship
	 * between the defined hooks and the functions defined in this
	 * class.
	 */
	public function mwb_pgfw_onboarding_enqueue_scripts() {
		global $pagenow;
		$is_valid = false;
		if ( ! $is_valid && 'plugins.php' == $pagenow ) {
			$is_valid = true;
		}
		if ( $this->mwb_pgfw_valid_page_screen_check() || $is_valid ) {

			wp_enqueue_script( 'mwb-pgfw-onboarding-select2-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/select-2/pdf-generator-for-wordpress-select2.js', array( 'jquery' ), '1.0.0', false );

			wp_enqueue_script( 'mwb-pgfw-metarial-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-pgfw-metarial-js2', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-pgfw-metarial-lite', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_enqueue_script( 'mwb-pgfw-onboarding-scripts', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'onboarding/js/pdf-generator-for-wordpress-onboarding.js', array( 'jquery', 'mwb-pgfw-onboarding-select2-js', 'mwb-pgfw-metarial-js', 'mwb-pgfw-metarial-js2', 'mwb-pgfw-metarial-lite' ), time(), true );

			$pgfw_current_slug = ! empty( explode( '/', plugin_basename( __FILE__ ) ) ) ? explode( '/', plugin_basename( __FILE__ ) )[0] : '';
			wp_localize_script(
				'mwb-pgfw-onboarding-scripts',
				'mwb_pgfw_onboarding',
				array(
					'ajaxurl'                     => admin_url( 'admin-ajax.php' ),
					'pgfw_auth_nonce'             => wp_create_nonce( 'mwb_pgfw_onboarding_nonce' ),
					'pgfw_current_screen'         => $pagenow,
					'pgfw_current_supported_slug' => apply_filters( 'mwb_pgfw_deactivation_supported_slug', array( $pgfw_current_slug ) ),
				)
			);
		}
	}

	/**
	 * Get all valid screens to add scripts and templates for pdf-generator-for-wordpress.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_add_onboarding_popup_screen() {
		if ( $this->mwb_pgfw_valid_page_screen_check() && $this->mwb_pgfw_show_onboarding_popup_check() ) {
			require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'onboarding/templates/pdf-generator-for-wordpress-onboarding-template.php';
		}
	}

	/**
	 * Get all valid screens to add scripts and templates for pdf-generator-for-wordpress.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_add_deactivation_popup_screen() {

		global $pagenow;
		if ( ! empty( $pagenow ) && 'plugins.php' == $pagenow ) {
			require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'onboarding/templates/pdf-generator-for-wordpress-deactivation-template.php';
		}
	}

	/**
	 * Skip the popup for some days of pdf-generator-for-wordpress.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_skip_onboarding_popup() {

		$get_skipped_timstamp = update_option( 'mwb_pgfw_onboarding_data_skipped', time() );
		echo wp_json_encode( 'true' );
		wp_die();
	}


	/**
	 * Add your pdf-generator-for-wordpress onboarding form fields.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_add_on_boarding_form_fields() {

		$current_user = wp_get_current_user();
		if ( ! empty( $current_user ) ) {
			$current_user_email = $current_user->user_email ? $current_user->user_email : '';
		}

		if ( function_exists( 'get_woocommerce_currency_symbol' ) ) {
			$currency_symbol = get_woocommerce_currency_symbol();
		} else {
			$currency_symbol = '$';
		}

		/**
		 * Do not repeat id index.
		 */

		$fields = array(

			/**
			 * Input field with label.
			 * Radio field with label ( select only one ).
			 * Radio field with label ( select multiple one ).
			 * Checkbox radio with label ( select only one ).
			 * Checkbox field with label ( select multiple one ).
			 * Only Label ( select multiple one ).
			 * Select field with label ( select only one ).
			 * Select2 field with label ( select multiple one ).
			 * Email field with label. ( auto filled with admin email )
			 */

			rand() => array(
				'id'          => 'mwb-pgfw-monthly-revenue',
				'title'       => esc_html__( 'What is your monthly revenue?', 'pdf-generator-for-wordpress' ),
				'type'        => 'radio',
				'description' => '',
				'name'        => 'monthly_revenue_',
				'value'       => '',
				'multiple'    => 'no',
				'placeholder' => '',
				'required'    => 'yes',
				'class'       => '',
				'options'     => array(
					'0-500'      => $currency_symbol . '0-' . $currency_symbol . '500',
					'501-5000'   => $currency_symbol . '501-' . $currency_symbol . '5000',
					'5001-10000' => $currency_symbol . '5001-' . $currency_symbol . '10000',
					'10000+'     => $currency_symbol . '10000+',
				),
			),

			rand() => array(
				'id'          => 'mwb_pgfw_industry_type',
				'title'       => esc_html__( 'What industry defines your business?', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'name'        => 'industry_type_',
				'value'       => '',
				'description' => '',
				'multiple'    => 'yes',
				'placeholder' => esc_html__( 'Industry Type', 'pdf-generator-for-wordpress' ),
				'required'    => 'yes',
				'class'       => '',
				'options'     => array(
					'agency'                  => 'Agency',
					'consumer-services'       => 'Consumer Services',
					'ecommerce'               => 'Ecommerce',
					'financial-services'      => 'Financial Services',
					'healthcare'              => 'Healthcare',
					'manufacturing'           => 'Manufacturing',
					'nonprofit-and-education' => 'Nonprofit and Education',
					'professional-services'   => 'Professional Services',
					'real-estate'             => 'Real Estate',
					'software'                => 'Software',
					'startups'                => 'Startups',
					'restaurant'              => 'Restaurant',
					'fitness'                 => 'Fitness',
					'jewelry'                 => 'Jewelry',
					'beauty'                  => 'Beauty',
					'celebrity'               => 'Celebrity',
					'gaming'                  => 'Gaming',
					'government'              => 'Government',
					'sports'                  => 'Sports',
					'retail-store'            => 'Retail Store',
					'travel'                  => 'Travel',
					'political-campaign'      => 'Political Campaign',
				),
			),

			rand() => array(
				'id'          => 'mwb-pgfw-onboard-email',
				'title'       => esc_html__( 'What is the best email address to contact you?', 'pdf-generator-for-wordpress' ),
				'type'        => 'email',
				'description' => '',
				'name'        => 'email',
				'placeholder' => esc_html__( 'Email', 'pdf-generator-for-wordpress' ),
				'value'       => $current_user_email,
				'required'    => 'yes',
				'class'       => 'pgfw-text-class',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-onboard-number',
				'title'       => esc_html__( 'What is your contact number?', 'pdf-generator-for-wordpress' ),
				'type'        => 'text',
				'description' => '',
				'name'        => 'phone',
				'value'       => '',
				'placeholder' => esc_html__( 'Contact Number', 'pdf-generator-for-wordpress' ),
				'required'    => 'yes',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-store-name',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'name'        => 'company',
				'placeholder' => '',
				'value'       => self::$mwb_pgfw_store_name,
				'required'    => '',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-store-url',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'name'        => 'website',
				'placeholder' => '',
				'value'       => self::$mwb_pgfw_store_url,
				'required'    => '',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-show-counter',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'placeholder' => '',
				'name'        => 'mwb-pgfw-show-counter',
				'value'       => get_option( 'mwb_pgfw_onboarding_data_sent', 'not-sent' ),
				'required'    => '',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-plugin-name',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'placeholder' => '',
				'name'        => 'org_plugin_name',
				'value'       => self::$mwb_pgfw_plugin_name,
				'required'    => '',
				'class'       => '',
			),
		);

		return $fields;
	}


	/**
	 * Add your pdf-generator-for-wordpress deactivation form fields.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_add_deactivation_form_fields() {

		$current_user = wp_get_current_user();
		if ( ! empty( $current_user ) ) {
			$current_user_email = $current_user->user_email ? $current_user->user_email : '';
		}

		/**
		 * Do not repeat id index.
		 */

		$fields = array(

			/**
			 * Input field with label.
			 * Radio field with label ( select only one ).
			 * Radio field with label ( select multiple one ).
			 * Checkbox radio with label ( select only one ).
			 * Checkbox field with label ( select multiple one ).
			 * Only Label ( select multiple one ).
			 * Select field with label ( select only one ).
			 * Select2 field with label ( select multiple one ).
			 * Email field with label. ( auto filled with admin email )
			 */

			rand() => array(
				'id'          => 'mwb-pgfw-deactivation-reason',
				'title'       => '',
				'description' => '',
				'type'        => 'radio',
				'placeholder' => '',
				'name'        => 'plugin_deactivation_reason',
				'value'       => '',
				'multiple'    => 'no',
				'required'    => 'yes',
				'class'       => 'pgfw-radio-class',
				'options'     => array(
					'temporary-deactivation-for-debug' => 'It is a temporary deactivation. I am just debugging an issue.',
					'site-layout-broke'                => 'The plugin broke my layout or some functionality.',
					'complicated-configuration'        => 'The plugin is too complicated to configure.',
					'no-longer-need'                   => 'I no longer need the plugin',
					'found-better-plugin'              => 'I found a better plugin',
					'other'                            => 'Other',
				),
			),

			rand() => array(
				'id'          => 'mwb-pgfw-deactivation-reason-text',
				'title'       => esc_html__( 'Let us know why you are deactivating ' . self::$mwb_pgfw_plugin_name_label . ' so we can improve the plugin', 'pdf-generator-for-wordpress' ),
				'type'        => 'textarea',
				'description' => '',
				'name'        => 'deactivation_reason_text',
				'placeholder' => esc_html__( 'Reason', 'pdf-generator-for-wordpress' ),
				'value'       => '',
				'required'    => '',
				'class'       => 'mwb-keep-hidden',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-admin-email',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'name'        => 'email',
				'placeholder' => '',
				'value'       => $current_user_email,
				'required'    => '',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-store-name',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'placeholder' => '',
				'name'        => 'company',
				'value'       => self::$mwb_pgfw_store_name,
				'required'    => '',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-store-url',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'name'        => 'website',
				'placeholder' => '',
				'value'       => self::$mwb_pgfw_store_url,
				'required'    => '',
				'class'       => '',
			),

			rand() => array(
				'id'          => 'mwb-pgfw-plugin-name',
				'title'       => '',
				'description' => '',
				'type'        => 'hidden',
				'placeholder' => '',
				'name'        => 'org_plugin_name',
				'value'       => '',
				'required'    => '',
				'class'       => '',
			),
		);

		return $fields;
	}


	/**
	 * Send the data to Hubspot crm.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_send_onboarding_data() {

		check_ajax_referer( 'mwb_pgfw_onboarding_nonce', 'nonce' );

		$form_data = ! empty( $_POST['form_data'] ) ? json_decode( sanitize_text_field( wp_unslash( $_POST['form_data'] ) ) ) : '';

		$formatted_data = array();

		if ( ! empty( $form_data ) && is_array( $form_data ) ) {

			foreach ( $form_data as $key => $input ) {

				if ( 'mwb-pgfw-show-counter' == $input->name ) {
					continue;
				}

				if ( false !== strrpos( $input->name, '[]' ) ) {

					$new_key = str_replace( '[]', '', $input->name );
					$new_key = str_replace( '"', '', $new_key );

					array_push(
						$formatted_data,
						array(
							'name'  => $new_key,
							'value' => $input->value,
						)
					);

				} else {

					$input->name = str_replace( '"', '', $input->name );

					array_push(
						$formatted_data,
						array(
							'name'  => $input->name,
							'value' => $input->value,
						)
					);
				}
			}
		}

		try {

			$found = current(
				array_filter(
					$formatted_data,
					function( $item ) {
						return isset( $item['name'] ) && 'plugin_deactivation_reason' == $item['name'];
					}
				)
			);

			if ( ! empty( $found ) ) {
				$action_type = 'deactivation';
			} else {
				$action_type = 'onboarding';
			}

			if ( ! empty( $formatted_data ) && is_array( $formatted_data ) ) {

				unset( $formatted_data['mwb-pgfw-show-counter'] );

				$result = $this->mwb_pgfw_handle_form_submission_for_hubspot( $formatted_data, $action_type );
			}
		} catch ( Exception $e ) {

			echo wp_json_encode( $e->getMessage() );
			wp_die();
		}

		if ( ! empty( $action_type ) && 'onboarding' == $action_type ) {
			$get_skipped_timstamp = update_option( 'mwb_pgfw_onboarding_data_sent', 'sent' );
		}

		echo wp_json_encode( $formatted_data );
		wp_die();
	}


	/**
	 * Handle pdf-generator-for-wordpress form submission.
	 *
	 * @param  array  $submission  The resultant data of the form.
	 * @param  string $action_type  Type of action.
	 * @since    1.0.0
	 */
	protected function mwb_pgfw_handle_form_submission_for_hubspot( $submission = false, $action_type = 'onboarding' ) {

		if ( 'onboarding' == $action_type ) {
			array_push(
				$submission,
				array(
					'name'  => 'currency',
					'value' => get_woocommerce_currency(),
				)
			);
		}

		$result = $this->mwb_pgfw_hubwoo_submit_form( $submission, $action_type );

		if ( true == $result['success'] ) {
			return true;
		} else {
			return false;
		}
	}


	/**
	 *  Define pdf-generator-for-wordpress Onboarding Submission :: Get a form.
	 *
	 * @param      array  $form_data    form data.
	 * @param      string $action_type    type of action.
	 * @since       1.0.0
	 */
	protected function mwb_pgfw_hubwoo_submit_form( $form_data = array(), $action_type = 'onboarding' ) {

		if ( 'onboarding' == $action_type ) {
			$form_id = self::$mwb_pgfw_onboarding_form_id;
		} else {
			$form_id = self::$mwb_pgfw_deactivation_form_id;
		}

		$url = 'submissions/v3/integration/submit/' . self::$mwb_pgfw_portal_id . '/' . $form_id;

		$headers = array(
			'Content-Type: application/json',
		);

		$form_data = wp_json_encode(
			array(
				'fields'  => $form_data,
				'context' => array(
					'pageUri'   => self::$mwb_pgfw_store_url,
					'pageName'  => self::$mwb_pgfw_store_name,
					'ipAddress' => $this->mwb_pgfw_get_client_ip(),
				),
			)
		);

		$response = $this->mwb_pgfw_hic_post( $url, $form_data, $headers );
		if ( 200 == $response['status_code'] ) {
			$result            = json_decode( $response['response'], true );
			$result['success'] = true;
		} else {
			$result = $response;
		}

		return $result;
	}
	/**
	 * Handle Hubspot POST api calls.
	 *
	 * @since    1.0.0
	 * @param   string $endpoint   Url where the form data posted.
	 * @param   array  $post_params    form data that need to be send.
	 * @param   array  $headers    data that must be included in header for request.
	 */
	private function mwb_pgfw_hic_post( $endpoint, $post_params, $headers ) {
		$url      = $this->mwb_pgfw_base_url . $endpoint;
		$request  = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.0',
			'blocking'    => true,
			'headers'     => $headers,
			'body'        => $post_params,
			'cookies'     => array(),
		);
		$response = wp_remote_post( $url, $request );
		if ( is_wp_error( $response ) ) {
			$status_code = 500;
			$response    = esc_html__( 'Unexpected Error Occured', 'invoice-system-for-woocommerce' );
			$curl_errors = $response;
		} else {
			$response    = wp_remote_retrieve_body( $response );
			$status_code = wp_remote_retrieve_response_code( $response );
			$curl_errors = $response;
		}
		return array(
			'status_code' => $status_code,
			'response'    => $response,
			'errors'      => $curl_errors,
		);
	}


	/**
	 * Function to get the client IP address.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_get_client_ip() {
		$ipaddress = '';
		if ( getenv( 'HTTP_CLIENT_IP' ) ) {
			$ipaddress = getenv( 'HTTP_CLIENT_IP' );
		} elseif ( getenv( 'HTTP_X_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_X_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_X_FORWARDED' );
		} elseif ( getenv( 'HTTP_FORWARDED_FOR' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED_FOR' );
		} elseif ( getenv( 'HTTP_FORWARDED' ) ) {
			$ipaddress = getenv( 'HTTP_FORWARDED' );
		} elseif ( getenv( 'REMOTE_ADDR' ) ) {
			$ipaddress = getenv( 'REMOTE_ADDR' );
		} else {
			$ipaddress = 'UNKNOWN';
		}
		return $ipaddress;
	}

	/**
	 * Validate the popup to be shown on specific screen.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_valid_page_screen_check() {
		$mwb_pgfw_screen  = get_current_screen();
		$mwb_pgfw_is_flag = false;
		if ( isset( $mwb_pgfw_screen->id ) && 'makewebbetter_page_pdf_generator_for_wordpress_menu' == $mwb_pgfw_screen->id ) {
			$mwb_pgfw_is_flag = true;
		}

		return $mwb_pgfw_is_flag;
	}

	/**
	 * Show the popup based on condition.
	 *
	 * @since    1.0.0
	 */
	public function mwb_pgfw_show_onboarding_popup_check() {

		$mwb_pgfw_is_already_sent = get_option( 'mwb_pgfw_onboarding_data_sent', false );

		// Already submitted the data.
		if ( ! empty( $mwb_pgfw_is_already_sent ) && 'sent' == $mwb_pgfw_is_already_sent ) {
			return false;
		}

		$mwb_pgfw_get_skipped_timstamp = get_option( 'mwb_pgfw_onboarding_data_skipped', false );
		if ( ! empty( $mwb_pgfw_get_skipped_timstamp ) ) {

			$mwb_pgfw_next_show = strtotime( '+2 days', $mwb_pgfw_get_skipped_timstamp );

			$mwb_pgfw_current_time = time();

			$mwb_pgfw_time_diff = $mwb_pgfw_next_show - $mwb_pgfw_current_time;

			if ( 0 < $mwb_pgfw_time_diff ) {
				return false;
			}
		}

		// By default Show.
		return true;
	}
}
