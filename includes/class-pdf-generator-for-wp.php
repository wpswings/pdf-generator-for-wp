<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Pdf_Generator_For_Wp {


	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @var      Pdf_Generator_For_Wp_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $pgfw_onboard    To initializsed the object of class onboard.
	 */
	protected $pgfw_onboard;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area,
	 * the public-facing side of the site and common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PDF_GENERATOR_FOR_WP_VERSION' ) ) {

			$this->version = PDF_GENERATOR_FOR_WP_VERSION;
		} else {

			$this->version = '1.6.3';
		}

		$this->plugin_name = 'pdf-generator-for-wp';

		$this->pdf_generator_for_wp_dependencies();
		
		if ( $this->pdf_generator_for_wp_should_load_admin_context() ) {
			$this->pdf_generator_for_wp_admin_hooks();
		} else {
			$this->pdf_generator_for_wp_public_hooks();
		}
		$this->pdf_generator_for_wp_common_hooks();

		$this->pdf_generator_for_wp_api_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Pdf_Generator_For_Wp_Loader. Orchestrates the hooks of the plugin.
	 * - Pdf_Generator_For_Wp_i18n. Defines internationalization functionality.
	 * - Pdf_Generator_For_Wp_Admin. Defines all hooks for the admin area.
	 * - Pdf_Generator_For_Wp_Common. Defines all hooks for the common area.
	 * - Pdf_Generator_For_Wp_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function pdf_generator_for_wp_dependencies() {
		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'includes/class-pdf-generator-for-wp-loader.php';

		
		if ( $this->pdf_generator_for_wp_should_load_admin_context() ) {

			// The class responsible for defining all actions that occur in the admin area.
			require_once plugin_dir_path( __DIR__ ) . 'admin/class-pdf-generator-for-wp-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir( plugin_dir_path( __DIR__ ) . 'onboarding' ) && ! class_exists( 'Pdf_Generator_For_Wp_Onboarding_Steps' ) ) {
				require_once plugin_dir_path( __DIR__ ) . 'includes/class-pdf-generator-for-wp-onboarding-steps.php';
			}

			if ( ! class_exists( 'Pdf_Generator_For_Wp_Talk_To_Expert_Form' ) ) {
				require_once plugin_dir_path( __DIR__ ) . 'includes/class-pdf-generator-for-wp-talk-to-expert-form.php';
			}

			if ( class_exists( 'Pdf_Generator_For_Wp_Onboarding_Steps' ) ) {
				$pgfw_onboard_steps = new Pdf_Generator_For_Wp_Onboarding_Steps();
			}

			if ( class_exists( 'Pdf_Generator_For_Wp_Talk_To_Expert_Form' ) ) {
				$pgfw_talk_to_expert_form = new Pdf_Generator_For_Wp_Talk_To_Expert_Form();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			require_once plugin_dir_path( __DIR__ ) . 'public/class-pdf-generator-for-wp-public.php';
		}

		require_once plugin_dir_path( __DIR__ ) . 'package/rest-api/class-pdf-generator-for-wp-rest-api.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( __DIR__ ) . 'common/class-pdf-generator-for-wp-common.php';
		$this->loader = new Pdf_Generator_For_Wp_Loader();
	}

	/**
	 * Determine whether the current request needs admin-side plugin hooks.
	 *
	 * Tab content is loaded through the REST API from the admin screen, so those
	 * requests also need the admin field filters registered.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	private function pdf_generator_for_wp_should_load_admin_context() {
		return is_admin() || ( defined( 'REST_REQUEST' ) && REST_REQUEST );
	}


	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function pdf_generator_for_wp_admin_hooks() {
		$pgfw_plugin_admin = new Pdf_Generator_For_Wp_Admin( $this->pgfw_get_plugin_name(), $this->pgfw_get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $pgfw_plugin_admin, 'pgfw_admin_enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $pgfw_plugin_admin, 'pgfw_admin_enqueue_scripts' );

		// Add settings menu for PDF Generator For WordPress.
		$this->loader->add_action( 'admin_menu', $pgfw_plugin_admin, 'pgfw_options_page' );
		$this->loader->add_action( 'admin_menu', $pgfw_plugin_admin, 'wps_pgfw_remove_default_submenu', 50 );

		// All admin actions and filters after License Validation goes here.
		// Adding sub menu page.
		$this->loader->add_filter( 'wps_add_plugins_menus_array', $pgfw_plugin_admin, 'pgfw_admin_submenu_page', 15 );

		// Fields for general settings tab.
		$this->loader->add_filter( 'pgfw_general_settings_array', $pgfw_plugin_admin, 'pgfw_admin_general_settings_page', 10 );
		// Fields for display setting tab.
		$this->loader->add_filter( 'pgfw_display_settings_array', $pgfw_plugin_admin, 'pgfw_admin_display_settings_page', 10 );
		// Fields for header customizations.
		$this->loader->add_filter( 'pgfw_header_settings_array', $pgfw_plugin_admin, 'pgfw_admin_header_settings_page', 10 );
		// Fields for footer customizations.
		$this->loader->add_filter( 'pgfw_footer_settings_array', $pgfw_plugin_admin, 'pgfw_admin_footer_settings_page', 10 );
		// Fields for body customizations.
		$this->loader->add_filter( 'pgfw_body_settings_array', $pgfw_plugin_admin, 'pgfw_admin_body_settings_page', 10 );
		// Fields for advanced settings.
		$this->loader->add_filter( 'pgfw_advanced_settings_array', $pgfw_plugin_admin, 'pgfw_admin_advanced_settings_page', 10 );
		// Fields for meta fields settings.
		$this->loader->add_filter( 'pgfw_meta_fields_settings_array', $pgfw_plugin_admin, 'pgfw_admin_meta_fields_settings_page', 10 );
		// Fields for PDF upload settings.
		$this->loader->add_filter( 'pgfw_pdf_upload_fields_settings_array', $pgfw_plugin_admin, 'pgfw_admin_pdf_upload_settings_page', 10 );
		// Request handling for saving general settings.
		$this->loader->add_action( 'admin_init', $pgfw_plugin_admin, 'pgfw_admin_save_tab_settings' );
		// Deleting media from table by media ID.
		$this->loader->add_action( 'wp_ajax_wps_pgfw_delete_poster_by_media_id_from_table', $pgfw_plugin_admin, 'wps_pgfw_delete_poster_by_media_id_from_table' );
		// schedular fo deleting documents form server.
		$this->loader->add_action( 'init', $pgfw_plugin_admin, 'pgfw_delete_pdf_form_server_scheduler' );
		$this->loader->add_action( 'pgfw_cron_delete_pdf_from_server', $pgfw_plugin_admin, 'pgfw_delete_pdf_from_server' );
		// Reset all the settings to default.
		$this->loader->add_action( 'wp_ajax_pgfw_reset_default_settings', $pgfw_plugin_admin, 'pgfw_reset_default_settings' );

		$this->loader->add_action( 'wp_ajax_wpg_ajax_callbacks', $pgfw_plugin_admin, 'wps_wpg_ajax_callbacks' );
		$this->loader->add_filter( 'wps_pgfw_custom_page_size_filter_hook', $pgfw_plugin_admin, 'wpg_custom_page_size_in_dropdown' );
		$this->loader->add_action( 'admin_init', $pgfw_plugin_admin, 'wps_pgfw_set_cron_for_plugin_notification' );
		$this->loader->add_action( 'wps_wgm_check_for_notification_update', $pgfw_plugin_admin, 'wps_pgfw_save_notice_message' );
		$this->loader->add_action( 'wp_ajax_wps_pgfw_dismiss_notice_banner', $pgfw_plugin_admin, 'wps_pgfw_dismiss_notice_banner_callback' );

		$this->loader->add_action( 'init', $pgfw_plugin_admin, 'register_google_embed_blocks' );

		// PRO PLUGIN DUMMY CONTENT HTML FUNCTIONS  ////////////.
		if ( ! wps_pgfw_is_pdf_pro_plugin_active() ) {
			$this->loader->add_filter( 'pgfw_taxonomy_settings_array_dummy', $pgfw_plugin_admin, 'pgfw_setting_fields_for_customising_taxonomy_dummy' );
			$this->loader->add_action( 'pgfw_plugin_standard_admin_settings_sub_tabs_dummy', $pgfw_plugin_admin, 'pgfw_add_custom_template_settings_tab_dummy' );
			$this->loader->add_filter( 'pgfw_template_pdf_settings_array_dummy', $pgfw_plugin_admin, 'pgfw_template_pdf_settings_page_dummy', 10 );
			$this->loader->add_filter( 'pgfw_template_invoice_settings_array_dummy', $pgfw_plugin_admin, 'pgfw_template_invoice_setting_html_fields_dummy' );
			$this->loader->add_filter( 'pgfw_layout_cover_page_setting_html_array_dummy', $pgfw_plugin_admin, 'pgfw_cover_page_html_layout_fields_dummy' );
		}

		$this->loader->add_action( 'wp_ajax_wps_pgfw_save_embed_source', $pgfw_plugin_admin, 'wps_pgfw_save_embed_source_callback' );

		/* Functionality related to Flipbook    */
		$this->loader->add_action( 'add_meta_boxes', $pgfw_plugin_admin, 'wps_pgfw_add_flipbook_metabox_callback', 10, 1 );
		$this->loader->add_action( 'save_post_flipbook', $pgfw_plugin_admin, 'wps_pgfw_save_flipbook_metabox_callback', 10, 1 );
		$this->loader->add_filter( 'manage_flipbook_posts_columns', $pgfw_plugin_admin, 'wps_pgfw_manage_flipbook_posts_columns', 10, 1 );
		$this->loader->add_action( 'manage_flipbook_posts_custom_column', $pgfw_plugin_admin, 'wps_pgfw_flipbook_posts_custom_column', 10, 2 );
	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function pdf_generator_for_wp_common_hooks() {
		$pgfw_plugin_common = new Pdf_Generator_For_Wp_Common( $this->pgfw_get_plugin_name(), $this->pgfw_get_version() );
		$this->loader->add_action( 'wp_enqueue_scripts', $pgfw_plugin_common, 'pgfw_common_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $pgfw_plugin_common, 'pgfw_common_enqueue_scripts' );
		$pdf_general_settings_arr = wps_pgfw_get_option_cached( 'pgfw_general_settings_save', array() );
		$pgfw_enable_plugin       = array_key_exists( 'pgfw_enable_plugin', $pdf_general_settings_arr ) ? $pdf_general_settings_arr['pgfw_enable_plugin'] : '';
		if ( 'yes' === $pgfw_enable_plugin ) {
			// catching pdf generate link with $_GET.
			$this->loader->add_action( 'init', $pgfw_plugin_common, 'pgfw_generate_pdf_link_catching_user', 20 );
			$this->loader->add_action( 'plugins_loaded', $pgfw_plugin_common, 'pgfw_poster_download_shortcode' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_pgfw_ajax_for_single_pdf_mail', $pgfw_plugin_common, 'wps_pgfw_generate_pdf_single_and_mail' );
			$this->loader->add_action( 'wp_ajax_wps_pgfw_ajax_for_single_pdf_mail', $pgfw_plugin_common, 'wps_pgfw_generate_pdf_single_and_mail' );

			$this->loader->add_action( 'load-edit.php', $pgfw_plugin_common, 'pgfw_aspose_pdf_exporter_bulk_action' );
			// Bulk pdf gentrate hook for the page post and producta.
			$this->loader->add_filter( 'bulk_actions-edit-post', $pgfw_plugin_common, 'wpg_add_custom_bulk_action_post', 10, 2 );
			$this->loader->add_filter( 'bulk_actions-edit-page', $pgfw_plugin_common, 'wpg_add_custom_bulk_actions_page', 10, 2 );
			$this->loader->add_filter( 'bulk_actions-edit-product', $pgfw_plugin_common, 'wpg_add_custom_bulk_actionss_product', 10, 2 );
			// invoice.
			$pgfw_enable_plugin = wps_pgfw_get_option_cached( 'wpg_enable_plugin', '' );
			if ( 'yes' === $pgfw_enable_plugin ) {
				// adding shortcodes to fetch all order detials [ISFW_FETCH_ORDER].
				$this->loader->add_action( 'plugins_loaded', $pgfw_plugin_common, 'wpg_fetch_order_details_shortcode' );
				// Custom template mapping.
				$this->loader->add_filter( 'pgfw_load_templates_for_pdf_html', $pgfw_plugin_common, 'wpg_load_custom_template_for_pdf_generation', 10, 3 );
				$this->loader->add_action( 'wp_ajax_wpg_save_template_items', $pgfw_plugin_common, 'wpg_save_template_items_callbck' );
				$this->loader->add_action( 'wpg_reset_invoice_number_hook', $pgfw_plugin_common, 'wpg_reset_invoice_number' );
			}
		}
	}

	/**
	 * Register all of the hooks related to the public-facing functionality.
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function pdf_generator_for_wp_public_hooks() {
		$pgfw_plugin_public = new Pdf_Generator_For_Wp_Public( $this->pgfw_get_plugin_name(), $this->pgfw_get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $pgfw_plugin_public, 'pgfw_public_enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $pgfw_plugin_public, 'pgfw_public_enqueue_scripts' );
		$pdf_general_settings_arr     = wps_pgfw_get_option_cached( 'pgfw_general_settings_save', array() );
		$pgfw_display_settings        = wps_pgfw_get_option_cached( 'pgfw_save_admin_display_settings', array() );
		$pgfw_enable_plugin           = array_key_exists( 'pgfw_enable_plugin', $pdf_general_settings_arr ) ? $pdf_general_settings_arr['pgfw_enable_plugin'] : '';
		$pgfw_pdf_icon_after          = array_key_exists( 'pgfw_display_pdf_icon_after', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_after'] : '';
		$pgfw_exclude_wp_filter_hooks = array( 'before_content', 'after_content' );
		if ( 'yes' === $pgfw_enable_plugin ) {
			$this->loader->add_action( 'plugins_loaded', $pgfw_plugin_public, 'pgfw_shortcode_to_generate_pdf' );
			if ( wps_pgfw_is_plugin_active_cached( 'woocommerce/woocommerce.php' ) ) {
				if ( '' !== $pgfw_pdf_icon_after && ! in_array( $pgfw_pdf_icon_after, $pgfw_exclude_wp_filter_hooks, true ) ) {
					// post to pdf generate button if woocomerce is activated.
					$this->loader->add_action( $pgfw_pdf_icon_after, $pgfw_plugin_public, 'pgfw_show_download_icon_to_users_for_woocommerce' );
				} else {
					// Post to pdf generate button if woocommerce is activated but hook the content is used.
					$this->loader->add_filter( 'the_content', $pgfw_plugin_public, 'pgfw_show_download_icon_to_users', 20 );
				}
			} else {
				// Post to pdf generate button if woocommerce is not activated.
				$this->loader->add_filter( 'the_content', $pgfw_plugin_public, 'pgfw_show_download_icon_to_users', 20 );
			}
		}

		$this->loader->add_action( 'init', $pgfw_plugin_public, 'wps_pgfw_flipbook_shortcode_callback' );
	}

	/**
	 * Register all of the hooks related to the api functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 */
	private function pdf_generator_for_wp_api_hooks() {
		$pgfw_plugin_api = new Pdf_Generator_For_Wp_Rest_Api( $this->pgfw_get_plugin_name(), $this->pgfw_get_version() );

		$this->loader->add_action( 'rest_api_init', $pgfw_plugin_api, 'wps_pgfw_add_endpoint' );
	}


	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_run() {
		$this->loader->pgfw_run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function pgfw_get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pdf_Generator_For_Wp_Loader    Orchestrates the hooks of the plugin.
	 */
	public function pgfw_get_loader() {
		 return $this->loader;
	}


	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Pdf_Generator_For_Wp_Onboard    Orchestrates the hooks of the plugin.
	 */
	public function pgfw_get_onboard() {
		return $this->pgfw_onboard;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function pgfw_get_version() {
		return $this->version;
	}

	/**
	 * Check whether the pro plugin is active on the current site or network.
	 *
	 * @since 1.6.2
	 * @return bool
	 */
	public function wps_pgfw_is_pro_plugin_active() {
		return wps_pgfw_is_pdf_pro_plugin_active();
	}

	/**
	 * Get the dashboard version label for free/pro installs.
	 *
	 * @since 1.6.2
	 * @return string
	 */
	public function wps_pgfw_get_dashboard_version_label() {
		$version = PDF_GENERATOR_FOR_WP_VERSION;

		if ( $this->wps_pgfw_is_pro_plugin_active() ) {
			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php';
			}

			$plugins = get_plugins();
			if ( ! empty( $plugins['wordpress-pdf-generator/wordpress-pdf-generator.php']['Version'] ) ) {
				$version = $plugins['wordpress-pdf-generator/wordpress-pdf-generator.php']['Version'];
			}

			return sprintf(
				/* translators: 1: version number, 2: pro suffix. */
				esc_html__( 'v%1$s %2$s', 'pdf-generator-for-wp' ),
				$version,
				esc_html__( 'Pro', 'pdf-generator-for-wp' )
			);
		}

		return sprintf(
			/* translators: %s: version number. */
			esc_html__( 'v%s', 'pdf-generator-for-wp' ),
			$version
		);
	}

	/**
	 * Return legacy free-tab to pro-tab aliases for dashboard routes.
	 *
	 * @since 1.6.2
	 * @return array
	 */
	public function wps_pgfw_get_pro_tab_aliases() {
		return array(
			'pdf-generator-for-wp-taxonomy'             => 'wordpress-pdf-generator-taxonomy',
			'pdf-generator-for-wp-layout-settings'      => 'wordpress-pdf-generator-layout-settings',
			'pdf-generator-for-wp-cover-page-setting'   => 'wordpress-pdf-generator-cover-page-setting',
			'pdf-generator-for-wp-internal-page-setting'=> 'wordpress-pdf-generator-internal-page-setting',
			'pdf-generator-for-wp-logs'                 => 'wordpress-pdf-generator-logs',
			'pdf-generator-for-wp-invoice-general'      => 'wordpress-pdf-generator-invoice-general',
			'pdf-generator-for-wp-invoice-page-setting' => 'wordpress-pdf-generator-invoice-page-setting',
		);
	}

	/**
	 * Normalize legacy free pro-tab slugs to their active pro equivalents.
	 *
	 * @since 1.6.2
	 * @param string $tab_key Requested dashboard tab.
	 * @return string
	 */
	public function wps_pgfw_normalize_dashboard_tab( $tab_key ) {
		$tab_key = sanitize_key( $tab_key );

		if ( empty( $tab_key ) || ! $this->wps_pgfw_is_pro_plugin_active() ) {
			return $tab_key;
		}

		$pro_tab_aliases = $this->wps_pgfw_get_pro_tab_aliases();

		return isset( $pro_tab_aliases[ $tab_key ] ) ? $pro_tab_aliases[ $tab_key ] : $tab_key;
	}

	/**
	 * Predefined default wps_pgfw_plug tabs.
	 *
	 * @return array An key=>value pair of PDF Generator For WordPress tabs.
	 */
	public function wps_pgfw_plug_default_tabs() {
		$pgfw_default_tabs = array();

		$pgfw_default_tabs['pdf-generator-for-wp-overview'] = array(
			'title' => esc_html__( 'Overview', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-overview',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-general'] = array(
			'title' => esc_html__( 'General Settings', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-general',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-pdf-setting'] = array(
			'title' => esc_html__( 'PDF Settings', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-pdf-setting',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-advanced'] = array(
			'title' => esc_html__( 'Advanced Settings', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-advanced',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-meta-fields'] = array(
			'title' => esc_html__( 'Meta Fields Settings', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-meta-fields',
		);
		$pgfw_default_tabs['pdf-generator-for-wp-embed-source'] = array(
			'title' => esc_html__( 'Embed Source', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-embed-source',
		);
		// Check if the pro plugin is active.
		if ( ! wps_pgfw_is_pdf_pro_plugin_active() ) {
			// Pro plugin is active.
			$pgfw_default_tabs['pdf-generator-for-wp-taxonomy'] = array(
				'title' => esc_html__( 'Taxonomy Settings', 'pdf-generator-for-wp' ),
				'name'  => 'pdf-generator-for-wp-taxonomy',
			);

			$pgfw_default_tabs['pdf-generator-for-wp-layout-settings'] = array(
				'title' => esc_html__( 'Layout Settings', 'pdf-generator-for-wp' ),
				'name'  => 'pdf-generator-for-wp-layout-settings',
			);

			$pgfw_default_tabs['pdf-generator-for-wp-logs'] = array(
				'title' => esc_html__( 'PDF Logs', 'pdf-generator-for-wp' ),
				'name'  => 'pdf-generator-for-wp-logs',
			);
			// tabs for the invoice genration.
			$pgfw_default_tabs['pdf-generator-for-wp-invoice-general'] = array(
				'title' => esc_html__( 'Invoice settings', 'pdf-generator-for-wp' ),
				'name'  => 'pdf-generator-for-wp-invoice-general',
			);
			// invoice main page setting tab.
			$pgfw_default_tabs['pdf-generator-for-wp-invoice-page-setting'] = array(
				'title' => esc_html__( 'Invoice page settings', 'pdf-generator-for-wp' ),
				'name'  => 'pdf-generator-for-wp-invoice-page-setting',
			);
		}
		// END DUMMY CODE TABS ////////.
		$pgfw_default_tabs = apply_filters( 'wps_pgfw_plugin_standard_admin_settings_tabs', $pgfw_default_tabs );

		$pgfw_default_tabs['pdf-generator-for-wp-pdf-upload'] = array(
			'title' => esc_html__( 'PDF Upload', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-pdf-upload',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-shortcode'] = array(
			'title' => esc_html__( 'Shortcodes', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-shortcode',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-overview'] = array(
			'title' => esc_html__( 'Overview', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-overview',
		);
		return $pgfw_default_tabs;
	}
	/**
	 * Customizations sub tabs.
	 *
	 * @since 1.0.0
	 * @return array array containing sub tabs menus details.
	 */
	public function wps_pgfw_plug_default_sub_tabs() {
		$pgfw_default_tabs = array();
		$pgfw_default_tabs['pdf-generator-for-wp-pdf-icon-setting'] = array(
			'title' => esc_html__( 'Icon Display', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-pdf-icon-setting',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-header'] = array(
			'title' => esc_html__( 'Header', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-header',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-body'] = array(
			'title' => esc_html__( 'Body', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-body',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-footer'] = array(
			'title' => esc_html__( 'Footer', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-footer',
		);

		return $pgfw_default_tabs;
	}

	/**
	 * Return child-to-parent dashboard tab mappings.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function wps_pgfw_get_dashboard_parent_tab_map() {
		return array(
			'pdf-generator-for-wp-pdf-icon-setting'  => 'pdf-generator-for-wp-pdf-setting',
			'pdf-generator-for-wp-header'            => 'pdf-generator-for-wp-pdf-setting',
			'pdf-generator-for-wp-body'              => 'pdf-generator-for-wp-pdf-setting',
			'pdf-generator-for-wp-footer'            => 'pdf-generator-for-wp-pdf-setting',
			'pdf-generator-for-wp-cover-page-setting'    => 'pdf-generator-for-wp-layout-settings',
			'pdf-generator-for-wp-internal-page-setting' => 'pdf-generator-for-wp-layout-settings',
			'wordpress-pdf-generator-cover-page-setting'    => 'wordpress-pdf-generator-layout-settings',
			'wordpress-pdf-generator-internal-page-setting' => 'wordpress-pdf-generator-layout-settings',
		);
	}

	/**
	 * Return the top-level dashboard tab for a tab key.
	 *
	 * @since 1.0.0
	 * @param string $tab_key Current tab key.
	 * @return string
	 */
	public function wps_pgfw_get_dashboard_parent_tab( $tab_key ) {
		$tab_key        = $this->wps_pgfw_normalize_dashboard_tab( $tab_key );
		$parent_tab_map = $this->wps_pgfw_get_dashboard_parent_tab_map();
		return isset( $parent_tab_map[ $tab_key ] ) ? $parent_tab_map[ $tab_key ] : $tab_key;
	}

	/**
	 * Return dashboard hero content for the active tab.
	 *
	 * @since 1.0.0
	 * @param string $tab_key Current tab key.
	 * @return array
	 */
	public function wps_pgfw_get_dashboard_header_content( $tab_key ) {
		$tab_key     = $this->wps_pgfw_normalize_dashboard_tab( $tab_key );
		$parent_tab  = $this->wps_pgfw_get_dashboard_parent_tab( $tab_key );
		$header_data = array(
			'pdf-generator-for-wp-overview' => array(
				'eyebrow'     => esc_html__( 'Overview', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'See your PDF workspace at a glance', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Review core plugin capabilities, quick actions, and support resources from one dashboard.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-general' => array(
				'eyebrow'     => esc_html__( 'General Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Control how PDF generation behaves across your site', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Configure the main plugin behavior, availability, and baseline PDF generation rules for your content.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-pdf-setting' => array(
				'eyebrow'     => esc_html__( 'PDF Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Shape the PDF experience from icon to layout', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Adjust icon display, document structure, and the visual sections that appear inside each generated PDF.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-pdf-icon-setting' => array(
				'eyebrow'     => esc_html__( 'PDF Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Control how PDF icons appear on your site', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Manage icon visibility, alignment, labels, and styling before users generate or download PDFs.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-header' => array(
				'eyebrow'     => esc_html__( 'PDF Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Customize the header section of each PDF', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Configure the logo, company name, tagline, typography, and spacing shown at the top of generated PDFs.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-body' => array(
				'eyebrow'     => esc_html__( 'PDF Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Design the main PDF body content', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Set up body styling, colors, watermark options, and content presentation for the main document area.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-footer' => array(
				'eyebrow'     => esc_html__( 'PDF Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Manage the footer that closes every PDF', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Update footer text, spacing, and supporting details so the final page area matches your document design.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-advanced' => array(
				'eyebrow'     => esc_html__( 'Advanced Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Handle advanced PDF generation options', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Fine-tune deeper behavior, compatibility settings, and advanced controls for more complex PDF workflows.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-meta-fields' => array(
				'eyebrow'     => esc_html__( 'Meta Fields', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Decide which meta fields flow into your PDFs', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Select and organize post meta so the right data is injected into generated documents.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-embed-source' => array(
				'eyebrow'     => esc_html__( 'Embed Source', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Choose the source used for embedded PDFs', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Configure where embedded PDF content is pulled from and how it is exposed within your site experience.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-taxonomy' => array(
				'eyebrow'     => esc_html__( 'Taxonomy Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Control taxonomy data inside generated PDFs', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Decide how taxonomy terms are included so category and classification data appears where you need it.', 'pdf-generator-for-wp' ),
			),
			'wordpress-pdf-generator-taxonomy' => array(
				'eyebrow'     => esc_html__( 'Taxonomy Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Control taxonomy data inside generated PDFs', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Decide how taxonomy terms are included so category and classification data appears where you need it.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-layout-settings' => array(
				'eyebrow'     => esc_html__( 'Layout Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Build the page structure of your PDF layouts', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Configure page templates, supporting layouts, and layout-specific structure for more tailored PDF output.', 'pdf-generator-for-wp' ),
			),
			'wordpress-pdf-generator-layout-settings' => array(
				'eyebrow'     => esc_html__( 'Layout Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Build the page structure of your PDF layouts', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Configure page templates, supporting layouts, and layout-specific structure for more tailored PDF output.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-cover-page-setting' => array(
				'eyebrow'     => esc_html__( 'Layout Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Configure the cover page layout', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Manage the content and presentation of the opening page shown before the main PDF body.', 'pdf-generator-for-wp' ),
			),
			'wordpress-pdf-generator-cover-page-setting' => array(
				'eyebrow'     => esc_html__( 'Layout Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Configure the cover page layout', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Manage the content and presentation of the opening page shown before the main PDF body.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-internal-page-setting' => array(
				'eyebrow'     => esc_html__( 'Layout Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Set up the internal page layout', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Define the structure and reusable sections used across internal pages of the generated PDF.', 'pdf-generator-for-wp' ),
			),
			'wordpress-pdf-generator-internal-page-setting' => array(
				'eyebrow'     => esc_html__( 'Layout Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Set up the internal page layout', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Define the structure and reusable sections used across internal pages of the generated PDF.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-logs' => array(
				'eyebrow'     => esc_html__( 'PDF Logs', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Inspect PDF generation activity and diagnostics', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Review logged PDF events to monitor activity, troubleshoot issues, and verify document generation.', 'pdf-generator-for-wp' ),
			),
			'wordpress-pdf-generator-logs' => array(
				'eyebrow'     => esc_html__( 'PDF Logs', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Inspect PDF generation activity and diagnostics', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Review logged PDF events to monitor activity, troubleshoot issues, and verify document generation.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-invoice-general' => array(
				'eyebrow'     => esc_html__( 'Invoice Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Configure invoice PDF generation rules', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Set invoice-specific behavior, numbering, branding, and options for WooCommerce invoice documents.', 'pdf-generator-for-wp' ),
			),
			'wordpress-pdf-generator-invoice-general' => array(
				'eyebrow'     => esc_html__( 'Invoice Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Configure invoice PDF generation rules', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Set invoice-specific behavior, numbering, branding, and options for WooCommerce invoice documents.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-invoice-page-setting' => array(
				'eyebrow'     => esc_html__( 'Invoice Page Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Design the layout used for invoice pages', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Adjust invoice page structure and visual presentation so billing documents match your storefront requirements.', 'pdf-generator-for-wp' ),
			),
			'wordpress-pdf-generator-invoice-page-setting' => array(
				'eyebrow'     => esc_html__( 'Invoice Page Settings', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Design the layout used for invoice pages', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Adjust invoice page structure and visual presentation so billing documents match your storefront requirements.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-pdf-upload' => array(
				'eyebrow'     => esc_html__( 'PDF Upload', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Upload PDF assets and reuse them with shortcodes', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Manage uploaded PDF files and poster assets so they can be placed anywhere on your site with generated shortcodes.', 'pdf-generator-for-wp' ),
			),
			'pdf-generator-for-wp-shortcode' => array(
				'eyebrow'     => esc_html__( 'Shortcodes', 'pdf-generator-for-wp' ),
				'title'       => esc_html__( 'Reference the shortcodes available in this plugin', 'pdf-generator-for-wp' ),
				'description' => esc_html__( 'Review copy-ready shortcode patterns for PDF buttons, meta values, QR codes, and other supported outputs.', 'pdf-generator-for-wp' ),
			),
		);

		if ( isset( $header_data[ $tab_key ] ) ) {
			return $header_data[ $tab_key ];
		}

		if ( isset( $header_data[ $parent_tab ] ) ) {
			return $header_data[ $parent_tab ];
		}

		$known_tabs = array_merge(
			$this->wps_pgfw_plug_default_tabs(),
			$this->wps_pgfw_plug_default_sub_tabs(),
			$this->wps_pgfw_plug_layout_setting_sub_tabs(),
			$this->wps_pgfw_plug_layout_setting_sub_tabs_dummy()
		);
		$tab_title  = isset( $known_tabs[ $tab_key ]['title'] ) ? $known_tabs[ $tab_key ]['title'] : esc_html__( 'PDF Workspace', 'pdf-generator-for-wp' );

		return array(
			'eyebrow'     => $tab_title,
			/* translators: %s: current dashboard tab title. */
			'title'       => sprintf( esc_html__( 'Manage %s', 'pdf-generator-for-wp' ), $tab_title ),
			'description' => esc_html__( 'Review and update the settings available in this section.', 'pdf-generator-for-wp' ),
		);
	}
	/**
	 * Loading sub tabs for layout settings used by pro plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function wps_pgfw_plug_layout_setting_sub_tabs() {
		$pgfw_default_sub_tabs = array();
		$pgfw_default_sub_tabs = apply_filters( 'wps_pgfw_plugin_standard_admin_settings_sub_tabs', $pgfw_default_sub_tabs );
		return $pgfw_default_sub_tabs;
	}
	/**
	 * Loading sub tabs for layout settings used by pro plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	public function wps_pgfw_plug_layout_setting_sub_tabs_dummy() {
		 $pgfw_default_sub_tabs = array();
		$pgfw_default_sub_tabs = apply_filters( 'pgfw_plugin_standard_admin_settings_sub_tabs_dummy', $pgfw_default_sub_tabs );
		return $pgfw_default_sub_tabs;
	}
	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function wps_pgfw_plug_load_template( $path, $params = array() ) {
		$pgfw_file_path = PDF_GENERATOR_FOR_WP_DIR_PATH . $path;
		$pgfw_file_path = apply_filters( 'wps_pgfw_setting_page_loading_filter_hook', $pgfw_file_path, $path );
		if ( file_exists( $pgfw_file_path ) ) {
			include $pgfw_file_path;
			return;
		}

		$pgfw_fallback_path = $this->wps_pgfw_maybe_get_fallback_template_path( $path );

		if ( ! empty( $pgfw_fallback_path ) && file_exists( $pgfw_fallback_path ) ) {
			include $pgfw_fallback_path;
			return;
		}

		/* translators: %s: file path */
		$pgfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'pdf-generator-for-wp' ), ( ! empty( $pgfw_fallback_path ) ? $pgfw_fallback_path : $pgfw_file_path ) );
		$this->wps_pgfw_plug_admin_notice( $pgfw_notice, 'error' );
	}
	/**
	 * Locate and load appropriate tempate.
	 *
	 * @since   1.0.0
	 * @param string $path path file for inclusion.
	 * @param array  $params parameters to pass to the file for access.
	 */
	public function wps_pgfw_plug_load_sub_template( $path, $params = array() ) {
		$pgfw_file_path = PDF_GENERATOR_FOR_WP_DIR_PATH . $path;
		$pgfw_file_path = apply_filters( 'wps_pgfw_setting_sub_page_loading_filter_hook', $pgfw_file_path, $path );
		if ( file_exists( $pgfw_file_path ) ) {
			include $pgfw_file_path;
			return;
		}

		$pgfw_fallback_path = $this->wps_pgfw_maybe_get_fallback_template_path( $path );

		if ( ! empty( $pgfw_fallback_path ) && file_exists( $pgfw_fallback_path ) ) {
			include $pgfw_fallback_path;
			return;
		}

		/* translators: %s: file path */
		$pgfw_notice = sprintf( esc_html__( 'Unable to locate file at location %s. Some features may not work properly in this plugin. Please contact us!', 'pdf-generator-for-wp' ), ( ! empty( $pgfw_fallback_path ) ? $pgfw_fallback_path : $pgfw_file_path ) );
		$this->wps_pgfw_plug_admin_notice( $pgfw_notice, 'error' );
	}

	/**
	 * Provide fallback template path when pro slugs are requested but pro plugin is inactive.
	 *
	 * @since 1.0.0
	 * @param string $relative_path Template path relative to plugin root.
	 * @return string Fallback absolute path or empty string when none.
	 */
	private function wps_pgfw_maybe_get_fallback_template_path( $relative_path ) {
		$pgfw_fallback_path = '';

		if ( false === strpos( $relative_path, 'wordpress-pdf-generator-' ) ) {
			return $pgfw_fallback_path;
		}

		if ( wps_pgfw_is_pdf_pro_plugin_active() ) {
			return $pgfw_fallback_path;
		}

		$fallback_relative_path = str_replace( 'wordpress-pdf-generator-', 'pdf-generator-for-wp-', $relative_path );
		$pgfw_fallback_path     = PDF_GENERATOR_FOR_WP_DIR_PATH . $fallback_relative_path;

		return $pgfw_fallback_path;
	}

	/**
	 * Show admin notices.
	 *
	 * @param  string $pgfw_message    Message to display.
	 * @param  string $type       notice type, accepted values - error/update/update-nag.
	 * @since  1.0.0
	 */
	public static function wps_pgfw_plug_admin_notice( $pgfw_message, $type = 'error' ) {

		$pgfw_classes = 'notice ';

		switch ( $type ) {

			case 'update':
				$pgfw_classes .= 'updated is-dismissible';
				break;

			case 'update-nag':
				$pgfw_classes .= 'update-nag is-dismissible';
				break;

			case 'success':
				$pgfw_classes .= 'notice-success is-dismissible';
				break;

			default:
				$pgfw_classes .= 'notice-error is-dismissible';
				break;
		}

		$pgfw_notice  = '<div class="' . esc_attr( $pgfw_classes ) . ' wps-errorr-5">';
		$pgfw_notice .= '<p>' . esc_html( $pgfw_message ) . '</p>';
		$pgfw_notice .= '</div>';

		echo wp_kses_post( $pgfw_notice );
	}
	/**
	 * Generate html components.
	 *
	 * @param  string $pgfw_components    html to display.
	 * @since  1.0.0
	 */
	public function wps_pgfw_plug_generate_html( $pgfw_components = array() ) {
		if ( is_array( $pgfw_components ) && ! empty( $pgfw_components ) ) {
			foreach ( $pgfw_components as $pgfw_component ) {
				if ( ! empty( $pgfw_component['type'] ) && ! empty( $pgfw_component['id'] ) ) {
					switch ( $pgfw_component['type'] ) {
						case 'hidden':
						case 'number':
						case 'email':
						case 'text':
							?>
							<div class="wps-form-group wps-pgfw-<?php echo esc_attr( $pgfw_component['type'] . ' ' . $pgfw_component['class'] . ' ' . ( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ) ); ?>" style="<?php echo esc_attr( array_key_exists( 'style', $pgfw_component ) ? $pgfw_component['style'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<?php if ( 'number' !== $pgfw_component['type'] ) { ?>
													<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?></span>
												<?php } ?>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input
											class="mdc-text-field__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
											name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
											id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
											type="<?php echo esc_attr( $pgfw_component['type'] ); ?>"
											value="<?php echo ( isset( $pgfw_component['value'] ) ? esc_attr( $pgfw_component['value'] ) : '' ); ?>"
											placeholder="<?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?>"
											<?php echo ( 'number' === $pgfw_component['type'] && isset( $pgfw_component['min'] ) ) ? esc_html( 'min=' . $pgfw_component['min'] ) : ''; ?>
											<?php echo ( 'number' === $pgfw_component['type'] && isset( $pgfw_component['max'] ) ) ? esc_html( 'max=' . $pgfw_component['max'] ) : ''; ?>
											<?php echo isset( $pgfw_component['step'] ) ? esc_html( 'step=' . $pgfw_component['step'] ) : ''; ?>>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'password':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--with-trailing-icon">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<input
											class="mdc-text-field__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?> wps-form__password"
											name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
											id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
											type="<?php echo esc_attr( $pgfw_component['type'] ); ?>"
											value="<?php echo ( isset( $pgfw_component['value'] ) ? esc_attr( $pgfw_component['value'] ) : '' ); ?>"
											placeholder="<?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?>">
										<i class="material-icons mdc-text-field__icon mdc-text-field__icon--trailing wps-password-hidden" tabindex="0" role="button">visibility</i>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'textarea':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label class="wps-form-label" for="<?php echo esc_attr( $pgfw_component['id'] ); ?>"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea" for="text-field-hero-input">
										<span class="mdc-notched-outline">
											<span class="mdc-notched-outline__leading"></span>
											<span class="mdc-notched-outline__notch">
												<span class="mdc-floating-label"><?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?></span>
											</span>
											<span class="mdc-notched-outline__trailing"></span>
										</span>
										<span class="mdc-text-field__resizer">
											<textarea class="mdc-text-field__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>" rows="2" cols="25" aria-label="Label" name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>" id="<?php echo esc_attr( $pgfw_component['id'] ); ?>" placeholder="<?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?>"><?php echo ( isset( $pgfw_component['value'] ) ? esc_textarea( $pgfw_component['value'] ) : '' ); ?></textarea>
										</span>
									</label>
									<br />
									<label class="mdl-textfield__label" for="octane"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></label>
								</div>
							</div>

							<?php
							break;

						case 'select':
						case 'multiselect':
							?>
							<div class="wps-form-group <?php echo esc_attr( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label class="wps-form-label" for="<?php echo esc_attr( $pgfw_component['id'] ); ?>"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<div class="wps-form-select">
										<select id="<?php echo esc_attr( $pgfw_component['id'] ); ?>" name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : '' ); ?><?php echo ( 'multiselect' === $pgfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>" <?php echo 'multiselect' === $pgfw_component['type'] ? 'multiple="multiple"' : ''; ?>>
											<?php
											if ( ! empty( $pgfw_component['options'] ) && is_array( $pgfw_component['options'] ) ) {
												foreach ( $pgfw_component['options'] as $pgfw_key => $pgfw_val ) {
													?>
													<option value="<?php echo esc_attr( $pgfw_key ); ?>"
														<?php
														if ( is_array( $pgfw_component['value'] ) ) {
															selected( in_array( (string) $pgfw_key, $pgfw_component['value'], true ), true );
														} else {
															selected( $pgfw_component['value'], (string) $pgfw_key );
														}
														?>
														>
														<?php echo esc_html( $pgfw_val ); ?>
													</option>
													<?php
												}
											}
											?>
										</select>
										<label class="mdl-textfield__label" for="octane"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></label>
									</div>
								</div>
							</div>

							<?php
							break;

						case 'checkbox':
							?>
							<div class="wps-form-group <?php echo esc_attr( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control wps-pl-4">
									<div class="mdc-form-field">
										<div class="mdc-checkbox">
											<input
												name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
												id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
												type="checkbox"
												class="mdc-checkbox__native-control <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
												value="yes"
												<?php checked( $pgfw_component['value'], 'yes' ); ?> />
											<div class="mdc-checkbox__background">
												<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
													<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59" />
												</svg>
												<div class="mdc-checkbox__mixedmark"></div>
											</div>
											<div class="mdc-checkbox__ripple"></div>
										</div>
										<label for="checkbox-1"><?php echo ( isset( $pgfw_component['description'] ) ? wp_kses_post( $pgfw_component['description'] ) : '' ); ?></label>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'radio':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control wps-pl-4">
									<div class="wps-flex-col">
										<?php
										foreach ( $pgfw_component['options'] as $pgfw_radio_key => $pgfw_radio_val ) {
											?>
											<div class="mdc-form-field">
												<div class="mdc-radio">
													<input
														name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
														value="<?php echo esc_attr( $pgfw_radio_key ); ?>"
														type="radio"
														class="mdc-radio__native-control <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
														<?php checked( $pgfw_radio_key, $pgfw_component['value'] ); ?>>
													<div class="mdc-radio__background">
														<div class="mdc-radio__outer-circle"></div>
														<div class="mdc-radio__inner-circle"></div>
													</div>
													<div class="mdc-radio__ripple"></div>
												</div>
												<label for="radio-1"><?php echo esc_html( $pgfw_radio_val ); ?></label>
											</div>
											<?php
										}
										?>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'radio-image':
							$pgfw_radio_image_categories = array();
							foreach ( $pgfw_component['options'] as $pgfw_radio_option ) {
								if ( is_array( $pgfw_radio_option ) && ! empty( $pgfw_radio_option['category'] ) ) {
									$pgfw_radio_image_category = strtolower( wp_strip_all_tags( $pgfw_radio_option['category'] ) );
									if ( ! isset( $pgfw_radio_image_categories[ $pgfw_radio_image_category ] ) ) {
										$pgfw_radio_image_categories[ $pgfw_radio_image_category ] = array(
											'label' => $pgfw_radio_option['category'],
											'count' => 0,
										);
									}
									$pgfw_radio_image_categories[ $pgfw_radio_image_category ]['count']++;
								}
							}
							?>
							<div class="wps-form-group <?php echo esc_attr( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
									<div class="wps-form-group__control">
										<div class="pgfw-radio-image-toolbar" aria-hidden="true">
											<div class="pgfw-radio-image-summary">
												<?php /* translators: %d: total number of available radio image options. */ ?>
												<span class="pgfw-radio-image-summary__pill"><?php echo esc_html( sprintf( __( 'All %d', 'pdf-generator-for-wp' ), count( $pgfw_component['options'] ) ) ); ?></span>
												<?php foreach ( $pgfw_radio_image_categories as $pgfw_radio_image_category ) : ?>
													<?php /* translators: 1: radio image category label, 2: number of options in that category. */ ?>
													<span class="pgfw-radio-image-summary__pill"><?php echo esc_html( sprintf( __( '%1$s %2$d', 'pdf-generator-for-wp' ), $pgfw_radio_image_category['label'], $pgfw_radio_image_category['count'] ) ); ?></span>
												<?php endforeach; ?>
											</div>
										</div>
									<div class="pgfw-radio-image-group">
										<?php foreach ( $pgfw_component['options'] as $pgfw_radio_key => $pgfw_radio_option ) : ?>
											<?php
											$pgfw_radio_label       = is_array( $pgfw_radio_option ) && isset( $pgfw_radio_option['label'] ) ? $pgfw_radio_option['label'] : $pgfw_radio_option;
											$pgfw_radio_description = is_array( $pgfw_radio_option ) && isset( $pgfw_radio_option['description'] ) ? $pgfw_radio_option['description'] : '';
											$pgfw_radio_preview     = is_array( $pgfw_radio_option ) && isset( $pgfw_radio_option['preview_class'] ) ? $pgfw_radio_option['preview_class'] : '';
											$pgfw_radio_category    = is_array( $pgfw_radio_option ) && isset( $pgfw_radio_option['category'] ) ? $pgfw_radio_option['category'] : '';
											$pgfw_radio_sequence    = is_array( $pgfw_radio_option ) && isset( $pgfw_radio_option['sequence'] ) ? $pgfw_radio_option['sequence'] : '';
											?>
											<label class="pgfw-radio-image-option">
												<input
													name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
													value="<?php echo esc_attr( $pgfw_radio_key ); ?>"
													type="radio"
													class="pgfw-radio-image-option__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
													<?php checked( $pgfw_radio_key, $pgfw_component['value'] ); ?>>
												<span class="pgfw-radio-image-option__card">
													<span class="pgfw-radio-image-option__check" aria-hidden="true"></span>
													<span class="pgfw-radio-image-option__meta">
														<?php if ( ! empty( $pgfw_radio_sequence ) ) : ?>
															<span class="pgfw-radio-image-option__index"><?php echo esc_html( $pgfw_radio_sequence ); ?></span>
														<?php endif; ?>
														<?php if ( ! empty( $pgfw_radio_category ) ) : ?>
															<span class="pgfw-radio-image-option__category"><?php echo esc_html( strtolower( $pgfw_radio_category ) ); ?></span>
														<?php endif; ?>
													</span>
													<span class="pgfw-radio-image-option__preview <?php echo esc_attr( $pgfw_radio_preview ); ?>">
														<span class="pgfw-radio-image-option__preview-icon"></span>
														<span class="pgfw-radio-image-option__preview-label"></span>
													</span>
													<span class="pgfw-radio-image-option__content">
														<span class="pgfw-radio-image-option__title"><?php echo esc_html( $pgfw_radio_label ); ?></span>
														<?php if ( ! empty( $pgfw_radio_description ) ) : ?>
															<span class="pgfw-radio-image-option__description"><?php echo esc_html( $pgfw_radio_description ); ?></span>
														<?php endif; ?>
													</span>
												</span>
											</label>
										<?php endforeach; ?>
									</div>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'radio-switch':
							?>

							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<div class="pgfw-toggle">
										<label class="pgfw-toggle__switch" for="<?php echo esc_attr( $pgfw_component['id'] ); ?>">
											<input
												name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
												type="checkbox"
												id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
												value="yes"
												class="pgfw-toggle__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
												role="switch"
												aria-checked="<?php echo esc_attr( 'yes' === $pgfw_component['value'] ? 'true' : 'false' ); ?>"
												<?php checked( $pgfw_component['value'], 'yes' ); ?>>
											<span class="pgfw-toggle__track" aria-hidden="true">
												<span class="pgfw-toggle__thumb"></span>
											</span>
										</label>
									</div>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;

						case 'button':
							?>
							<div class="wps-form-group pgfw-savebar-wrap">
								<div class="wps-form-group__label"></div>
								<div class="wps-form-group__control pgfw-savebar-control">
									<button type="submit" class="mdc-button mdc-button--raised wps-pgfw-save-setting pgfw-btn-save" name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
										<span class="mdc-button__label <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"><?php echo ( isset( $pgfw_component['button_text'] ) ? esc_html( $pgfw_component['button_text'] ) : '' ); ?></span>
									</button>
								</div>
							</div>
							<?php
							break;
						case 'reset-button':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<button type="submit" class="<?php echo esc_attr( $pgfw_component['class'] ); ?>" name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
										<span class="mdc-button__label <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"><?php echo ( isset( $pgfw_component['button_text'] ) ? esc_html( $pgfw_component['button_text'] ) : '' ); ?></span>
									</button>
									<span id="<?php echo ( isset( $pgfw_component['loader-id'] ) ? esc_attr( $pgfw_component['loader-id'] ) : '' ); ?>"></span>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>

							<?php
							break;

						case 'multi':
							?>
							<div class="wps-form-group wps-isfw-<?php echo esc_attr( $pgfw_component['type'] ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<?php
									foreach ( $pgfw_component['value'] as $component ) {
										if ( 'color' !== $component['type'] ) {
											?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ( 'number' !== $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
											<?php } ?>
											<?php if ( 'color' === $component['type'] ) { ?>
												<?php $pgfw_multi_color_hex = ! empty( $component['value'] ) ? strtoupper( (string) $component['value'] ) : ''; ?>
												<div class="pgfw-color-picker-card <?php echo $pgfw_multi_color_hex ? 'has-color-value' : ''; ?>" data-color-picker-card>
													<div class="pgfw-color-picker-input-wrap">
														<input
															class="<?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>"
															name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
															id="<?php echo esc_attr( $component['id'] ); ?>"
															type="text"
															value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
															placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
															data-default-color="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
															data-alpha-enabled="false">
													</div>
													<div class="pgfw-color-picker-meta">
														<div class="pgfw-color-picker-meta-row">
															<span class="pgfw-color-picker-badge"><?php echo esc_html__( 'Color', 'pdf-generator-for-wp' ); ?></span>
															<span class="pgfw-color-picker-hex" data-color-picker-hex><?php echo esc_html( $pgfw_multi_color_hex ); ?></span>
														</div>
														<div class="pgfw-color-picker-desc"><?php echo ( isset( $component['placeholder'] ) ? esc_html( $component['placeholder'] ) : '' ); ?></div>
													</div>
												</div>
											<?php } else { ?>
												<input
													class="mdc-text-field__input <?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>"
													name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
													id="<?php echo esc_attr( $component['id'] ); ?>"
													type="<?php echo esc_attr( $component['type'] ); ?>"
													value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
													placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
													<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'min=' . $component['min'] . ' max=' . $component['max'] : '' ); ?>>
												</label>
											<?php } ?>
									<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'multiwithcheck':
							?>
							<div class="wps-form-group wps-isfw-<?php echo esc_attr( $pgfw_component['type'] ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<?php
									foreach ( $pgfw_component['value'] as $component ) {
										if ( 'color' !== $component['type'] ) {
											?>
											<label class="mdc-text-field mdc-text-field--outlined">
												<span class="mdc-notched-outline">
													<span class="mdc-notched-outline__leading"></span>
													<span class="mdc-notched-outline__notch">
														<?php if ( 'number' !== $component['type'] ) { ?>
															<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?></span>
														<?php } ?>
													</span>
													<span class="mdc-notched-outline__trailing"></span>
												</span>
											<?php } ?>
											<input type="checkbox" class="wpg-multi-checkbox" name="<?php echo ( isset( $component['checkbox_name'] ) ? esc_attr( $component['checkbox_name'] ) : '' ); ?>" id="<?php echo ( isset( $component['checkbox_id'] ) ? esc_attr( $component['checkbox_id'] ) : '' ); ?>" <?php checked( ( isset( $component['checkbox_value'] ) ? $component['checkbox_value'] : '' ), 'yes' ); ?> value="yes">
											<?php if ( 'color' === $component['type'] ) { ?>
												<?php $pgfw_multiwithcheck_color_hex = ! empty( $component['value'] ) ? strtoupper( (string) $component['value'] ) : ''; ?>
												<div class="pgfw-color-picker-card <?php echo $pgfw_multiwithcheck_color_hex ? 'has-color-value' : ''; ?>" data-color-picker-card>
													<div class="pgfw-color-picker-input-wrap">
														<input
															class="<?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>"
															name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
															id="<?php echo esc_attr( $component['id'] ); ?>"
															type="text"
															value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
															placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
															data-default-color="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
															data-alpha-enabled="false">
													</div>
													<div class="pgfw-color-picker-meta">
														<div class="pgfw-color-picker-meta-row">
															<span class="pgfw-color-picker-badge"><?php echo esc_html__( 'Color', 'pdf-generator-for-wp' ); ?></span>
															<span class="pgfw-color-picker-hex" data-color-picker-hex><?php echo esc_html( $pgfw_multiwithcheck_color_hex ); ?></span>
														</div>
														<div class="pgfw-color-picker-desc"><?php echo ( isset( $component['placeholder'] ) ? esc_html( $component['placeholder'] ) : '' ); ?></div>
													</div>
												</div>
											<?php } else { ?>
												<input
													class="mdc-text-field__input <?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>"
													name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
													id="<?php echo esc_attr( $component['id'] ); ?>"
													type="<?php echo esc_attr( $component['type'] ); ?>"
													value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
													placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
													<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'min=' . $component['min'] . ' max=' . $component['max'] : '' ); ?>>
												</label>
											<?php } ?>
									<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'color':
							$pgfw_color_value = isset( $pgfw_component['value'] ) ? (string) $pgfw_component['value'] : '';
							$pgfw_color_hex   = $pgfw_color_value ? strtoupper( $pgfw_color_value ) : '';
							$pgfw_color_class = isset( $pgfw_component['class'] ) ? (string) $pgfw_component['class'] : '';
							$pgfw_is_native_color_card = false !== strpos( $pgfw_color_class, 'pgfw_native_color_picker' );
							$pgfw_native_color_value   = preg_match( '/^#[0-9A-Fa-f]{6}$/', $pgfw_color_value ) ? $pgfw_color_value : '#000000';
							?>
							<div class="wps-form-group wps-isfw-<?php echo esc_attr( $pgfw_component['type'] ); ?> <?php echo esc_attr( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<div class="pgfw-color-picker-card <?php echo $pgfw_color_hex ? 'has-color-value' : ''; ?> <?php echo $pgfw_is_native_color_card ? 'pgfw-color-picker-card--native' : ''; ?>" data-color-picker-card>
										<div class="pgfw-color-picker-input-wrap">
											<input
												class="<?php echo esc_attr( $pgfw_color_class ); ?>"
												name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
												id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
												type="text"
												value="<?php echo esc_attr( $pgfw_color_value ); ?>"
												data-default-color="<?php echo esc_attr( $pgfw_color_value ); ?>"
												data-alpha-enabled="false">
											<?php if ( $pgfw_is_native_color_card ) : ?>
												<input
													class="pgfw-color-picker-native-control"
													type="color"
													value="<?php echo esc_attr( $pgfw_native_color_value ); ?>"
													aria-label="<?php echo esc_attr( isset( $pgfw_component['title'] ) ? $pgfw_component['title'] : esc_html__( 'Choose color', 'pdf-generator-for-wp' ) ); ?>">
											<?php endif; ?>
										</div>
										<div class="pgfw-color-picker-meta">
											<div class="pgfw-color-picker-meta-row">
												<span class="pgfw-color-picker-badge"><?php echo esc_html__( 'Color', 'pdf-generator-for-wp' ); ?></span>
												<span class="pgfw-color-picker-hex" data-color-picker-hex><?php echo esc_html( $pgfw_color_hex ); ?></span>
											</div>
											<div class="pgfw-color-picker-desc"><?php echo ( isset( $pgfw_component['description'] ) ? esc_html( $pgfw_component['description'] ) : '' ); ?></div>
										</div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'date':
						case 'file':
							?>
							<div class="wps-form-group wps-isfw-<?php echo esc_attr( $pgfw_component['type'] ); ?> <?php echo esc_attr( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<input
										class="<?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
										name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
										type="<?php echo esc_attr( $pgfw_component['type'] ); ?>"
										value="<?php echo ( isset( $pgfw_component['value'] ) ? esc_attr( $pgfw_component['value'] ) : '' ); ?>"
										<?php echo esc_html( ( 'date' === $pgfw_component['type'] ) ? 'max=' . gmdate( 'Y-m-d', strtotime( gmdate( 'Y-m-d', mktime() ) . ' + 365 day' ) ) . ' min=' . gmdate( 'Y-m-d' ) . '' : '' ); ?>>
									<?php if ( 'file' === $pgfw_component['type'] ) { ?>
										<span><?php echo esc_attr( $pgfw_component['value'] ); ?></span>
									<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'temp-select':
							?>
							<div class="wps-form-group wps_pgfw_pro_tag wps-wpg-<?php echo esc_attr( array_key_exists( 'type', $pgfw_component ) ? $pgfw_component['type'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( array_key_exists( 'id', $pgfw_component ) ? $pgfw_component['id'] : '' ); ?>" class="wps-form-label"><?php echo esc_html( array_key_exists( 'title', $pgfw_component ) ? $pgfw_component['title'] : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<?php
									foreach ( $pgfw_component['value'] as $pgfw_subcomponent ) {
										?>
										<img src="<?php echo ( isset( $pgfw_subcomponent['src'] ) ? esc_attr( $pgfw_subcomponent['src'] ) : '' ); ?>" width="100" height="100" alt="">
										<input
											class="<?php echo esc_attr( array_key_exists( 'class', $pgfw_subcomponent ) ? $pgfw_subcomponent['class'] : '' ); ?>"
											name="<?php echo esc_attr( array_key_exists( 'name', $pgfw_subcomponent ) ? $pgfw_subcomponent['name'] : '' ); ?>"
											id="<?php echo esc_attr( array_key_exists( 'id', $pgfw_subcomponent ) ? $pgfw_subcomponent['id'] : '' ); ?>"
											type="<?php echo esc_attr( array_key_exists( 'type', $pgfw_subcomponent ) ? $pgfw_subcomponent['type'] : '' ); ?>"
											value="<?php echo esc_attr( array_key_exists( 'value', $pgfw_subcomponent ) ? $pgfw_subcomponent['value'] : '' ); ?>"
											<?php checked( $pgfw_component['selected'], $pgfw_subcomponent['value'] ); ?>>
									<?php } ?>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo wp_kses_post( array_key_exists( 'description', $pgfw_component ) ? $pgfw_component['description'] : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'submit':
							?>
							<tr valign="top">
								<td scope="row">
									<input type="submit" class="button button-primary"
										name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
										id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
										class="<?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
										value="<?php echo esc_attr( $pgfw_component['button_text'] ); ?>" />
								</td>
							</tr>
							<?php
							break;
						case 'upload-button':
							?>
							<div class="wps-form-group <?php echo esc_attr( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ); ?>">
								<div class="wps-form-group__label">
									<label for="<?php echo esc_attr( array_key_exists( 'id', $pgfw_component ) ? $pgfw_component['id'] : '' ); ?>" class="wps-form-label"><?php echo esc_html( array_key_exists( 'title', $pgfw_component ) ? $pgfw_component['title'] : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<div class="pgfw-upload-card <?php echo esc_attr( array_key_exists( 'upload-card-class', $pgfw_component ) ? $pgfw_component['upload-card-class'] : '' ); ?> <?php echo ! empty( $pgfw_component['value'] ) ? 'is-filled' : 'is-empty'; ?>" data-pgfw-upload-card>
										<input
											type="hidden"
											id="<?php echo esc_attr( array_key_exists( 'id', $pgfw_component ) ? $pgfw_component['id'] : '' ); ?>"
											class="<?php echo esc_attr( array_key_exists( 'class', $pgfw_component ) ? $pgfw_component['class'] : '' ); ?>"
											name="<?php echo esc_attr( array_key_exists( 'name', $pgfw_component ) ? $pgfw_component['name'] : '' ); ?>"
											value="<?php echo esc_html( array_key_exists( 'value', $pgfw_component ) ? $pgfw_component['value'] : '' ); ?>">
										<div class="pgfw-upload-card__preview">
											<span class="pgfw-upload-card__preview-label"><?php echo esc_html( array_key_exists( 'preview-label', $pgfw_component ) ? $pgfw_component['preview-label'] : __( 'Preview', 'pdf-generator-for-wp' ) ); ?></span>
											<div class="pgfw-upload-card__preview-frame">
												<img
													src="<?php echo esc_attr( $pgfw_component['img-tag']['img-src'] ); ?>"
													class="<?php echo esc_attr( $pgfw_component['img-tag']['img-class'] ); ?>"
													id="<?php echo esc_attr( $pgfw_component['img-tag']['img-id'] ); ?>"
													style="<?php echo esc_attr( $pgfw_component['img-tag']['img-style'] ); ?>"
													alt="<?php echo esc_attr( array_key_exists( 'title', $pgfw_component ) ? $pgfw_component['title'] : '' ); ?>">
												<span class="pgfw-upload-card__empty"><?php echo esc_html( array_key_exists( 'empty-label', $pgfw_component ) ? $pgfw_component['empty-label'] : __( 'No file selected', 'pdf-generator-for-wp' ) ); ?></span>
											</div>
										</div>
										<div class="pgfw-upload-card__actions">
											<button type="button" class="mdc-button--raised <?php echo esc_attr( array_key_exists( 'sub_class', $pgfw_component ) ? $pgfw_component['sub_class'] : '' ); ?>" name="<?php echo esc_attr( array_key_exists( 'sub_name', $pgfw_component ) ? $pgfw_component['sub_name'] : '' ); ?>"
												id="<?php echo esc_attr( array_key_exists( 'sub_id', $pgfw_component ) ? $pgfw_component['sub_id'] : '' ); ?>"> <span class="mdc-button__ripple"></span>
												<span class="mdc-button__label"><?php echo esc_attr( array_key_exists( 'button_text', $pgfw_component ) ? $pgfw_component['button_text'] : '' ); ?></span>
											</button>
											<button type="button" class="mdc-button--raised <?php echo esc_attr( array_key_exists( 'btn-class', $pgfw_component['img-remove'] ) ? $pgfw_component['img-remove']['btn-class'] : '' ); ?>" name="<?php echo esc_attr( $pgfw_component['img-remove']['btn-name'] ); ?>"
												id="<?php echo esc_attr( $pgfw_component['img-remove']['btn-id'] ); ?>"
												style="<?php echo esc_attr( $pgfw_component['img-remove']['btn-style'] ); ?>"> <span class="mdc-button__ripple"></span>
												<span class="mdc-button__label"><?php echo esc_attr( $pgfw_component['img-remove']['btn-title'] ); ?></span>
											</button>
										</div>
										<input
											type="hidden"
											id="<?php echo ( isset( $pgfw_component['img-hidden'] ) ) ? esc_attr( $pgfw_component['img-hidden']['id'] ) : ''; ?>"
											class="<?php echo ( isset( $pgfw_component['img-hidden'] ) ) ? esc_attr( $pgfw_component['img-hidden']['class'] ) : ''; ?>"
											name="<?php echo ( isset( $pgfw_component['img-hidden'] ) ) ? esc_attr( $pgfw_component['img-hidden']['name'] ) : ''; ?>">
									</div>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'sub-text':
							?>
							<div class="sub-text-parent-class">
								<div class="wps-form-group wps-pgfw-<?php echo esc_attr( $pgfw_component['type'] . ' ' . $pgfw_component['class'] . ' ' . ( isset( $pgfw_component['parent-class'] ) ? $pgfw_component['parent-class'] : '' ) ); ?>" style="<?php echo esc_attr( array_key_exists( 'style', $pgfw_component ) ? $pgfw_component['style'] : '' ); ?>">
									<div class="wps-form-group__label">
										<label for="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
									</div>
									<div class="wps-form-group__control">
										<label class="mdc-text-field mdc-text-field--outlined">
											<span class="mdc-notched-outline">
												<span class="mdc-notched-outline__leading"></span>
												<span class="mdc-notched-outline__notch">
													<?php if ( 'number' !== $pgfw_component['type'] ) { ?>
														<span class="mdc-floating-label" id="my-label-id" style=""><?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?></span>
													<?php } ?>
												</span>
												<span class="mdc-notched-outline__trailing"></span>
											</span>
											<input
												class="mdc-text-field__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
												name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
												id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
												type="<?php echo esc_attr( $pgfw_component['type'] ); ?>"
												value="<?php echo ( isset( $pgfw_component['value'] ) ? esc_attr( $pgfw_component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?>"
												<?php echo ( 'number' === $pgfw_component['type'] && isset( $pgfw_component['min'] ) ) ? esc_html( 'min=' . $pgfw_component['min'] ) : ''; ?>
												<?php echo ( 'number' === $pgfw_component['type'] && isset( $pgfw_component['max'] ) ) ? esc_html( 'max=' . $pgfw_component['max'] ) : ''; ?>
												<?php echo isset( $pgfw_component['step'] ) ? esc_html( 'step=' . $pgfw_component['step'] ) : ''; ?>>
										</label>
										<div class="mdc-text-field-helper-line">
											<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
										</div>
									</div>
								</div>
							</div>
							<?php
							break;
						case 'date-picker':
							?>
							<div class="wps-form-group">
								<div class="wps-form-group__label">
									<label class="wps-form-label" for="<?php echo esc_attr( array_key_exists( 'id', $pgfw_component ) ? $pgfw_component['id'] : '' ); ?>"><?php echo esc_attr( array_key_exists( 'title', $pgfw_component ) ? $pgfw_component['title'] : '' ); ?></label>
								</div>
								<div class="wps-form-group__control">
									<?php
									$sub_pgfw_component_value = $pgfw_component['value'];
									?>
									<div class="wps-wpg-date-picker-group">
										<span class="wps-wpg-month-selector"><?php echo esc_attr( $sub_pgfw_component_value['month']['title'] ); ?></span>
										<select name="<?php echo esc_attr( $sub_pgfw_component_value['month']['name'] ); ?>" id="<?php echo esc_attr( $sub_pgfw_component_value['month']['id'] ); ?>" class="<?php echo esc_attr( $sub_pgfw_component_value['month']['class'] ); ?>">
											<?php
											$month_options = $sub_pgfw_component_value['month']['options'];
											foreach ( $month_options as $key => $value ) {
												?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $sub_pgfw_component_value['month']['value'], $key ); ?>><?php echo esc_attr( $value ); ?></option>
												<?php
											}
											?>
										</select>
										<span class="wps-wpg-date-selector"><?php echo esc_attr( $sub_pgfw_component_value['date']['title'] ); ?></span>
										<select name="<?php echo esc_attr( $sub_pgfw_component_value['date']['name'] ); ?>" id="<?php echo esc_attr( $sub_pgfw_component_value['date']['id'] ); ?>" class="<?php echo esc_attr( $sub_pgfw_component_value['date']['class'] ); ?>">
											<?php
											$date_options = $sub_pgfw_component_value['date']['options'];
											foreach ( $date_options as $key => $value ) {
												?>
												<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $sub_pgfw_component_value['date']['value'], $key ); ?>><?php echo esc_attr( $value ); ?></option>
												<?php
											}
											?>
										</select>
									</div>
								</div>
							</div>
							<?php
							break;
						default:
							break;
					}
				}
			}
		}
	}
}
