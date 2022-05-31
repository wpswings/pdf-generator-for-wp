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

			$this->version = '1.0.0';
		}

		$this->plugin_name = 'pdf-generator-for-wp';

		$this->pdf_generator_for_wp_dependencies();
		$this->pdf_generator_for_wp_locale();
		if ( is_admin() ) {
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdf-generator-for-wp-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdf-generator-for-wp-i18n.php';

		if ( is_admin() ) {

			// The class responsible for defining all actions that occur in the admin area.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-pdf-generator-for-wp-admin.php';

			// The class responsible for on-boarding steps for plugin.
			if ( is_dir( plugin_dir_path( dirname( __FILE__ ) ) . 'onboarding' ) && ! class_exists( 'Pdf_Generator_For_Wp_Onboarding_Steps' ) ) {
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-pdf-generator-for-wp-onboarding-steps.php';
			}

			if ( class_exists( 'Pdf_Generator_For_Wp_Onboarding_Steps' ) ) {
				$pgfw_onboard_steps = new Pdf_Generator_For_Wp_Onboarding_Steps();
			}
		} else {

			// The class responsible for defining all actions that occur in the public-facing side of the site.
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-pdf-generator-for-wp-public.php';

		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'package/rest-api/class-pdf-generator-for-wp-rest-api.php';

		/**
		 * This class responsible for defining common functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'common/class-pdf-generator-for-wp-common.php';
		$this->loader = new Pdf_Generator_For_Wp_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Pdf_Generator_For_Wp_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 */
	private function pdf_generator_for_wp_locale() {

		$plugin_i18n = new Pdf_Generator_For_Wp_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

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
		// $thi
		$this->loader->add_action( 'wp_ajax_wpg_ajax_callbacks', $pgfw_plugin_admin, 'wps_wpg_ajax_callbacks' );
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
		$pdf_general_settings_arr = get_option( 'pgfw_general_settings_save', array() );
		$pgfw_enable_plugin       = array_key_exists( 'pgfw_enable_plugin', $pdf_general_settings_arr ) ? $pdf_general_settings_arr['pgfw_enable_plugin'] : '';
		if ( 'yes' === $pgfw_enable_plugin ) {
			// catching pdf generate link with $_GET.
				// catching pdf generate link with $_GET.
			$this->loader->add_action( 'init', $pgfw_plugin_common, 'pgfw_generate_pdf_link_catching_user', 20 );
			$this->loader->add_action( 'plugins_loaded', $pgfw_plugin_common, 'pgfw_poster_download_shortcode' );
			$this->loader->add_action( 'wp_ajax_nopriv_wps_pgfw_ajax_for_single_pdf_mail', $pgfw_plugin_common, 'wps_pgfw_generate_pdf_single_and_mail' );
			$this->loader->add_action( 'wp_ajax_wps_pgfw_ajax_for_single_pdf_mail', $pgfw_plugin_common, 'wps_pgfw_generate_pdf_single_and_mail' );

			$this->loader->add_action( 'load-edit.php', $pgfw_plugin_common, 'mwb_aspose_pdf_exporter_bulk_action' );
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
		$pdf_general_settings_arr     = get_option( 'pgfw_general_settings_save', array() );
		$pgfw_display_settings        = get_option( 'pgfw_save_admin_display_settings', array() );
		$pgfw_enable_plugin           = array_key_exists( 'pgfw_enable_plugin', $pdf_general_settings_arr ) ? $pdf_general_settings_arr['pgfw_enable_plugin'] : '';
		$pgfw_pdf_icon_after          = array_key_exists( 'pgfw_display_pdf_icon_after', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_after'] : '';
		$pgfw_exclude_wp_filter_hooks = array( 'before_content', 'after_content' );
		if ( 'yes' === $pgfw_enable_plugin ) {
			$this->loader->add_action( 'plugins_loaded', $pgfw_plugin_public, 'pgfw_shortcode_to_generate_pdf' );
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
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
	 * Predefined default wps_pgfw_plug tabs.
	 *
	 * @return array An key=>value pair of PDF Generator For WordPress tabs.
	 */
	public function wps_pgfw_plug_default_tabs() {
		$pgfw_default_tabs = array();

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

		$pgfw_default_tabs = apply_filters( 'wps_pgfw_plugin_standard_admin_settings_tabs', $pgfw_default_tabs );

		$pgfw_default_tabs['pdf-generator-for-wp-pdf-upload'] = array(
			'title' => esc_html__( 'PDF Upload', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-pdf-upload',
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
		} else {
			/* translators: %s: file path */
			$pgfw_notice = sprintf( esc_html__( 'Unable to locate file at location "%s". Some features may not work properly in this plugin. Please contact us!', 'pdf-generator-for-wp' ), $pgfw_file_path );
			$this->wps_pgfw_plug_admin_notice( $pgfw_notice, 'error' );
		}
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
		} else {

			/* translators: %s: file path */
			$pgfw_notice = sprintf( esc_html__( 'Unable to locate file at location %s. Some features may not work properly in this plugin. Please contact us!', 'pdf-generator-for-wp' ), $pgfw_file_path );
			$this->wps_pgfw_plug_admin_notice( $pgfw_notice, 'error' );
		}
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
									<?php echo isset( $pgfw_component['step'] ) ? esc_html( 'step=' . $pgfw_component['step'] ) : ''; ?>
									>
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
									placeholder="<?php echo ( isset( $pgfw_component['placeholder'] ) ? esc_attr( $pgfw_component['placeholder'] ) : '' ); ?>"
									>
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
								<label class="mdc-text-field mdc-text-field--outlined mdc-text-field--textarea"  	for="text-field-hero-input">
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
								<br/>
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
									<select id="<?php echo esc_attr( $pgfw_component['id'] ); ?>" name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : '' ); ?><?php echo ( 'multiselect' === $pgfw_component['type'] ) ? '[]' : ''; ?>" id="<?php echo esc_attr( $pgfw_component['id'] ); ?>" class="mdl-textfield__input <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>" <?php echo 'multiselect' === $pgfw_component['type'] ? 'multiple="multiple"' : ''; ?> >
										<?php
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
										<?php checked( $pgfw_component['value'], 'yes' ); ?>
										/>
										<div class="mdc-checkbox__background">
											<svg class="mdc-checkbox__checkmark" viewBox="0 0 24 24">
												<path class="mdc-checkbox__checkmark-path" fill="none" d="M1.73,12.91 8.1,19.28 22.79,4.59"/>
											</svg>
											<div class="mdc-checkbox__mixedmark"></div>
										</div>
										<div class="mdc-checkbox__ripple"></div>
									</div>
									<label for="checkbox-1"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></label>
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
												<?php checked( $pgfw_radio_key, $pgfw_component['value'] ); ?>
												>
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

						case 'radio-switch':
							?>

						<div class="wps-form-group">
							<div class="wps-form-group__label">
								<label for="" class="wps-form-label"><?php echo ( isset( $pgfw_component['title'] ) ? esc_html( $pgfw_component['title'] ) : '' ); ?></label>
							</div>
							<div class="wps-form-group__control">
								<div>
									<div class="mdc-switch">
										<div class="mdc-switch__track"></div>
										<div class="mdc-switch__thumb-underlay">
											<div class="mdc-switch__thumb"></div>
											<input
											name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
											type="checkbox"
											id="<?php echo esc_html( $pgfw_component['id'] ); ?>"
											value="yes"
											class="mdc-switch__native-control <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
											role="switch"
											aria-checked="<?php echo esc_html( 'yes' === $pgfw_component['value'] ) ? 'true' : 'false'; ?>"
											<?php checked( $pgfw_component['value'], 'yes' ); ?>
											>
										</div>
									</div>
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
						<div class="wps-form-group">
							<div class="wps-form-group__label"></div>
							<div class="wps-form-group__control">
								<button type="submit" class="mdc-button mdc-button--raised" name= "<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
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
								<button type="submit" class="<?php echo esc_attr( $pgfw_component['class'] ); ?>" name= "<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
									id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"> <span class="mdc-button__ripple"></span>
									<span class="mdc-button__label <?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"><?php echo ( isset( $pgfw_component['button_text'] ) ? esc_html( $pgfw_component['button_text'] ) : '' ); ?></span>
								</button>
								<span id="<?php echo ( isset( $pgfw_component['loader-id'] ) ? esc_attr( $pgfw_component['loader-id'] ) : '' ); ?>" ></span>
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
												<input 
												class="mdc-text-field__input <?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( 'color' === $component['type'] ) ? 'text' : esc_html( $component['type'] ); ?>"
												value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'min=' . $component['min'] . ' max=' . $component['max'] : '' ); ?>
												>
												<?php if ( 'color' !== $component['type'] ) { ?>
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
												<input 
												class="mdc-text-field__input <?php echo ( isset( $component['class'] ) ? esc_attr( $component['class'] ) : '' ); ?>" 
												name="<?php echo ( isset( $component['name'] ) ? esc_html( $component['name'] ) : esc_html( $component['id'] ) ); ?>"
												id="<?php echo esc_attr( $component['id'] ); ?>"
												type="<?php echo esc_attr( 'color' === $component['type'] ) ? 'text' : esc_html( $component['type'] ); ?>"
												value="<?php echo ( isset( $component['value'] ) ? esc_attr( $component['value'] ) : '' ); ?>"
												placeholder="<?php echo ( isset( $component['placeholder'] ) ? esc_attr( $component['placeholder'] ) : '' ); ?>"
												<?php echo esc_attr( ( 'number' === $component['type'] ) ? 'min=' . $component['min'] . ' max=' . $component['max'] : '' ); ?>
												>
												<?php if ( 'color' !== $component['type'] ) { ?>
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
									type="<?php echo esc_attr( ( 'color' === $pgfw_component['type'] ) ? 'text' : $pgfw_component['type'] ); ?>"
									value="<?php echo ( isset( $pgfw_component['value'] ) ? esc_attr( $pgfw_component['value'] ) : '' ); ?>"
									<?php echo esc_html( ( 'date' === $pgfw_component['type'] ) ? 'max=' . gmdate( 'Y-m-d', strtotime( gmdate( 'Y-m-d', mktime() ) . ' + 365 day' ) ) . ' min=' . gmdate( 'Y-m-d' ) . '' : '' ); ?>
									>
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

						case 'submit':
							?>
						<tr valign="top">
							<td scope="row">
								<input type="submit" class="button button-primary" 
								name="<?php echo ( isset( $pgfw_component['name'] ) ? esc_html( $pgfw_component['name'] ) : esc_html( $pgfw_component['id'] ) ); ?>"
								id="<?php echo esc_attr( $pgfw_component['id'] ); ?>"
								class="<?php echo ( isset( $pgfw_component['class'] ) ? esc_attr( $pgfw_component['class'] ) : '' ); ?>"
								value="<?php echo esc_attr( $pgfw_component['button_text'] ); ?>"
								/>
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
									<input
									type="hidden"
									id="<?php echo esc_attr( array_key_exists( 'id', $pgfw_component ) ? $pgfw_component['id'] : '' ); ?>"
									class="<?php echo esc_attr( array_key_exists( 'class', $pgfw_component ) ? $pgfw_component['class'] : '' ); ?>"
									name="<?php echo esc_attr( array_key_exists( 'name', $pgfw_component ) ? $pgfw_component['name'] : '' ); ?>"
									value="<?php echo esc_html( array_key_exists( 'value', $pgfw_component ) ? $pgfw_component['value'] : '' ); ?>"
									>
									<img
										src="<?php echo esc_attr( $pgfw_component['img-tag']['img-src'] ); ?>"
										class="<?php echo esc_attr( $pgfw_component['img-tag']['img-class'] ); ?>"
										id="<?php echo esc_attr( $pgfw_component['img-tag']['img-id'] ); ?>"
										style="<?php echo esc_attr( $pgfw_component['img-tag']['img-style'] ); ?>"
									>
									<button class="mdc-button--raised" name="<?php echo esc_attr( array_key_exists( 'sub_name', $pgfw_component ) ? $pgfw_component['sub_name'] : '' ); ?>"
										id="<?php echo esc_attr( array_key_exists( 'sub_id', $pgfw_component ) ? $pgfw_component['sub_id'] : '' ); ?>"> <span class="mdc-button__ripple"></span>
										<span class="mdc-button__label"><?php echo esc_attr( array_key_exists( 'button_text', $pgfw_component ) ? $pgfw_component['button_text'] : '' ); ?></span>
									</button>
									<button class="mdc-button--raised" name="<?php echo esc_attr( $pgfw_component['img-remove']['btn-name'] ); ?>"
										id="<?php echo esc_attr( $pgfw_component['img-remove']['btn-id'] ); ?>"
										style="<?php echo esc_attr( $pgfw_component['img-remove']['btn-style'] ); ?>"
										> <span class="mdc-button__ripple"
										></span>
										<span class="mdc-button__label"><?php echo esc_attr( $pgfw_component['img-remove']['btn-title'] ); ?></span>
									</button>
									<input
									type="hidden"
									id="<?php echo ( isset( $pgfw_component['img-hidden'] ) ) ? esc_attr( $pgfw_component['img-hidden']['id'] ) : ''; ?>"
									class="<?php echo ( isset( $pgfw_component['img-hidden'] ) ) ? esc_attr( $pgfw_component['img-hidden']['class'] ) : ''; ?>"
									name="<?php echo ( isset( $pgfw_component['img-hidden'] ) ) ? esc_attr( $pgfw_component['img-hidden']['name'] ) : ''; ?>"
									>
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
										<?php echo isset( $pgfw_component['step'] ) ? esc_html( 'step=' . $pgfw_component['step'] ) : ''; ?>
										>
									</label>
									<div class="mdc-text-field-helper-line">
										<div class="mdc-text-field-helper-text--persistent wps-helper-text" id="" aria-hidden="true"><?php echo ( isset( $pgfw_component['description'] ) ? esc_attr( $pgfw_component['description'] ) : '' ); ?></div>
									</div>
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
