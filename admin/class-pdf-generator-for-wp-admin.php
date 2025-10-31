<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Pdf_Generator_For_Wp_Admin {


	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name       The name of this plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function pgfw_admin_enqueue_styles( $hook ) {
		$screen = get_current_screen();
		if ( isset( $screen->id ) && ( 'wp-swings_page_pdf_generator_for_wp_menu' == $screen->id || 'wp-swings_page_home' == $screen->id ) ) { // phpcs:ignore

			wp_enqueue_style( 'wps-pgfw-select2-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/select-2/pdf-generator-for-wp-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'wps-pgfw-meterial-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'wps-pgfw-meterial-css2', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'wps-pgfw-meterial-lite', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'wps-pgfw-meterial-icons-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/css/pdf-generator-for-wp-admin-global.css', array( 'wps-pgfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_style( 'pgfw-admin-commomn-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/css/pdf-generator-for-wp-admin-common.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'pgfw-datatable-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/datatable/datatables.min.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'pgfw-overview-form-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/css/wps-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'wps--admin--min-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/css/pdf-admin-home.min.css', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'pgfw-admin-custom-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/css/pdf-generator-for-wp-admin-custom.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'flipbook-custom-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/css/flipbook.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function pgfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		$wps_wgm_notice = array(
			'ajaxurl'       => admin_url( 'admin-ajax.php' ),
			'wps_pgfw_nonce' => wp_create_nonce( 'wps-pgfw-verify-notice-nonce' ),
			'check_pro_activate'     => ! wps_pgfw_is_pdf_pro_plugin_active(),

		);
		wp_register_script( $this->plugin_name . 'admin-notice', plugin_dir_url( __FILE__ ) . 'src/js/pdf-generator-for-wp-notices.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . 'admin-notice', 'wps_pgfw_notice', $wps_wgm_notice );
		wp_enqueue_script( $this->plugin_name . 'admin-notice' );
		if ( isset( $screen->id ) && ( 'wp-swings_page_pdf_generator_for_wp_menu' == $screen->id || 'wp-swings_page_home' == $screen->id ) ) { // phpcs:ignore
			wp_enqueue_script( 'wps-pgfw-select2', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/select-2/pdf-generator-for-wp-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'wps-pgfw-metarial-js', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'wps-pgfw-metarial-js2', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'wps-pgfw-metarial-lite', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/js/pdf-generator-for-wp-admin.js', array( 'jquery', 'wps-pgfw-select2', 'wps-pgfw-metarial-js', 'wps-pgfw-metarial-js2', 'wps-pgfw-metarial-lite' ), time(), false );
			$wps_wpg_plugin_list = get_option( 'active_plugins' );
			$wps_wpg_is_pro_active = false;
			$wps_wpg_plugin = 'wordpress-pdf-generator/wordpress-pdf-generator.php';
			if ( in_array( $wps_wpg_plugin, $wps_wpg_plugin_list ) ) {
				$wps_wpg_is_pro_active = true;
			}
			$license_check = get_option( 'wps_wpg_license_check', 0 );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'pgfw_admin_param',
				array(
					'ajaxurl'             => admin_url( 'admin-ajax.php' ),
					'reloadurl'           => admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ),
					'pgfw_gen_tab_enable' => get_option( 'pgfw_radio_switch_demo' ),
					'is_pro_active' => $wps_wpg_is_pro_active,
					'license_check' => $license_check,
					'nonce'         => wp_create_nonce( 'wps_wpg_embed_ajax_nonce' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
			wp_enqueue_media();
			wp_enqueue_script( 'pgfw-datatable-js', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/datatable/datatables.min.js', array( 'jquery' ), $this->version, true );
			wp_enqueue_script( 'wps-pgfw-admin-custom-setting-js', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/js/pdf-generator-for-wp-admin-custom.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
			wp_localize_script(
				'wps-pgfw-admin-custom-setting-js',
				'pgfw_admin_custom_param',
				array(
					'ajaxurl'            => admin_url( 'admin-ajax.php' ),
					'delete_loader'      => esc_html__( 'Deleting....', 'pdf-generator-for-wp' ),
					'nonce'              => wp_create_nonce( 'pgfw_delete_media_by_id' ),
					'pgfw_doc_dummy_img' => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/document-management-big.png',
					'upload_doc'         => esc_html__( 'Upload Doc', 'pdf-generator-for-wp' ),
					'use_doc'            => esc_html__( 'Use Doc', 'pdf-generator-for-wp' ),
					'upload_image'       => esc_html__( 'Upload Image', 'pdf-generator-for-wp' ),
					'upload_invoice_image' => esc_html__( 'Upload Invoice Image', 'pdf-generator-for-wp' ),
					'use_image'          => esc_html__( 'Use Image', 'pdf-generator-for-wp' ),
					'confirm_text'       => esc_html__( 'Are you sure you want to delete Doc ?', 'pdf-generator-for-wp' ),
					'reset_confirm'      => esc_html__( 'Are you sure you want to reset all the settings to default ?', 'pdf-generator-for-wp' ),
					'reset_loader'       => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/loader.gif',
					'reset_success'      => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/checked.png',
					'reset_error'        => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/cross.png',
				)
			);
			$migration_success = get_option( 'wps_code_migratded' );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'src/js/wpg-addon-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-swal', plugin_dir_url( __FILE__ ) . 'src/js/wpg-swal.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( $this->plugin_name . '-wps-swal', plugin_dir_url( __FILE__ ) . 'src/js/wps-wpg-swal.js', array( 'jquery' ), $this->version, false );
			wp_localize_script(
				$this->plugin_name,
				'localised',
				array(
					'ajaxurl'       => admin_url( 'admin-ajax.php' ),
					'nonce'         => wp_create_nonce( 'wps_wpg_migrated_nonce' ),
					'callback'      => 'wpg_ajax_callbacks',
					'pending_settings' => $this->wps_wpg_get_count( 'settings', 'count' ),
					'hide_import'   => $migration_success,
				)
			);
		}
		// Enqueue PDF.js library.
		wp_enqueue_script(
			'pdfjs-library',
			'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.6.347/pdf.min.js',
			array(),
			'2.6.347',
			false
		);

		// Enqueue jQuery (if not already enqueued).
		wp_enqueue_script( 'jquery' );

		// Enqueue custom PDF handler script.
		wp_enqueue_script(
			'wps-pgfw-pdf-handler',
			plugin_dir_url( __FILE__ ) . 'src/js/pdf-flipbook.js',
			array( 'jquery', 'pdfjs-library' ),
			'1.0.0',
			true
		);

		// Localize script with nonces and AJAX URL.
		wp_localize_script(
			'wps-pgfw-pdf-handler',
			'wpsGfwPdf',
			array(
				'fbFetchNonce' => wp_create_nonce( 'fb_fetch_pdf' ),
				'fbUploadNonce' => wp_create_nonce( 'ifb_upload_pdf' ),
				'fbAjaxUrl' => esc_url( admin_url( 'admin-ajax.php' ) ),
			)
		);
		wp_enqueue_script( 'flipbook-js', plugin_dir_url( __FILE__ ) . 'src/js/flipbook.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_media();
	}

	/**
	 * Adding settings menu for PDF Generator For WordPress.
	 *
	 * @since 1.0.0
	 */
	public function pgfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['wps-plugins'] ) ) {
			add_menu_page( 'WP Swings', 'WP Swings', 'manage_options', 'wps-plugins', array( $this, 'wps_plugins_listing_page' ), PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/wpswings_logo.png', 15 );

			add_submenu_page( 'wps-plugins', 'Home', 'Home', 'manage_options', 'home', array( $this, 'wps_pgfw_welcome_callback_function' ), 1 );
			$pgfw_menus = apply_filters( 'wps_add_plugins_menus_array', array() );
			if ( is_array( $pgfw_menus ) && ! empty( $pgfw_menus ) ) {
				foreach ( $pgfw_menus as $pgfw_key => $pgfw_value ) {
					add_submenu_page( 'wps-plugins', $pgfw_value['name'], $pgfw_value['name'], 'manage_options', $pgfw_value['menu_link'], array( $pgfw_value['instance'], $pgfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function wps_pgfw_remove_default_submenu() {
		 global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'wps-plugins', $submenu ) ) {
			if ( isset( $submenu['wps-plugins'][0] ) ) {
				unset( $submenu['wps-plugins'][0] );
			}
		}
	}
	/**
	 * PDF Generator For WordPress pgfw_admin_submenu_page.
	 *
	 * @since 1.0.0
	 * @param array $menus Marketplace menus.
	 */
	public function pgfw_admin_submenu_page( $menus = array() ) {
		$menus[] = array(
			'name'      => __( 'PDF Generator For WP', 'pdf-generator-for-wp' ),
			'slug'      => 'pdf_generator_for_wp_menu',
			'menu_link' => 'pdf_generator_for_wp_menu',
			'instance'  => $this,
			'function'  => 'pgfw_options_menu_html',
		);
		return $menus;
	}


	/**
	 * PDF Generator For WordPress wps_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function wps_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'wps_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * PDF Generator For WordPress admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_options_menu_html() {
		include_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf-generator-for-wp-admin-dashboard.php';
	}
	/**
	 * PDF Generator For WordPress admin menu page.
	 *
	 * @since    1.0.0
	 * @param array $pgfw_settings_general_html_arr Settings fields.
	 * @return array
	 */
	public function pgfw_admin_general_settings_page( $pgfw_settings_general_html_arr ) {
		$general_settings_data     = get_option( 'pgfw_general_settings_save', array() );
		$pgfw_enable_plugin        = array_key_exists( 'pgfw_enable_plugin', $general_settings_data ) ? $general_settings_data['pgfw_enable_plugin'] : '';
		$pgfw_show_post_categories = array_key_exists( 'pgfw_general_pdf_show_categories', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_categories'] : '';
		$pgfw_flipbook_enable = array_key_exists( 'pgfw_flipbook_enable', $general_settings_data ) ? $general_settings_data['pgfw_flipbook_enable'] : '';
		$pgfw_show_post_tags       = array_key_exists( 'pgfw_general_pdf_show_tags', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_tags'] : '';
		$pgfw_show_post_taxonomy   = array_key_exists( 'pgfw_general_pdf_show_taxonomy', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_taxonomy'] : '';
		$pgfw_show_post_date       = array_key_exists( 'pgfw_general_pdf_show_post_date', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_post_date'] : '';
		$pgfw_show_post_author     = array_key_exists( 'pgfw_general_pdf_show_author_name', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_author_name'] : '';
		$pgfw_pdf_generate_mode    = array_key_exists( 'pgfw_general_pdf_generate_mode', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_generate_mode'] : '';
		$pgfw_pdf_file_name        = array_key_exists( 'pgfw_general_pdf_file_name', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_file_name'] : '';
		$pgfw_pdf_file_name_custom = array_key_exists( 'pgfw_custom_pdf_file_name', $general_settings_data ) ? $general_settings_data['pgfw_custom_pdf_file_name'] : '';
		$pgfw_general_pdf_date_format    = array_key_exists( 'pgfw_general_pdf_date_format', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_date_format'] : '';
		$pgfw_show_current_date       = array_key_exists( 'pgfw_general_pdf_show_current_date', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_current_date'] : '';
		// Get the flipbook URL.
		$flipbook_url = admin_url( 'edit.php?post_type=flipbook' );

		// Prepare description based on condition.
		$description = __( 'Enable to convert any PDF or images in flipbook', 'pdf-generator-for-wp' );

		// Add the link only if your specific condition is true.
		if ( 'yes' === $pgfw_flipbook_enable ) {
			$description .= ' <a href="' . esc_url( $flipbook_url ) . '">' . __( 'Visit Here', 'pdf-generator-for-wp' ) . '</a>';
		}
		$pgfw_settings_general_html_arr   = array(
			array(
				'title'       => __( 'Enable Plugin', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable plugin to start the functionality.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_enable_plugin',
				'value'       => $pgfw_enable_plugin,
				'class'       => 'pgfw_enable_plugin',
				'name'        => 'pgfw_enable_plugin',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),

			array(
				'title'        => __( 'Enable Flipbook', 'pdf-generator-for-wp' ),
				'type'         => 'checkbox',
				'description'  => $description,
				'id'           => 'pgfw_flipbook_enable',
				'value'        => $pgfw_flipbook_enable,
				'class'        => 'pgfw_flipbook_enable',
				'name'         => 'pgfw_flipbook_enable',
				'parent-class' => 'pgfw_new-feature',
			),
			array(
				'title'        => __( 'Include Categories', 'pdf-generator-for-wp' ),
				'type'         => 'checkbox',
				'description'  => __( 'Categories will be shown on PDF( for post ).', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_general_pdf_show_categories',
				'value'        => $pgfw_show_post_categories,
				'class'        => 'pgfw_general_pdf_show_categories',
				'name'         => 'pgfw_general_pdf_show_categories',
				'parent-class' => 'wps_pgfw_setting_separate_border',
			),
			array(
				'title'       => __( 'Include Tag', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Tags will be shown on PDF( for post ).', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_general_pdf_show_tags',
				'value'       => $pgfw_show_post_tags,
				'class'       => 'pgfw_general_pdf_show_tags',
				'name'        => 'pgfw_general_pdf_show_tags',
			),
			array(
				'title'       => __( 'Include Taxonomy', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Taxonomy will be shown on PDF( works for all post types ) this also includes category and tags for posts.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_general_pdf_show_taxonomy',
				'value'       => $pgfw_show_post_taxonomy,
				'class'       => 'pgfw_general_pdf_show_taxonomy',
				'name'        => 'pgfw_general_pdf_show_taxonomy',
			),
			array(
				'title'       => __( 'Display Post Date', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Post date will be shown on PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_general_pdf_show_post_date',
				'value'       => $pgfw_show_post_date,
				'class'       => 'pgfw_general_pdf_show_post_date',
				'name'        => 'pgfw_general_pdf_show_post_date',
			),
			array(
				'title'       => __( 'Display Current Date', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Current date will be shown on PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_general_pdf_show_current_date',
				'value'       => $pgfw_show_current_date,
				'class'       => 'pgfw_general_pdf_show_current_date',
				'name'        => 'pgfw_general_pdf_show_current_date',
			),
			array(
				'title'       => __( 'Display Author Name', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Author name will be shown on PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_general_pdf_show_author_name',
				'value'       => $pgfw_show_post_author,
				'class'       => 'pgfw_general_pdf_show_author_name',
				'name'        => 'pgfw_general_pdf_show_author_name',
			),
			array(
				'title'        => __( 'PDF Download Option', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'Please choose either to download or open window.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_general_pdf_generate_mode',
				'value'        => $pgfw_pdf_generate_mode,
				'class'        => 'pgfw_general_pdf_generate_mode',
				'name'         => 'pgfw_general_pdf_generate_mode',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'options'      => array(
					''                 => __( 'Select option', 'pdf-generator-for-wp' ),
					'download_locally' => __( 'Download Locally', 'pdf-generator-for-wp' ),
					'open_window'      => __( 'Open Window', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'        => __( 'Date Format', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'Select date format for your dates on PDF template.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_general_pdf_date_format',
				'value'        => $pgfw_general_pdf_date_format,
				'class'        => 'pgfw_general_pdf_date_format',
				'name'         => 'pgfw_general_pdf_date_format',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'options'      => array(
					'Y/m/d' => 'yyyy/mm/dd',
					'm/d/Y' => 'mm/dd/yyyy',
					'd M Y' => 'd MM yyyy',
					'l, d M Y' => 'DD, d MM yyyy',
					'Y-m-d' => 'yyyy-mm-dd',
					'd/m/Y' => 'dd/mm/yyyy',
					'd.m.Y' => 'd.m.yyyy',
				),
			),
			array(
				'title'       => __( 'Default File Name', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'File name will be used as the name of the PDF generated.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_general_pdf_file_name',
				'value'       => $pgfw_pdf_file_name,
				'class'       => 'pgfw_general_pdf_file_name',
				'name'        => 'pgfw_general_pdf_file_name',
				'options'     => array(
					''                   => __( 'Select option', 'pdf-generator-for-wp' ),
					'post_name'          => __( 'Post Name', 'pdf-generator-for-wp' ),
					'document_productid' => __( 'Document_ProductID', 'pdf-generator-for-wp' ),
					'custom'             => __( 'Custom', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Please Enter the Custom File Name', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'description' => __( 'For custom file name, product/page/post id will be used as suffix.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_custom_pdf_file_name',
				'class'       => 'pgfw_custom_pdf_file_name',
				'name'        => 'pgfw_custom_pdf_file_name',
				'value'       => $pgfw_pdf_file_name_custom,
				'style'       => ( 'custom' !== $pgfw_pdf_file_name ) ? 'display:none;' : '',
				'placeholder' => __( 'File Name', 'pdf-generator-for-wp' ),
			),
		);
		$pgfw_settings_general_html_arr   = apply_filters( 'pgfw_settings_general_html_arr_filter_hook', $pgfw_settings_general_html_arr );
		$pgfw_settings_general_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_general_settings_save',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_general_settings_save',
			'name'        => 'pgfw_general_settings_save',
		);

		return $pgfw_settings_general_html_arr;
	}
	/**
	 * PDF Generator For WordPress save tab settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_admin_save_tab_settings() {
		global $pgfw_wps_pgfw_obj, $wps_pgfw_gen_flag, $pgfw_save_check_flag;
		$settings_general_arr = array();
		$pgfw_save_check_flag = false;
		if ( wp_doing_ajax() ) {
			return;
		}
		if ( isset( $_POST['pgfw_tracking_save_button'] ) ) {

			$enable_tracking = ! empty( $_POST['pgfw_enable_tracking'] ) ? sanitize_text_field( wp_unslash( $_POST['pgfw_enable_tracking'] ) ) : '';
			update_option( 'pgfw_enable_tracking', $enable_tracking );
		}
		if ( isset( $_POST['pgfw_nonce_field'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['pgfw_nonce_field'] ) ), 'nonce_settings_save' ) ) {
			if ( isset( $_POST['pgfw_general_settings_save'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_general_settings_array', array() );
				$key                   = 'pgfw_general_settings_save';
				$pgfw_save_check_flag  = true;
			} elseif ( isset( $_POST['pgfw_save_admin_display_settings'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_display_settings_array', array() );
				$key                   = 'pgfw_save_admin_display_settings';
				$pgfw_save_check_flag  = true;
			} elseif ( isset( $_POST['pgfw_header_setting_submit'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_header_settings_array', array() );
				$key                   = 'pgfw_header_setting_submit';
				$pgfw_save_check_flag  = true;
			} elseif ( isset( $_POST['pgfw_footer_setting_submit'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_footer_settings_array', array() );
				$key                   = 'pgfw_footer_setting_submit';
				$pgfw_save_check_flag  = true;
			} elseif ( isset( $_POST['pgfw_body_save_settings'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_body_settings_array', array() );
				$key                   = 'pgfw_body_save_settings';
				$pgfw_save_check_flag  = true;
			} elseif ( isset( $_POST['pgfw_advanced_save_settings'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_advanced_settings_array', array() );
				$key                   = 'pgfw_advanced_save_settings';
				$pgfw_save_check_flag  = true;
			} elseif ( isset( $_POST['pgfw_meta_fields_save_settings'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_meta_fields_settings_array', array() );
				$key                   = 'pgfw_meta_fields_save_settings';
				$pgfw_save_check_flag  = true;
			} elseif ( isset( $_POST['pgfw_pdf_upload_save_settings'] ) ) {
				$pgfw_genaral_settings = apply_filters( 'pgfw_pdf_upload_fields_settings_array', array() );
				$key                   = 'pgfw_pdf_upload_save_settings';
				$pgfw_save_check_flag  = true;
			}

			if ( $pgfw_save_check_flag ) {
				$wps_pgfw_gen_flag = false;
				$pgfw_button_index = array_search( 'submit', array_column( $pgfw_genaral_settings, 'type' ), true );
				if ( isset( $pgfw_button_index ) && ( null == $pgfw_button_index || '' === $pgfw_button_index ) ) { // phpcs:ignore
					$pgfw_button_index = array_search( 'button', array_column( $pgfw_genaral_settings, 'type' ), true );
				}
				if ( isset( $pgfw_button_index ) && '' !== $pgfw_button_index ) {
					unset( $pgfw_genaral_settings[ $pgfw_button_index ] );
					if ( is_array( $pgfw_genaral_settings ) && ! empty( $pgfw_genaral_settings ) ) {
						foreach ( $pgfw_genaral_settings as $pgfw_genaral_setting ) {
							if ( isset( $pgfw_genaral_setting['id'] ) && '' !== $pgfw_genaral_setting['id'] ) {
								if ( 'multi' === $pgfw_genaral_setting['type'] ) {
									$pgfw_general_settings_sub_arr = $pgfw_genaral_setting['value'];
									foreach ( $pgfw_general_settings_sub_arr as $pgfw_genaral_setting ) {
										if ( isset( $_POST[ $pgfw_genaral_setting['id'] ] ) ) {
											$settings_general_arr[ $pgfw_genaral_setting['id'] ] = is_array( $_POST[ $pgfw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ) );
										} else {
											$settings_general_arr[ $pgfw_genaral_setting['id'] ] = '';
										}
									}
								} elseif ( 'multiwithcheck' === $pgfw_genaral_setting['type'] ) {
									$pgfw_general_settings_sub_arr = $pgfw_genaral_setting['value'];
									foreach ( $pgfw_general_settings_sub_arr as $pgfw_genaral_setting ) {
										if ( isset( $_POST[ $pgfw_genaral_setting['name'] ] ) ) {
											$settings_general_arr[ $pgfw_genaral_setting['name'] ] = is_array( $_POST[ $pgfw_genaral_setting['name'] ] ) ? map_deep( wp_unslash( $_POST[ $pgfw_genaral_setting['name'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $pgfw_genaral_setting['name'] ] ) );
										} else {
											$settings_general_arr[ $pgfw_genaral_setting['name'] ] = '';
										}
										if ( isset( $_POST[ $pgfw_genaral_setting['checkbox_id'] ] ) ) {
											$settings_general_arr[ $pgfw_genaral_setting['checkbox_id'] ] = is_array( $_POST[ $pgfw_genaral_setting['checkbox_id'] ] ) ? map_deep( wp_unslash( $_POST[ $pgfw_genaral_setting['checkbox_id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $pgfw_genaral_setting['checkbox_id'] ] ) );
										} else {
											$settings_general_arr[ $pgfw_genaral_setting['checkbox_id'] ] = '';
										}
									}
								} elseif ( 'file' === $pgfw_genaral_setting['type'] ) {
									if ( isset( $_FILES[ $pgfw_genaral_setting['id'] ]['name'] ) && isset( $_FILES[ $pgfw_genaral_setting['id'] ]['tmp_name'] ) ) {
										$file_name_to_upload = sanitize_text_field( wp_unslash( $_FILES[ $pgfw_genaral_setting['id'] ]['name'] ) );
										$file_to_upload      = sanitize_text_field( wp_unslash( $_FILES[ $pgfw_genaral_setting['id'] ]['tmp_name'] ) );
										$upload_dir          = wp_upload_dir();
										$upload_basedir      = $upload_dir['basedir'] . '/pgfw_ttf_font/';
										if ( ! file_exists( $upload_basedir ) ) {
											wp_mkdir_p( $upload_basedir );
										}
										$target_file = $upload_basedir . basename( $file_name_to_upload );
										$file_type   = strtolower( pathinfo( $target_file, PATHINFO_EXTENSION ) );
										if ( 'ttf' === $file_type ) {
											if ( ! file_exists( $target_file ) ) {
												copy( $file_to_upload, $target_file );
											}
											$settings_general_arr[ $pgfw_genaral_setting['id'] ] = $file_name_to_upload;
										} else {
											$settings_general_arr[ $pgfw_genaral_setting['id'] ] = $pgfw_genaral_setting['value'];
										}
									} else {
										$settings_general_arr[ $pgfw_genaral_setting['id'] ] = '';
									}
								} elseif ( isset( $_POST[ $pgfw_genaral_setting['id'] ] ) ) {
										$settings_general_arr[ $pgfw_genaral_setting['id'] ] = is_array( $_POST[ $pgfw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ) );
								} else {
									$settings_general_arr[ $pgfw_genaral_setting['id'] ] = '';
								}
							} else {
								$wps_pgfw_gen_flag = true;
							}
						}
					}
					if ( ! $wps_pgfw_gen_flag ) {
						update_option( $key, $settings_general_arr );
					}
				}
			}
		}
	}
	/**
	 * Html fields for display setting.
	 *
	 * @since 1.0.0
	 * @param array $pgfw_settings_display_fields_html_arr array containing html fields.
	 * @return array
	 */
	public function pgfw_admin_display_settings_page( $pgfw_settings_display_fields_html_arr ) {
		$pgfw_display_settings                   = get_option( 'pgfw_save_admin_display_settings', array() );
		$pgfw_template_color               = array_key_exists( 'pgfw_template_color', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_template_color'] : '#FFFFFF';
		$pgfw_template_text_color       = array_key_exists( 'pgfw_template_text_color', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_template_text_color'] : '#000000';
		$pgfw_user_access                        = array_key_exists( 'pgfw_user_access', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_user_access'] : '';
		$pgfw_guest_access                       = array_key_exists( 'pgfw_guest_access', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_guest_access'] : '';
		$pgfw_guest_download_or_email            = array_key_exists( 'pgfw_guest_download_or_email', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_guest_download_or_email'] : '';
		$pgfw_user_download_or_email             = array_key_exists( 'pgfw_user_download_or_email', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_user_download_or_email'] : '';
		$pgfw_pdf_icon_after                     = array_key_exists( 'pgfw_display_pdf_icon_after', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_after'] : '';
		$pgfw_pdf_icon_alignment                 = array_key_exists( 'pgfw_display_pdf_icon_alignment', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_alignment'] : '';
		$sub_pgfw_pdf_single_download_icon       = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $pgfw_display_settings ) ? $pgfw_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
		$pgfw_pdf_icon_width                     = array_key_exists( 'pgfw_pdf_icon_width', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_width'] : '';
		$pgfw_pdf_icon_height                    = array_key_exists( 'pgfw_pdf_icon_height', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_height'] : '';
		$pgfw_body_show_pdf_icon                 = array_key_exists( 'pgfw_body_show_pdf_icon', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_body_show_pdf_icon'] : '';
		$pgfw_show_post_type_icons_for_user_role = array_key_exists( 'pgfw_show_post_type_icons_for_user_role', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_show_post_type_icons_for_user_role'] : '';

		$pgfw_template_color_option                 = array_key_exists( 'pgfw_template_color_option', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_template_color_option'] : '';

		global $wp_roles;
		$all_roles = $wp_roles->roles;
		$roles_array = array();

		foreach ( $all_roles as $role => $value ) {

			$roles_array[ $role ] = $role;
		}
		$pgfw_pdf_icon_places              = array(
			''               => __( 'Select option', 'pdf-generator-for-wp' ),
			'before_content' => __( 'Before Content', 'pdf-generator-for-wp' ),
			'after_content'  => __( 'After Content', 'pdf-generator-for-wp' ),
		);
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			$woocommerce_hook_arr = array(
				'woocommerce_before_add_to_cart_form'      => __( 'Before Add to Cart Form', 'pdf-generator-for-wp' ),
				'woocommerce_product_meta_start'           => __( 'Before Product Meta Start', 'pdf-generator-for-wp' ),
				'woocommerce_product_meta_end'             => __( 'After Add to Cart Form', 'pdf-generator-for-wp' ),
				'woocommerce_after_single_product_summary' => __( 'After Single Product Summary', 'pdf-generator-for-wp' ),
				'woocommerce_before_single_product_summary' => __( 'Before Single Product Summary', 'pdf-generator-for-wp' ),
				'woocommerce_after_single_product'         => __( 'After Single Product', 'pdf-generator-for-wp' ),
				'woocommerce_before_single_product'        => __( 'Before Single Product', 'pdf-generator-for-wp' ),
				'woocommerce_share'                        => __( 'After Share Button', 'pdf-generator-for-wp' ),
			);
			foreach ( $woocommerce_hook_arr as $hooks => $name ) {
				$pgfw_pdf_icon_places[ $hooks ] = $name;
			}
		}

		$pgfw_settings_display_fields_html_arr   = array(
			array(
				'title'       => __( 'Logged in Users', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to logged in users to download PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_user_access',
				'value'       => $pgfw_user_access,
				'class'       => 'pgfw_user_access',
				'name'        => 'pgfw_user_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Guest', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to guest users to download PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_guest_access',
				'value'       => $pgfw_guest_access,
				'class'       => 'pgfw_guest_access',
				'name'        => 'pgfw_guest_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),

			array(
				'title'       => __( 'Enable Bulk Download', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to bulk download PDF', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_bulk_download_enable',
				'value'       => '$pgfw_bulk_download_enable',
				'class'       => 'wps_pgfw_pro_tag',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Enable Print Option', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to print current window screen', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_print_enable_org_tag',
				'value'       => 'pgfw_print_enable_org_tag',
				'class'       => 'wps_pgfw_pro_tag',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Enable WhatsApp Sharing Icon', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to share PDF over WhatsApp', 'pdf-generator-for-wp' ),
				'id'          => 'wpg_whatsapp_sharing',
				'value'       => '',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'wpg_whatsapp_sharing',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'        => __( 'Download Invoice icon change option', 'pdf-generator-for-wp' ),
				'type'         => 'upload-button',
				'button_text'  => __( 'Upload Icon', 'pdf-generator-for-wp' ),
				'class'        => 'wps_pgfw_pro_tag',
				'id'           => 'sub_pgfw_pdf_invoice_single_download_icon',
				'value'        => '',
				'sub_id'       => 'pgfw_pdf_invoice_single_download_icon',
				'sub_class'    => 'pgfw_pdf_invoice_single_download_icon',
				'sub_name'     => 'pgfw_pdf_invoice_single_download_icon',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'description'  => __( 'If no icon is chosen default icon will be used.', 'pdf-generator-for-wp' ),
				'img-tag'      => array(
					'img-class' => 'pgfw_single_pdf_icon_image_invoice',
					'img-id'    => 'pgfw_single_pdf_icon_image_invoice',
					'img-style' => ( '' ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
					'img-src'   => '',
				),
				'img-remove'   => array(
					'btn-class' => '',
					'btn-id'    => 'pgfw_single_pdf_invoice_icon_image_remove',
					'btn-text'  => __( 'Remove Icon', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove Icon', 'pdf-generator-for-wp' ),
					'btn-name'  => 'e',
					'btn-style' => '',
				),
			),
			array(
				'title'        => __( 'Direct Download or Email User', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'Please choose either to direct download or to email user.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_user_download_or_email',
				'value'        => $pgfw_user_download_or_email,
				'class'        => 'pgfw_user_download_or_email',
				'name'         => 'pgfw_user_download_or_email',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'options'      => array(
					''                => __( 'Select option', 'pdf-generator-for-wp' ),
					'direct_download' => __( 'Direct Download', 'pdf-generator-for-wp' ),
					'email'           => __( 'Email', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'        => __( 'Direct Download or Email Guest', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'Please choose either to direct download or to email guest.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_guest_download_or_email',
				'value'        => $pgfw_guest_download_or_email,
				'class'        => 'pgfw_guest_download_or_email',
				'name'         => 'pgfw_guest_download_or_email',
				'parent-class' => '',
				'options'      => array(
					''                => __( 'Select option', 'pdf-generator-for-wp' ),
					'direct_download' => __( 'Direct Download', 'pdf-generator-for-wp' ),
					'email'           => __( 'Email', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'        => __( 'Show PDF Icon', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'PDF Icon will be shown after selected space.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_display_pdf_icon_after',
				'value'        => $pgfw_pdf_icon_after,
				'class'        => 'pgfw_display_pdf_icon_after',
				'name'         => 'pgfw_display_pdf_icon_after',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'options'      => $pgfw_pdf_icon_places,
			),
			array(
				'title'       => __( 'PDF Icon Alignment', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'PDF Icon will be aligned according to the selected value.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_display_pdf_icon_alignment',
				'value'       => $pgfw_pdf_icon_alignment,
				'class'       => 'pgfw_display_pdf_icon_alignment',
				'name'        => 'pgfw_display_pdf_icon_alignment',
				'options'     => array(
					''       => __( 'Please Choose', 'pdf-generator-for-wp' ),
					'flex-start'   => __( 'Left', 'pdf-generator-for-wp' ),
					'center' => __( 'Center', 'pdf-generator-for-wp' ),
					'flex-end'  => __( 'Right', 'pdf-generator-for-wp' ),
				),
			),

			array(
				'title'       => __( 'Choose Bulk Download PDF Icon', 'pdf-generator-for-wp' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Icon', 'pdf-generator-for-wp' ),
				'class'       => 'wps_pgfw_pro_tag',
				'id'          => 'wps_pgfw_pdf_bulk_download_icon',
				'value'       => '',
				'sub_id'      => 'wps_pgfw_pdf_bulk_download_icon',
				'sub_class'   => 'wps_pgfw_pdf_bulk_download_icon',
				'sub_name'    => 'wps_pgfw_pdf_bulk_download_icon',
				'name'        => 'wps_pgfw_pdf_bulk_download_icon',
				'description' => __( 'If no icon is chosen default icon will be used', 'pdf-generator-for-wp' ),
				'img-tag'     => array(
					'img-class' => 'wps_bulk_pdf_icon_image',
					'img-id'    => 'wps_bulk_pdf_icon_image',
					'img-style' => ( '' ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
					'img-src'   => '',
				),
				'img-remove'  => array(
					'btn-class' => 'wps_bulk_pdf_icon_image_remove',
					'btn-id'    => 'wps_bulk_pdf_icon_image_remove',
					'btn-text'  => __( 'Remove Icon', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove Icon', 'pdf-generator-for-wp' ),
					'btn-name'  => 'wps_bulk_pdf_icon_image_remove',
					'btn-style' => ! ( '' ) ? 'display:none' : '',
				),
			),

			array(
				'title'       => __( 'Bulk Download PDF Icon Name', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'id'          => 'bulk_pdf_icon_name',
				'value'       => 'bulk download name',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'bulk_pdf_icon_name',
				'placeholder' => __( 'Icon Name', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Single Download PDF Icon Name ', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'id'          => 'single_pdf_icon_name',
				'value'       => 'single pdf name',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'single_pdf_icon_name',
				'placeholder' => __( 'Icon Name', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Icon Size', 'pdf-generator-for-wp' ),
				'type'        => 'multi',
				'id'          => 'pgfw_pdf_icons_sizes',
				'description' => __( 'Enter icon width and height in pixels.', 'pdf-generator-for-wp' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_pdf_icon_width',
						'class'       => 'pgfw_pdf_icon_width',
						'name'        => 'pgfw_pdf_icon_width',
						'placeholder' => __( 'width', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_pdf_icon_width,
						'min'         => 0,
						'max'         => 50,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_pdf_icon_height',
						'class'       => 'pgfw_pdf_icon_height',
						'name'        => 'pgfw_pdf_icon_height',
						'placeholder' => __( 'height', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_pdf_icon_height,
						'min'         => 0,
						'max'         => 50,
					),
				),
			),
			array(
				'title'       => __( 'Show Pdf Icon According To User Roles', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Check this if you want to show download PDF icon according to the user roles .', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_show_pdf_icon',
				'value'       => $pgfw_body_show_pdf_icon,
				'class'       => 'pgfw_body_show_pdf_icon',
				'name'        => 'pgfw_body_show_pdf_icon',
			),
			array(
				'title'       => __( 'Select User Role For Which You Want To Show The PDF Icon', 'pdf-generator-for-wp' ),
				'type'        => 'multiselect',
				'description' => __( 'Select all user roles for which you want to show the PDF download icon  ', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_show_post_type_icons_for_user_role',
				'value'       => $pgfw_show_post_type_icons_for_user_role,
				'class'       => 'pgfw-multiselect-class wps-defaut-multiselect pgfw_show_post_type_icons_for_user_role',
				'name'        => 'pgfw_show_post_type_icons_for_user_role',
				'options'     => $roles_array,
			),
			array(
				'title'        => __( 'Choose Single Download PDF Icon', 'pdf-generator-for-wp' ),
				'type'         => 'upload-button',
				'button_text'  => __( 'Upload Icon', 'pdf-generator-for-wp' ),
				'class'        => 'sub_pgfw_pdf_single_download_icon',
				'id'           => 'sub_pgfw_pdf_single_download_icon',
				'value'        => $sub_pgfw_pdf_single_download_icon,
				'sub_id'       => 'pgfw_pdf_single_download_icon',
				'sub_class'    => 'pgfw_pdf_single_download_icon',
				'sub_name'     => 'pgfw_pdf_single_download_icon',
				'name'         => 'sub_pgfw_pdf_single_download_icon',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'description'  => __( 'If no icon is chosen default icon will be used.', 'pdf-generator-for-wp' ),
				'img-tag'      => array(
					'img-class' => 'pgfw_single_pdf_icon_image',
					'img-id'    => 'pgfw_single_pdf_icon_image',
					'img-style' => ( $sub_pgfw_pdf_single_download_icon ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
					'img-src'   => $sub_pgfw_pdf_single_download_icon,
				),
				'img-remove'   => array(
					'btn-class' => 'pgfw_single_pdf_icon_image_remove',
					'btn-id'    => 'pgfw_single_pdf_icon_image_remove',
					'btn-text'  => __( 'Remove Icon', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove Icon', 'pdf-generator-for-wp' ),
					'btn-name'  => 'pgfw_single_pdf_icon_image_remove',
					'btn-style' => ! ( $sub_pgfw_pdf_single_download_icon ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'PDF Template Color ', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this radio button if you want to change PDF template and text color.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_template_color_option',
				'value'       => $pgfw_template_color_option,
				'class'       => 'pgfw_template_color_option',
				'name'        => 'pgfw_template_color_option',
			),
			array(
				'title'        => __( 'Choose PDF Template Colour', 'pdf-generator-for-wp' ),
				'type'         => 'color',
				'description'  => __( 'Choose color to display PDF Template.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_template_color',
				'value'        => $pgfw_template_color,
				'class'        => 'pgfw_color_picker pgfw_body_font_color',
				'name'         => 'pgfw_template_color',
				'placeholder'  => __( 'color', 'pdf-generator-for-wp' ),
				'parent-class' => 'wps_pgfw_setting_separate_border',
			),
			array(
				'title'        => __( 'Choose PDF Template Text Colour', 'pdf-generator-for-wp' ),
				'type'         => 'color',
				'description'  => __( 'Choose color to display PDF Template Text.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_template_text_color',
				'value'        => $pgfw_template_text_color,
				'class'        => 'pgfw_color_picker pgfw_body_font_color',
				'name'         => 'pgfw_template_text_color',
				'placeholder'  => __( 'color', 'pdf-generator-for-wp' ),
				'parent-class' => 'wps_pgfw_setting_separate_border',
			),

		);
		$pgfw_settings_display_fields_html_arr   = apply_filters( 'pgfw_settings_display_fields_html_arr_filter_hook', $pgfw_settings_display_fields_html_arr );
		$pgfw_settings_display_fields_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_save_admin_display_settings',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_save_admin_display_settings',
			'name'        => 'pgfw_save_admin_display_settings',
		);
		return $pgfw_settings_display_fields_html_arr;
	}
	/**
	 * Html fields for header custmization.
	 *
	 * @since 1.0.0
	 *
	 * @param array $pgfw_settings_header_fields_html_arr array of fields containing html.
	 * @return array
	 */
	public function pgfw_admin_header_settings_page( $pgfw_settings_header_fields_html_arr ) {
		$pgfw_header_settings   = get_option( 'pgfw_header_setting_submit', array() );
		$pgfw_header_use_in_pdf = array_key_exists( 'pgfw_header_use_in_pdf', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_use_in_pdf'] : '';
		$pgfw_header_logo       = array_key_exists( 'sub_pgfw_header_image_upload', $pgfw_header_settings ) ? $pgfw_header_settings['sub_pgfw_header_image_upload'] : '';
		$pgfw_header_comp_name  = array_key_exists( 'pgfw_header_company_name', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_company_name'] : '';
		$pgfw_header_tagline    = array_key_exists( 'pgfw_header_tagline', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_tagline'] : '';
		$pgfw_header_color      = array_key_exists( 'pgfw_header_color', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_color'] : '';
		$pgfw_header_width      = array_key_exists( 'pgfw_header_width', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_width'] : '';
		$pgfw_header_font_style = array_key_exists( 'pgfw_header_font_style', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_font_style'] : '';
		$pgfw_header_font_size  = array_key_exists( 'pgfw_header_font_size', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_font_size'] : '';
		$pgfw_header_top        = array_key_exists( 'pgfw_header_top', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_top'] : '';
		$pgfw_header_logo_size  = array_key_exists( 'pgfw_header_logo_size', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_logo_size'] : '30';
		$wps_pgfw_font_styles   = array(
			''            => __( 'Select option', 'pdf-generator-for-wp' ),
			'helvetica'   => __( 'Helvetica', 'pdf-generator-for-wp' ),
			'courier'     => __( 'Courier', 'pdf-generator-for-wp' ),
			'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wp' ),
			'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wp' ),
			'times-roman' => __( 'Times-Roman', 'pdf-generator-for-wp' ),
			'symbol'      => __( 'Symbol', 'pdf-generator-for-wp' ),
			'zapfdinbats' => __( 'Zapfdinbats', 'pdf-generator-for-wp' ),
		);
		$wps_pgfw_font_styles   = apply_filters( 'wps_pgfw_font_styles_filter_hook', $wps_pgfw_font_styles );

		$pgfw_settings_header_fields_html_arr   = array(
			array(
				'title'       => __( 'Include Header', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this to include header on the page.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_header_use_in_pdf',
				'value'       => $pgfw_header_use_in_pdf,
				'class'       => 'pgfw_header_use_in_pdf',
				'name'        => 'pgfw_header_use_in_pdf',
			),
			array(
				'title'       => __( 'Choose Logo', 'pdf-generator-for-wp' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Image', 'pdf-generator-for-wp' ),
				'sub_class'   => 'pgfw_header_image_upload',
				'sub_id'      => 'pgfw_header_image_upload',
				'id'          => 'sub_pgfw_header_image_upload',
				'name'        => 'sub_pgfw_header_image_upload',
				'class'       => 'sub_pgfw_header_image_upload',
				'value'       => $pgfw_header_logo,
				'sub_name'    => 'pgfw_header_image_upload',
				'img-tag'     => array(
					'img-class' => 'pgfw_header_image',
					'img-id'    => 'pgfw_header_image',
					'img-style' => ( $pgfw_header_logo ) ? 'margin-right:10px;width:100px;height:100px;' : 'display:none;margin:10px;width:100px;height:100px;',
					'img-src'   => $pgfw_header_logo,
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_header_image_remove',
					'btn-id'    => 'pgfw_header_image_remove',
					'btn-text'  => __( 'Remove image', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove image', 'pdf-generator-for-wp' ),
					'btn-name'  => 'pgfw_header_image_remove',
					'btn-style' => ! ( $pgfw_header_logo ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'Logo Size', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'Enter header logo width size in (px) . ', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_header_logo_size',
				'value'       => $pgfw_header_logo_size,
				'class'       => 'pgfw_header_logo_size',
				'name'        => 'pgfw_header_logo_size',
				'placeholder' => __( 'width', 'pdf-generator-for-wp' ),
				'min'         => 5,
				'max'         => 150,
			),
			array(
				'title'       => __( 'Company Name', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'description' => __( 'Company name will be displayed on the right side of the header', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_header_company_name',
				'value'       => $pgfw_header_comp_name,
				'class'       => 'pgfw_header_company_name',
				'name'        => 'pgfw_header_company_name',
				'placeholder' => __( 'company name', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Tagline or Address', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'class'       => 'pgfw_header_tagline',
				'id'          => 'pgfw_header_tagline',
				'name'        => 'pgfw_header_tagline',
				'description' => __( 'Enter the tagline or address to show in header', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'tagline or address', 'pdf-generator-for-wp' ),
				'value'       => $pgfw_header_tagline,
			),
			array(
				'title'       => __( 'Choose Color', 'pdf-generator-for-wp' ),
				'type'        => 'color',
				'description' => __( 'Please choose text color to display in the header', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_header_color',
				'value'       => $pgfw_header_color,
				'class'       => 'pgfw_color_picker pgfw_header_color',
				'name'        => 'pgfw_header_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Header Width', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'Please enter width to display in the header accepted values are in px, please enter number only', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_header_width',
				'value'       => $pgfw_header_width,
				'class'       => 'pgfw_header_width',
				'name'        => 'pgfw_header_width',
				'placeholder' => __( 'width', 'pdf-generator-for-wp' ),
				'min'         => 5,
				'max'         => 30,
			),
			array(
				'title'       => __( 'Choose Font Style', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'Please choose font style to display in the header', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_header_font_style',
				'value'       => $pgfw_header_font_style,
				'class'       => 'pgfw_header_font_style',
				'name'        => 'pgfw_header_font_style',
				'placeholder' => __( 'font style', 'pdf-generator-for-wp' ),
				'options'     => $wps_pgfw_font_styles,
			),
			array(
				'title'       => __( 'Choose Font Size', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'Please choose font size to display in the header', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_header_font_size',
				'value'       => $pgfw_header_font_size,
				'class'       => 'pgfw_header_font_size',
				'name'        => 'pgfw_header_font_size',
				'placeholder' => __( 'font size', 'pdf-generator-for-wp' ),
				'min'         => 5,
				'max'         => 30,
			),
			array(
				'title'        => __( 'Header Top Placement', 'pdf-generator-for-wp' ),
				'type'         => 'number',
				'description'  => __( 'The greater the value in the Header Top, more will be the header length down from the top. Accepted values are positive and negative. If there exists an issue with the header placement, the header top value should be changed.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_header_top',
				'value'        => $pgfw_header_top,
				'class'        => 'pgfw_header_top',
				'name'         => 'pgfw_header_top',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'placeholder'  => __( 'top', 'pdf-generator-for-wp' ),
				'min'          => -500,
				'max'          => 500,
			),
		);
		$pgfw_settings_header_fields_html_arr   = apply_filters( 'pgfw_settings_header_fields_html_arr_filter_hook', $pgfw_settings_header_fields_html_arr );
		$pgfw_settings_header_fields_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_header_setting_submit',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_header_setting_submit',
			'name'        => 'pgfw_header_setting_submit',
		);
		return $pgfw_settings_header_fields_html_arr;
	}

	/**
	 * Html fields for footer custmization.
	 *
	 * @since 1.0.0
	 *
	 * @param array $pgfw_settings_footer_fields_html_arr array of fields containing html.
	 * @return array
	 */
	public function pgfw_admin_footer_settings_page( $pgfw_settings_footer_fields_html_arr ) {
		$pgfw_footer_settings   = get_option( 'pgfw_footer_setting_submit', array() );
		$pgfw_footer_use_in_pdf = array_key_exists( 'pgfw_footer_use_in_pdf', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_use_in_pdf'] : '';
		$pgfw_footer_tagline    = array_key_exists( 'pgfw_footer_tagline', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_tagline'] : '';
		$pgfw_footer_color      = array_key_exists( 'pgfw_footer_color', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_color'] : '';
		$pgfw_footer_width      = array_key_exists( 'pgfw_footer_width', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_width'] : '';
		$pgfw_footer_font_style = array_key_exists( 'pgfw_footer_font_style', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_font_style'] : '';
		$pgfw_footer_font_size  = array_key_exists( 'pgfw_footer_font_size', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_font_size'] : '';
		$pgfw_footer_bottom     = array_key_exists( 'pgfw_footer_bottom', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_bottom'] : '';
		$pgfw_footer_customization     = array_key_exists( 'pgfw_footer_customization_for_post_detail', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_customization_for_post_detail'] : array();
		$wps_pgfw_font_styles   = array(
			''            => __( 'Select option', 'pdf-generator-for-wp' ),
			'helvetica'   => __( 'Helvetica', 'pdf-generator-for-wp' ),
			'courier'     => __( 'Courier', 'pdf-generator-for-wp' ),
			'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wp' ),
			'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wp' ),
			'times-roman' => __( 'Times-Roman', 'pdf-generator-for-wp' ),
			'symbol'      => __( 'Symbol', 'pdf-generator-for-wp' ),
			'zapfdinbats' => __( 'Zapfdinbats', 'pdf-generator-for-wp' ),
		);
		$wps_pgfw_font_styles   = apply_filters( 'wps_pgfw_font_styles_filter_hook', $wps_pgfw_font_styles );

		$pgfw_settings_footer_fields_html_arr   = array(
			array(
				'title'       => __( 'Include Footer', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this include footer on the page.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_footer_use_in_pdf',
				'value'       => $pgfw_footer_use_in_pdf,
				'class'       => 'pgfw_footer_use_in_pdf',
				'name'        => 'pgfw_footer_use_in_pdf',
			),
			array(
				'title'       => __( 'Tagline', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'class'       => 'pgfw_footer_tagline',
				'id'          => 'pgfw_footer_tagline',
				'name'        => 'pgfw_footer_tagline',
				'description' => __( 'Enter the tagline to show in footer', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'tagline', 'pdf-generator-for-wp' ),
				'value'       => $pgfw_footer_tagline,
			),
			array(
				'title'       => __( 'Choose Color', 'pdf-generator-for-wp' ),
				'type'        => 'color',
				'description' => __( 'Please choose color to display in the footer text.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_footer_color',
				'value'       => $pgfw_footer_color,
				'class'       => 'pgfw_color_picker pgfw_footer_color',
				'name'        => 'pgfw_footer_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Choose Width', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'Please choose width to display in the footer accepted values are in px, please enter number only.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_footer_width',
				'value'       => $pgfw_footer_width,
				'class'       => 'pgfw_footer_width',
				'name'        => 'pgfw_footer_width',
				'placeholder' => __( 'width', 'pdf-generator-for-wp' ),
				'min'         => 0,
				'max'         => 300,
			),
			array(
				'title'       => __( 'Choose Font Style', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'Please choose font style to display in the footer.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_footer_font_style',
				'value'       => $pgfw_footer_font_style,
				'class'       => 'pgfw_footer_font_style',
				'name'        => 'pgfw_footer_font_style',
				'placeholder' => __( 'font style', 'pdf-generator-for-wp' ),
				'options'     => $wps_pgfw_font_styles,
			),
			array(
				'title'       => __( 'Choose Font Size', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'Please choose font size to display in the footer.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_footer_font_size',
				'value'       => $pgfw_footer_font_size,
				'class'       => 'pgfw_footer_font_size',
				'name'        => 'pgfw_footer_font_size',
				'placeholder' => __( 'font size', 'pdf-generator-for-wp' ),
			),
			array(
				'title'        => __( 'Footer Bottom Placement', 'pdf-generator-for-wp' ),
				'type'         => 'number',
				'description'  => __( 'The greater the value in the Footer Bottom, more will be the footer length up from the bottom. Accepted values are positive and negative. If there exists an issue with the footer placement, the footer bottom value should be changed.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_footer_bottom',
				'value'        => $pgfw_footer_bottom,
				'class'        => 'pgfw_footer_bottom',
				'name'         => 'pgfw_footer_bottom',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'placeholder'  => __( 'bottom', 'pdf-generator-for-wp' ),
				'min'          => -500,
				'max'          => 500,
			),
			array(
				'title'       => __( 'Add Author, Post name and Date in Footer.', 'pdf-generator-for-wp' ),
				'type'        => 'multiselect',
				'description' => __( 'You have the option to customize the footer to include the authors name, the post title, and the publication date.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_footer_customization_for_post_detail',
				'value'       => $pgfw_footer_customization,
				'class'       => 'pgfw-multiselect-class wps-defaut-multiselect pgfw_advanced_show_post_type_icons',
				'name'        => 'pgfw_footer_customization_for_post_detail',
				'options'     => array(
					'author' => __( 'author name', 'pdf-generator-for-wp' ),
					'post_title'      => __( 'post title', 'pdf-generator-for-wp' ),
					'post_date'      => __( 'publish date', 'pdf-generator-for-wp' ),
				),
			),
		);

		$pgfw_settings_footer_fields_html_arr[] = array(
			'title'        => __( 'Change Page No Format', 'pdf-generator-for-wp' ),
			'type'         => 'checkbox',
			'description'  => __( 'Check this if you want to show page no with total page count example ( 1 / 20 ).', 'pdf-generator-for-wp' ),
			'id'           => 'pgfw_general_pdf_show_pageno',
			'value'        => '',
			'class'        => 'wps_pgfw_pro_tag',
			'name'         => 'pgfw_general_pdf_show_pageno',
			'parent-class' => 'wps_pgfw_setting_separate_border',
		);
		$pgfw_settings_footer_fields_html_arr[] = array(
			'title'       => __( 'Page number position', 'pdf-generator-for-wp' ),
			'type'        => 'multi',
			'class'       => 'wps_pgfw_pro_tag',
			'id'          => 'pgfw_wartermark_position',
			'description' => __( 'Choose page number position left, top in px.', 'pdf-generator-for-wp' ),
			'value'       => array(
				array(
					'type'        => 'number',
					'id'          => 'pgfw_pageno_position_left',
					'class'       => 'wps_pgfw_pro_tag',
					'name'        => 'pgfw_pageno_position_left',
					'placeholder' => __( 'left', 'pdf-generator-for-wp' ),
					'value'       => 100,
					'min'         => -5000,
					'max'         => 5000,
				),
				array(
					'type'        => 'number',
					'id'          => 'pgfw_pageno_position_top',
					'class'       => 'wps_pgfw_pro_tag',
					'name'        => 'pgfw_pageno_position_top',
					'placeholder' => __( 'top', 'pdf-generator-for-wp' ),
					'value'       => 100,
					'min'         => -5000,
					'max'         => 5000,
				),
			),
		);

		$pgfw_settings_footer_fields_html_arr   = apply_filters( 'pgfw_settings_footer_fields_html_arr_filter_hook', $pgfw_settings_footer_fields_html_arr );
		$pgfw_settings_footer_fields_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_footer_setting_submit',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_footer_setting_submit',
			'name'        => 'pgfw_footer_setting_submit',
		);

		return $pgfw_settings_footer_fields_html_arr;
	}
	/**
	 * Html fields for body customizations.
	 *
	 * @since 1.0.0
	 * @param array $pgfw_body_html_arr array containing fields for body customizations.
	 * @return array
	 */
	public function pgfw_admin_body_settings_page( $pgfw_body_html_arr ) {
		$pgfw_body_settings          = get_option( 'pgfw_body_save_settings', array() );
		$pgfw_body_title_font_style  = array_key_exists( 'pgfw_body_title_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_style'] : '';
		$pgfw_body_title_font_size   = array_key_exists( 'pgfw_body_title_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_size'] : '';
		$pgfw_body_title_font_color  = array_key_exists( 'pgfw_body_title_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_color'] : '';
		$pgfw_body_page_size         = array_key_exists( 'pgfw_body_page_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_size'] : '';
		$pgfw_body_page_orientation  = array_key_exists( 'pgfw_body_page_orientation', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_orientation'] : '';
		$pgfw_body_page_font_style   = array_key_exists( 'pgfw_body_page_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_font_style'] : '';
		$pgfw_body_page_font_size    = array_key_exists( 'pgfw_content_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_content_font_size'] : '';
		$pgfw_body_page_font_color   = array_key_exists( 'pgfw_body_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_font_color'] : '';
		$pgfw_body_border_size       = array_key_exists( 'pgfw_body_border_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_border_size'] : '';
		$pgfw_body_border_color      = array_key_exists( 'pgfw_body_border_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_border_color'] : '';
		$pgfw_body_margin_top        = array_key_exists( 'pgfw_body_margin_top', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_top'] : '';
		$pgfw_body_margin_left       = array_key_exists( 'pgfw_body_margin_left', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_left'] : '';
		$pgfw_body_margin_right      = array_key_exists( 'pgfw_body_margin_right', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_right'] : '';
		$pgfw_body_margin_bottom     = array_key_exists( 'pgfw_body_margin_bottom', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_bottom'] : '';
		$pgfw_body_rtl_support       = array_key_exists( 'pgfw_body_rtl_support', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_rtl_support'] : '';
		$pgfw_body_add_watermark     = array_key_exists( 'pgfw_body_add_watermark', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_add_watermark'] : '';
		$pgfw_body_metafields_row_wise     = array_key_exists( 'pgfw_body_metafields_row_wise', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_metafields_row_wise'] : '';
		$pgfw_body_images_row_wise     = array_key_exists( 'pgfw_body_images_row_wise', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_images_row_wise'] : '';
		$pgfw_body_watermark_text    = array_key_exists( 'pgfw_body_watermark_text', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_watermark_text'] : '';
		$pgfw_body_watermark_color   = array_key_exists( 'pgfw_body_watermark_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_watermark_color'] : '';
		$pgfw_body_page_template     = array_key_exists( 'pgfw_body_page_template', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_template'] : '';
		$pgfw_body_post_template     = array_key_exists( 'pgfw_body_post_template', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_post_template'] : '';
		$pgfw_body_meta_field_column     = array_key_exists( 'pgfw_body_meta_field_column', $pgfw_body_settings ) ? intval( $pgfw_body_settings['pgfw_body_meta_field_column'] ) : '';
		$pgfw_border_position_top    = array_key_exists( 'pgfw_border_position_top', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_top'] : '';
		$pgfw_border_position_bottom = array_key_exists( 'pgfw_border_position_bottom', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_bottom'] : '';
		$pgfw_border_position_left   = array_key_exists( 'pgfw_border_position_left', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_left'] : '';
		$pgfw_border_position_right  = array_key_exists( 'pgfw_border_position_right', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_right'] : '';
		$pgfw_body_custom_css        = array_key_exists( 'pgfw_body_custom_css', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_custom_css'] : '';
		$pgfw_body_custom_page_size_height        = array_key_exists( 'pgfw_body_custom_page_size_height', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_custom_page_size_height'] : 150;
		$pgfw_body_custom_page_size_width        = array_key_exists( 'pgfw_body_custom_page_size_width', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_custom_page_size_width'] : 150;
		$pgfw_body_customization                 = array_key_exists( 'pgfw_body_customization_for_post_detail', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_customization_for_post_detail'] : array();
		$pgfw_body_spcl_char_support       = array_key_exists( 'pgfw_body_spcl_char_support', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_spcl_char_support'] : '';

		$wps_pgfw_font_styles = array(
			''            => __( 'Select option', 'pdf-generator-for-wp' ),
			'helvetica'   => __( 'Helvetica', 'pdf-generator-for-wp' ),
			'courier'     => __( 'Courier', 'pdf-generator-for-wp' ),
			'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wp' ),
			'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wp' ),
			'times-roman' => __( 'Times-Roman', 'pdf-generator-for-wp' ),
			'symbol'      => __( 'Symbol', 'pdf-generator-for-wp' ),
			'zapfdinbats' => __( 'Zapfdinbats', 'pdf-generator-for-wp' ),
		);

		$wps_pgfw_font_styles = apply_filters( 'wps_pgfw_font_styles_filter_hook', $wps_pgfw_font_styles );
		$wps_pgfw_custom_page_size = array(
			''                         => __( 'Select option', 'pdf-generator-for-wp' ),
			'4a0'                      => __( '4A0', 'pdf-generator-for-wp' ),
			'2a0'                      => __( '2A0', 'pdf-generator-for-wp' ),
			'a0'                       => __( 'A0', 'pdf-generator-for-wp' ),
			'a1'                       => __( 'A1', 'pdf-generator-for-wp' ),
			'a2'                       => __( 'A2', 'pdf-generator-for-wp' ),
			'a3'                       => __( 'A3', 'pdf-generator-for-wp' ),
			'a4'                       => __( 'A4', 'pdf-generator-for-wp' ),
			'a5'                       => __( 'A5', 'pdf-generator-for-wp' ),
			'a6'                       => __( 'A6', 'pdf-generator-for-wp' ),
			'b0'                       => __( 'B0', 'pdf-generator-for-wp' ),
			'b1'                       => __( 'B1', 'pdf-generator-for-wp' ),
			'b2'                       => __( 'B2', 'pdf-generator-for-wp' ),
			'b3'                       => __( 'B3', 'pdf-generator-for-wp' ),
			'b4'                       => __( 'B4', 'pdf-generator-for-wp' ),
			'b5'                       => __( 'B5', 'pdf-generator-for-wp' ),
			'b6'                       => __( 'B6', 'pdf-generator-for-wp' ),
			'c0'                       => __( 'C0', 'pdf-generator-for-wp' ),
			'c1'                       => __( 'C1', 'pdf-generator-for-wp' ),
			'c2'                       => __( 'C2', 'pdf-generator-for-wp' ),
			'c3'                       => __( 'C3', 'pdf-generator-for-wp' ),
			'c4'                       => __( 'C4', 'pdf-generator-for-wp' ),
			'c5'                       => __( 'C5', 'pdf-generator-for-wp' ),
			'c6'                       => __( 'C6', 'pdf-generator-for-wp' ),
			'ra0'                      => __( 'RA0', 'pdf-generator-for-wp' ),
			'ra1'                      => __( 'RA1', 'pdf-generator-for-wp' ),
			'ra2'                      => __( 'RA2', 'pdf-generator-for-wp' ),
			'ra3'                      => __( 'RA3', 'pdf-generator-for-wp' ),
			'ra4'                      => __( 'RA4', 'pdf-generator-for-wp' ),
			'sra0'                     => __( 'SRA0', 'pdf-generator-for-wp' ),
			'sra1'                     => __( 'SRA1', 'pdf-generator-for-wp' ),
			'sra2'                     => __( 'SRA2', 'pdf-generator-for-wp' ),
			'sra3'                     => __( 'SRA3', 'pdf-generator-for-wp' ),
			'sra4'                     => __( 'SRA4', 'pdf-generator-for-wp' ),
			'letter'                   => __( 'Letter', 'pdf-generator-for-wp' ),
			'legal'                    => __( 'Legal', 'pdf-generator-for-wp' ),
			'executive'                => __( 'Executive', 'pdf-generator-for-wp' ),
			'ledger'                   => __( 'Ledger', 'pdf-generator-for-wp' ),
			'tabloid'                  => __( 'Tabloid', 'pdf-generator-for-wp' ),
			'folio'                    => __( 'Folio', 'pdf-generator-for-wp' ),
			'commercial #10 envelope'  => __( 'Commercial Envelope', 'pdf-generator-for-wp' ),
			'catalog #10 1/2 envelope' => __( 'Catalog Envelope', 'pdf-generator-for-wp' ),
			'8.5x11'                   => '8.5x11',
			'8.5x14'                   => '8.5x14',
			'11x17'                    => '11x17',
		);
		$wps_pgfw_custom_page_size = apply_filters( 'wps_pgfw_custom_page_size_filter_hook', $wps_pgfw_custom_page_size );

		$pgfw_body_html_arr   = array(
			array(
				'title'       => __( 'Title Font Style', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'Please choose title font style.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_title_font_style',
				'value'       => $pgfw_body_title_font_style,
				'class'       => 'pgfw_body_title_font_style',
				'name'        => 'pgfw_body_title_font_style',
				'placeholder' => __( 'title font_style', 'pdf-generator-for-wp' ),
				'options'     => $wps_pgfw_font_styles,
			),
			array(
				'title'       => __( 'Title Font Size.', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'This will be the font size of the title.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_title_font_size',
				'value'       => $pgfw_body_title_font_size,
				'class'       => 'pgfw_body_title_font_size',
				'name'        => 'pgfw_body_title_font_size',
				'placeholder' => __( 'title font size', 'pdf-generator-for-wp' ),
				'min'         => 5,
				'max'         => 50,
			),
			array(
				'title'       => __( 'Choose Title Color', 'pdf-generator-for-wp' ),
				'type'        => 'color',
				'description' => __( 'Please choose color to display the title text.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_title_font_color',
				'value'       => $pgfw_body_title_font_color,
				'class'       => 'pgfw_color_picker pgfw_body_title_font_color',
				'name'        => 'pgfw_body_title_font_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wp' ),
			),
			array(
				'title'        => __( 'Page Size', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'Please choose page size to generate PDF.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_body_page_size',
				'value'        => $pgfw_body_page_size,
				'class'        => 'pgfw_body_page_size',
				'name'         => 'pgfw_body_page_size',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'placeholder'  => __( 'page size', 'pdf-generator-for-wp' ),
				'options'      => $wps_pgfw_custom_page_size,
			),
			array(
				'title'       => __( 'Height of the page ( in mm )', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'id'          => 'pgfw_body_custom_page_size_height',
				'class'       => 'pgfw_body_custom_page_size_height',
				'name'        => 'pgfw_body_custom_page_size_height',
				'value'       => $pgfw_body_custom_page_size_height,
				'min'         => 150,
				'max'         => 1500,
				'style'       => ( 'custom_page' !== $pgfw_body_page_size ) ? 'display:none;' : '',
				'placeholder' => 'Height ( in mm )',
			),
			array(
				'title'       => __( 'Width of the page ( in mm )', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'id'          => 'pgfw_body_custom_page_size_width',
				'class'       => 'pgfw_body_custom_page_size_width',
				'name'        => 'pgfw_body_custom_page_size_width',
				'value'       => $pgfw_body_custom_page_size_width,
				'min'         => 150,
				'max'         => 1500,
				'style'       => ( 'custom_page' !== $pgfw_body_page_size ) ? 'display:none;' : '',
				'placeholder' => 'Width ( in mm )',
			),
			array(
				'title'       => __( 'Page Orientation', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'Choose page orientation to generate PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_page_orientation',
				'value'       => $pgfw_body_page_orientation,
				'class'       => 'pgfw_body_page_orientation',
				'name'        => 'pgfw_body_page_orientation',
				'placeholder' => __( 'page orientation', 'pdf-generator-for-wp' ),
				'options'     => array(
					''          => __( 'Select option', 'pdf-generator-for-wp' ),
					'landscape' => __( 'Landscape', 'pdf-generator-for-wp' ),
					'portrait'  => __( 'Portrait', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'        => __( 'Content Font Style', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'Choose page font to generate PDF.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_body_page_font_style',
				'value'        => $pgfw_body_page_font_style,
				'class'        => 'pgfw_body_page_font_style',
				'name'         => 'pgfw_body_page_font_style',
				'placeholder'  => __( 'page font', 'pdf-generator-for-wp' ),
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'options'      => $wps_pgfw_font_styles,
			),
			array(
				'title'       => __( 'Content Font Size', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'Choose content font size to generate PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_content_font_size',
				'value'       => $pgfw_body_page_font_size,
				'class'       => 'pgfw_content_font_size',
				'placeholder' => '',
			),
			array(
				'title'        => __( 'Choose Body Text Color', 'pdf-generator-for-wp' ),
				'type'         => 'color',
				'description'  => __( 'Choose color to display body text.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_body_font_color',
				'value'        => $pgfw_body_page_font_color,
				'class'        => 'pgfw_color_picker pgfw_body_font_color',
				'name'         => 'pgfw_body_font_color',
				'placeholder'  => __( 'color', 'pdf-generator-for-wp' ),
				'parent-class' => 'wps_pgfw_setting_separate_border',
			),
			array(
				'title'       => __( 'Border', 'pdf-generator-for-wp' ),
				'type'        => 'multi',
				'id'          => 'pgfw_body_border',
				'description' => __( 'Choose border: size in px and color.', 'pdf-generator-for-wp' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_border_size',
						'class'       => 'pgfw_body_border_size',
						'name'        => 'pgfw_body_border_size',
						'placeholder' => __( 'border size', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_body_border_size,
						'min'         => 0,
						'max'         => 50,
					),
					array(
						'type'        => 'color',
						'id'          => 'pgfw_body_border_color',
						'class'       => 'pgfw_color_picker pgfw_body_border_color',
						'name'        => 'pgfw_body_border_color',
						'placeholder' => __( 'border color', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_body_border_color,
					),
				),
			),
			array(
				'title'       => __( 'PDF Border Position', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_border_position',
				'type'        => 'multi',
				'description' => __( 'Enter Border margin : top, left, right, bottom, accepted values are positive and negative, this will decide the position of border on the page.', 'pdf-generator-for-wp' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_border_position_top',
						'class'       => 'pgfw_border_position_top',
						'name'        => 'pgfw_border_position_top',
						'placeholder' => __( 'Top', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_border_position_top,
						'min'         => -500,
						'max'         => 500,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_border_position_left',
						'class'       => 'pgfw_border_position_left',
						'name'        => 'pgfw_border_position_left',
						'placeholder' => __( 'Left', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_border_position_left,
						'min'         => -500,
						'max'         => 500,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_border_position_right',
						'class'       => 'pgfw_border_position_right',
						'name'        => 'pgfw_border_position_right',
						'placeholder' => __( 'Right', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_border_position_right,
						'min'         => -500,
						'max'         => 500,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_border_position_bottom',
						'class'       => 'pgfw_border_position_bottom',
						'name'        => 'pgfw_border_position_bottom',
						'placeholder' => __( 'Bottom', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_border_position_bottom,
						'min'         => -500,
						'max'         => 500,
					),
				),
			),
			array(
				'title'       => __( 'Page Margin', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_margin',
				'type'        => 'multi',
				'description' => __( 'Enter page margin : top, left, right, bottom, set top and bottom values if any issue with content placement, while changing the header and footer width, margin top and margin bottom must be set from here to display correctly on the page.', 'pdf-generator-for-wp' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_top',
						'class'       => 'pgfw_body_margin_top',
						'name'        => 'pgfw_body_margin_top',
						'placeholder' => __( 'Top', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_body_margin_top,
						'min'         => -500,
						'max'         => 500,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_left',
						'class'       => 'pgfw_body_margin_left',
						'name'        => 'pgfw_body_margin_left',
						'placeholder' => __( 'Left', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_body_margin_left,
						'min'         => -500,
						'max'         => 500,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_right',
						'class'       => 'pgfw_body_margin_right',
						'name'        => 'pgfw_body_margin_right',
						'placeholder' => __( 'Right', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_body_margin_right,
						'min'         => -500,
						'max'         => 500,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_bottom',
						'class'       => 'pgfw_body_margin_bottom',
						'name'        => 'pgfw_body_margin_bottom',
						'placeholder' => __( 'Bottom', 'pdf-generator-for-wp' ),
						'value'       => $pgfw_body_margin_bottom,
						'min'         => -500,
						'max'         => 500,
					),
				),
			),
			array(
				'title'        => __( 'Special Character Support', 'pdf-generator-for-wp' ),
				'type'         => 'checkbox',
				'description'  => __( 'Select this to enable special character support ( enabling this will enable, font-style : DejaVu Sans, sans-serif globally ) and will support special character.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_body_spcl_char_support',
				'value'        => $pgfw_body_spcl_char_support,
				'class'        => 'pgfw_body_spcl_char_support',
				'name'         => 'pgfw_body_spcl_char_support',
				'parent-class' => 'wps_pgfw_setting_separate_border',
			),
			array(
				'title'        => __( 'RTL Support', 'pdf-generator-for-wp' ),
				'type'         => 'checkbox',
				'description'  => __( 'Select this to enable RTL support ( enabling this will enable, font-style : DejaVu Sans, sans-serif globally ) and will support right to left text alignment.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_body_rtl_support',
				'value'        => $pgfw_body_rtl_support,
				'class'        => 'pgfw_body_rtl_support',
				'name'         => 'pgfw_body_rtl_support',
				'parent-class' => 'wps_pgfw_setting_separate_border',
			),
			array(
				'title'       => __( 'Add Watermark Text', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this to add watermark text on the created PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_add_watermark',
				'value'       => $pgfw_body_add_watermark,
				'class'       => 'pgfw_body_add_watermark',
				'name'        => 'pgfw_body_add_watermark',
			),

			array(
				'title'        => __( 'Add Watermark Image', 'pdf-generator-for-wp' ),
				'type'         => 'checkbox',
				'description'  => __( 'Select this to include watermark image in PDF.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_watermark_image_use_in_pdf_dummy',
				'value'        => 'no',
				'class'        => 'wps_pgfw_pro_tag',
				'name'         => 'pgfw_watermark_image_use_in_pdf',
				'parent-class' => 'wps_pgfw_setting_separate_border',
			),
			array(
				'title'       => __( 'Watermark Angle', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'description' => __( 'Please Choose Watermark Angle.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_watermark_angle_dummy',
				'value'       => -45,
				'class'       => 'wps_pgfw_pro_tag',
				'placeholder' => '',
				'min'         => -90,
				'max'         => 180,
			),
			array(
				'title'       => __( 'Watermark Position', 'pdf-generator-for-wp' ),
				'type'        => 'multi',
				'class'       => 'wps_pgfw_pro_tag',
				'id'          => 'pgfw_wartermark_position_dummy',
				'description' => __( 'Choose watermark position left, top in px.', 'pdf-generator-for-wp' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_watermark_position_left',
						'class'       => 'wps_pgfw_pro_tag',
						'name'        => 'pgfw_watermark_position_left',
						'placeholder' => __( 'left', 'pdf-generator-for-wp' ),
						'value'       => 120,
						'min'         => -5000,
						'max'         => 5000,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_watermark_position_top',
						'class'       => 'wps_pgfw_pro_tag',
						'name'        => 'pgfw_watermark_position_top',
						'placeholder' => __( 'top', 'pdf-generator-for-wp' ),
						'value'       => 80,
						'min'         => -5000,
						'max'         => 5000,
					),
				),
			),
			array(
				'title'       => __( 'Watermark Opacity', 'pdf-generator-for-wp' ),
				'type'        => 'number',
				'id'          => 'pgfw_watermark_opacity_dummy',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_watermark_opacity',
				'placeholder' => __( 'opacity', 'pdf-generator-for-wp' ),
				'value'       => 0.3,
				'min'         => 0,
				'max'         => 1,
				'step'        => .1,
				'description' => __( 'Choose this to add transparency to the image used as watermark, value should be greater than 0 and less than 1, accepted decimal values.', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Choose Watermark Image', 'pdf-generator-for-wp' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Image', 'pdf-generator-for-wp' ),
				'sub_class'   => 'pgfw_watermark_image_upload1',
				'sub_id'      => 'pgfw_watermark_image_upload1',
				'id'          => 'sub_pgfw_watermark_image_upload_dummy',
				'name'        => 'sub_pgfw_watermark_image_upload1',
				'class'       => 'wps_pgfw_pro_tag',
				'value'       => '',
				'sub_name'    => 'pgfw_watermark_image_upload',
				'img-tag'     => array(
					'img-class' => 'pgfw_watermark_image',
					'img-id'    => 'pgfw_watermark_image',
					'img-style' => ( '' ) ? 'margin-right:10px;width:100px;height:100px;' : 'display:none;margin-right:10px;width:100px;height:100px;',
					'img-src'   => '',
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_watermark_image_remove',
					'btn-id'    => 'pgfw_watermark_image_remove',
					'btn-text'  => __( 'Remove image', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove image', 'pdf-generator-for-wp' ),
					'btn-name'  => 'pgfw_watermark_image_remove',
					'btn-style' => ! ( '' ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'Watermark Image Size', 'pdf-generator-for-wp' ),
				'type'        => 'multi',
				'class'       => 'wps_pgfw_pro_tag',
				'id'          => 'pgfw_wartermark_size_dummy',
				'description' => __( 'Choose watermark image width, height in px.', 'pdf-generator-for-wp' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_watermark_image_width',
						'class'       => 'wps_pgfw_pro_tag',
						'name'        => 'pgfw_watermark_image_width',
						'placeholder' => __( 'width', 'pdf-generator-for-wp' ),
						'value'       => 100,
						'min'         => -5000,
						'max'         => 5000,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_watermark_image_height',
						'class'       => 'wps_pgfw_pro_tag',
						'name'        => 'pgfw_watermark_image_height',
						'placeholder' => __( 'height', 'pdf-generator-for-wp' ),
						'value'       => 100,
						'min'         => -5000,
						'max'         => 5000,
					),
				),
			),

			array(
				'title'       => __( 'Watermark Text', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'description' => __( 'Enter text to be used as watermark.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_watermark_text',
				'value'       => $pgfw_body_watermark_text,
				'class'       => 'pgfw_body_watermark_text ',
				'name'        => 'pgfw_body_watermark_text',
				'placeholder' => __( 'Watermark text', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Choose Watermark Text Color', 'pdf-generator-for-wp' ),
				'type'        => 'color',
				'description' => __( 'Please choose color to display the text of watermark.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_watermark_color',
				'value'       => $pgfw_body_watermark_color,
				'class'       => 'pgfw_color_picker pgfw_body_watermark_color',
				'name'        => 'pgfw_body_watermark_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wp' ),
			),
			array(
				'title'        => __( 'Page Template', 'pdf-generator-for-wp' ),
				'type'         => 'select',
				'description'  => __( 'This will be used as the page template.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_body_page_template',
				'value'        => $pgfw_body_page_template,
				'class'        => 'pgfw_body_page_template',
				'name'         => 'pgfw_body_page_template',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'options'      => array(

					'template1' => __( 'Template1', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Post Template', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'This will be used as the post template.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_post_template',
				'value'       => $pgfw_body_post_template,
				'class'       => 'pgfw_body_post_template',
				'name'        => 'pgfw_body_post_template',
				'options'     => array(

					'template1' => __( 'Template1', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Custom CSS', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'description' => __( 'Add custom CSS for any HTML element this will be applied to the elements in the content.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_custom_css',
				'value'       => $pgfw_body_custom_css,
				'class'       => 'pgfw_body_custom_css',
				'name'        => 'pgfw_body_custom_css',
				'placeholder' => __( 'Custom CSS', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Show images row wise   ( Template1 )', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this to display images in row wise .', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_images_row_wise',
				'value'       => $pgfw_body_images_row_wise,
				'class'       => 'pgfw_body_images_row_wise',
				'name'        => 'pgfw_body_images_row_wise',
			),
			array(
				'title'       => __( 'Show Meta fields row wise', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this to display meta fields in row .', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_metafields_row_wise',
				'value'       => $pgfw_body_metafields_row_wise,
				'class'       => 'pgfw_body_metafields_row_wise',
				'name'        => 'pgfw_body_metafields_row_wise',
			),
			array(
				'title'       => __( 'Select Number of columns ', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'You can choose number of columns needed in a row for your meta fields.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_meta_field_column',
				'value'       => $pgfw_body_meta_field_column,
				'class'       => 'pgfw_body_meta_field_column',
				'name'        => 'pgfw_body_meta_field_column',
				'options'     => array(
					'1'          => __( '1', 'pdf-generator-for-wp' ),
					'2'          => __( '2', 'pdf-generator-for-wp' ),
					'3'          => __( '3', 'pdf-generator-for-wp' ),
					'4'          => __( '4', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Hide featured image, title and Description(word)', 'pdf-generator-for-wp' ),
				'type'        => 'multiselect',
				'description' => __( 'You have the flexibility to customize the default template by editing static text strings and thumbnails to better suit your needs.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_customization_for_post_detail',
				'value'       => $pgfw_body_customization,
				'class'       => 'pgfw-multiselect-class wps-defaut-multiselect pgfw_advanced_show_post_type_icons',
				'name'        => 'pgfw_body_customization_for_post_detail',
				'options'     => array(
					'title' => __( 'Post title', 'pdf-generator-for-wp' ),
					'post_thumb'      => __( 'Post thumbnail', 'pdf-generator-for-wp' ),
					'description'      => __( 'Post description', 'pdf-generator-for-wp' ),
				),
			),
		);

		$pgfw_body_html_arr   = apply_filters( 'pgfw_settings_body_fields_html_arr_filter_hook', $pgfw_body_html_arr );
		$pgfw_body_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_body_save_settings',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_body_save_settings',
			'name'        => 'pgfw_body_save_settings',
		);
		return $pgfw_body_html_arr;
	}
	/**
	 * Html fields for advanced setting fields.
	 *
	 * @since 1.0.0
	 * @param array $pgfw_advanced_settings_html_arr array containing fields for advanced settings.
	 * @return array
	 */
	public function pgfw_admin_advanced_settings_page( $pgfw_advanced_settings_html_arr ) {
		$pgfw_advanced_settings  = get_option( 'pgfw_advanced_save_settings', array() );
		$pgfw_advanced_icon_show = array_key_exists( 'pgfw_advanced_show_post_type_icons', $pgfw_advanced_settings ) ? $pgfw_advanced_settings['pgfw_advanced_show_post_type_icons'] : '';

		$post_types              = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );

		$pgfw_advanced_settings_html_arr   = array();
		$pgfw_advanced_settings_html_arr[] = array(
			'title'       => __( 'Show Icons for Post Type', 'pdf-generator-for-wp' ),
			'type'        => 'multiselect',
			'description' => __( 'PDF generate icons will be visible to selected post type.', 'pdf-generator-for-wp' ),
			'id'          => 'pgfw_advanced_show_post_type_icons',
			'value'       => $pgfw_advanced_icon_show,
			'class'       => 'pgfw-multiselect-class wps-defaut-multiselect pgfw_advanced_show_post_type_icons',
			'name'        => 'pgfw_advanced_show_post_type_icons',
			'options'     => $post_types,
		);
		$pgfw_advanced_settings_html_arr[] = array(
			'title'       => __( 'Select Post Type', 'pdf-generator-for-wp' ),
			'type'        => 'multiselect',
			'description' => __( 'Select all post types that you want save as a PDF on server with weekly update.', 'pdf-generator-for-wp' ),
			'id'          => 'pgfw_advanced_post_on_server',
			'value'       => 'posts',
			'class'       => 'pgfw-multiselect-class wps-defaut-multiselect  wps_pgfw_pro_tag',
			'name'        => 'pgfw_advanced_post_on_server',
			'options'     => '',
		);
		$pgfw_advanced_settings_html_arr[] = array(
			'title'       => __( 'Upload Custom Font File', 'pdf-generator-for-wp' ),
			'type'        => 'file',
			'id'          => 'font_upload',
			'value'       => '',
			'class'       => 'wps_pgfw_pro_tag',
			'name'        => 'font_upload',
			'placeholder' => __( 'ttf file', 'pdf-generator-for-wp' ),
			'description' => __( 'Choose .ttf file to add custom font, once uploaded all dropdowns of font will have this option to choose from.', 'pdf-generator-for-wp' ),
		);
		$pgfw_advanced_settings_html_arr   = apply_filters( 'pgfw_settings_advance_html_arr_filter_hook', $pgfw_advanced_settings_html_arr );
		$pgfw_advanced_settings_html_arr[] = array(
			'title'       => __( 'Reset Settings', 'pdf-generator-for-wp' ),
			'description' => __( 'This will reset all the settings to default.', 'pdf-generator-for-wp' ),
			'type'        => 'reset-button',
			'id'          => 'pgfw_advanced_reset_settings',
			'button_text' => __( 'Reset settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_advanced_reset_settings',
			'name'        => 'pgfw_advanced_reset_settings',
			'loader-id'   => 'pgfw_reset_setting_loader',
		);

		$pgfw_advanced_settings_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_advanced_save_settings',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_advanced_save_settings',
			'name'        => 'pgfw_advanced_save_settings',
		);

		return $pgfw_advanced_settings_html_arr;
	}
	/**
	 * Html for meta fields settings.
	 *
	 * @since 1.0.0
	 * @param array $pgfw_meta_settings_html_arr array containing html fields for meta fields settings.
	 * @return array
	 */
	public function pgfw_admin_meta_fields_settings_page( $pgfw_meta_settings_html_arr ) {
		$pgfw_meta_settings = get_option( 'pgfw_meta_fields_save_settings', array() );

		$pgfw_meta_settings_html_arr = array();
		$post_types                  = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );
		$i = 0;
		foreach ( $post_types as $post_type ) {
			$meta_keys = array();
			$posts     = get_posts(
				array(
					'post_type' => $post_type,
					'limit'     => -1,
				)
			);
			foreach ( $posts as $_post ) {
				$post_meta_keys = get_post_custom_keys( $_post->ID );
				if ( $post_meta_keys ) {
					$meta_keys = array_merge( $meta_keys, $post_meta_keys );
				}
			}
			$post_meta_fields = array_values( array_unique( $meta_keys ) );
			$post_meta_field  = array();
			foreach ( $post_meta_fields as $key => $val ) {
				$post_meta_field[ $val ] = $val;
			}
			$pgfw_show_type_meta_val = array_key_exists( 'pgfw_meta_fields_' . $post_type . '_show', $pgfw_meta_settings ) ? $pgfw_meta_settings[ 'pgfw_meta_fields_' . $post_type . '_show' ] : '';
			$pgfw_show_type_meta_arr = array_key_exists( 'pgfw_meta_fields_' . $post_type . '_list', $pgfw_meta_settings ) ? $pgfw_meta_settings[ 'pgfw_meta_fields_' . $post_type . '_list' ] : array();
			$pgfw_meta_fields_show_image_gallery = array_key_exists( 'pgfw_meta_fields_show_image_gallery', $pgfw_meta_settings ) ? $pgfw_meta_settings['pgfw_meta_fields_show_image_gallery'] : '';
			$pgfw_meta_fields_show_unknown_image_format = array_key_exists( 'pgfw_meta_fields_show_unknown_image_format', $pgfw_meta_settings ) ? $pgfw_meta_settings['pgfw_meta_fields_show_unknown_image_format'] : '';
			$pgfw_gallery_metafield_key = array_key_exists( 'pgfw_gallery_metafield_key', $pgfw_meta_settings ) ? $pgfw_meta_settings['pgfw_gallery_metafield_key'] : '';
			$pgfw_meta_settings_html_arr[] =
				array(
					'title'        => __( 'Show Meta Fields For ', 'pdf-generator-for-wp' ) . $post_type,
					'type'         => 'checkbox',
					'description'  => __( 'selecting this will show the meta fields on PDF.', 'pdf-generator-for-wp' ),
					'id'           => 'pgfw_meta_fields_' . $post_type . '_show',
					'value'        => $pgfw_show_type_meta_val,
					'class'        => 'pgfw_meta_fields_' . $post_type . '_show',
					'name'         => 'pgfw_meta_fields_' . $post_type . '_show',
					'parent-class' => ( 0 === $i ) ? '' : 'wps_pgfw_setting_separate_border',
				);
			$pgfw_meta_settings_html_arr[] = array(
				'title'       => __( 'Meta Fields in ', 'pdf-generator-for-wp' ) . $post_type,
				'type'        => 'multiselect',
				'description' => __( 'These meta fields will be shown on PDF.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_meta_fields_' . $post_type . '_list',
				'name'        => 'pgfw_meta_fields_' . $post_type . '_list',
				'value'       => $pgfw_show_type_meta_arr,
				'class'       => 'pgfw-multiselect-class wps-defaut-multiselect pgfw_meta_fields_' . $post_type . '_list',
				'placeholder' => '',
				'options'     => $post_meta_field,
			);
			$pgfw_meta_settings_html_arr   = apply_filters( 'pgfw_settings_meta_fields_html_arr_filter_hook', $pgfw_meta_settings_html_arr, $post_meta_field, $post_type );
			$i++;
		}
		$pgfw_meta_settings_html_arr[] =
			array(
				'title'        => __( 'Show Product Gallery Image  ', 'pdf-generator-for-wp' ),
				'type'         => 'checkbox',
				'description'  => __( 'If your gallery image meta field is different from "_product_image_gallery" then please write the name in below box and enable this setting and uncheck this in metafield section.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_meta_fields_show_image_gallery',
				'value'        => $pgfw_meta_fields_show_image_gallery,
				'class'        => 'pgfw_meta_fields_show_image_gallery',
				'name'         => 'pgfw_meta_fields_show_image_gallery',

			);
		$pgfw_meta_settings_html_arr[] =
			array(
				'title'        => __( 'Unknown Image Format Handler ', 'pdf-generator-for-wp' ),
				'type'         => 'checkbox',
				'description'  => __( 'If your image type is unknown or not rendering in the PDF, please enable this setting.', 'pdf-generator-for-wp' ),
				'id'           => 'pgfw_meta_fields_show_unknown_image_format',
				'value'        => $pgfw_meta_fields_show_unknown_image_format,
				'class'        => 'pgfw_meta_fields_show_unknown_image_format',
				'name'         => 'pgfw_meta_fields_show_unknown_image_format',

			);
		$pgfw_meta_settings_html_arr[] = array(
			'title'       => __( 'Gallery Image Meta Field Name.', 'pdf-generator-for-wp' ),
			'type'        => 'text',
			'description' => __( 'Enter your image gallery key .', 'pdf-generator-for-wp' ),
			'id'          => 'pgfw_gallery_metafield_key',
			'value'       => $pgfw_gallery_metafield_key,
			'class'       => 'pgfw_gallery_metafield_key wps_proper_align',
			'name'        => 'pgfw_gallery_metafield_key',
			'placeholder' => __( 'Metafield key Name', 'pdf-generator-for-wp' ),
		);
		$pgfw_meta_settings_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_meta_fields_save_settings',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_meta_fields_save_settings',
			'name'        => 'pgfw_meta_fields_save_settings',
		);
		return $pgfw_meta_settings_html_arr;
	}
	/**
	 * Html pdf for upload settings.
	 *
	 * @since 1.0.0
	 * @param array $pgfw_pdf_upload_settings_html_arr array containing fields for upload settings page.
	 * @return array
	 */
	public function pgfw_admin_pdf_upload_settings_page( $pgfw_pdf_upload_settings_html_arr ) {
		$pgfw_pdf_upload_settings = get_option( 'pgfw_pdf_upload_save_settings', array() );
		$pgfw_poster_doc          = array_key_exists( 'sub_pgfw_poster_image_upload', $pgfw_pdf_upload_settings ) ? json_decode( $pgfw_pdf_upload_settings['sub_pgfw_poster_image_upload'], true ) : '';
		$pgfw_poster_user_access  = array_key_exists( 'pgfw_poster_user_access', $pgfw_pdf_upload_settings ) ? $pgfw_pdf_upload_settings['pgfw_poster_user_access'] : '';
		$pgfw_poster_guest_access = array_key_exists( 'pgfw_poster_guest_access', $pgfw_pdf_upload_settings ) ? $pgfw_pdf_upload_settings['pgfw_poster_guest_access'] : '';
		$pgfw_poster_doc          = ( is_array( $pgfw_poster_doc ) && count( $pgfw_poster_doc ) <= 0 ) ? false : $pgfw_poster_doc;

		$pgfw_pdf_upload_settings_html_arr = array(
			array(
				'title'       => __( 'Access to Users', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to Logged in users to download Posters.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_poster_user_access',
				'value'       => $pgfw_poster_user_access,
				'class'       => 'pgfw_poster_user_access',
				'name'        => 'pgfw_poster_user_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Access to Guests', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to Guests to Download Posters.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_poster_guest_access',
				'value'       => $pgfw_poster_guest_access,
				'class'       => 'pgfw_poster_guest_access',
				'name'        => 'pgfw_poster_guest_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wp' ),
					'no'  => __( 'NO', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'        => __( 'Choose Poster(s)', 'pdf-generator-for-wp' ),
				'type'         => 'upload-button',
				'button_text'  => __( 'Upload Doc', 'pdf-generator-for-wp' ),
				'class'        => 'sub_pgfw_poster_image_upload',
				'id'           => 'sub_pgfw_poster_image_upload',
				'value'        => is_array( $pgfw_poster_doc ) ? wp_json_encode( $pgfw_poster_doc ) : $pgfw_poster_doc,
				'sub_id'       => 'pgfw_poster_image_upload',
				'sub_class'    => 'pgfw_poster_image_upload',
				'sub_name'     => 'pgfw_poster_image_upload',
				'name'         => 'sub_pgfw_poster_image_upload',
				'parent-class' => 'wps_pgfw_setting_separate_border',
				'img-tag'      => array(
					'img-class' => 'pgfw_poster_image',
					'img-id'    => 'pgfw_poster_image',
					'img-style' => ( $pgfw_poster_doc ) ? 'margin:10px;height:35px;width:35px;' : 'display:none;margin:10px;height:35px;width:35px;',
					'img-src'   => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/document-management-big.png',
				),
				'img-remove'   => array(
					'btn-class' => 'pgfw_poster_image_remove',
					'btn-id'    => 'pgfw_poster_image_remove',
					'btn-text'  => __( 'Remove Doc', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove Doc', 'pdf-generator-for-wp' ),
					'btn-name'  => 'pgfw_poster_image_remove',
					'btn-style' => ! ( $pgfw_poster_doc ) ? 'display:none' : '',
				),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_pdf_upload_save_settings',
				'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
				'class'       => 'pgfw_pdf_upload_save_settings',
				'name'        => 'pgfw_pdf_upload_save_settings',
			),
		);
		return $pgfw_pdf_upload_settings_html_arr;
	}
	/**
	 * Ajax request handling for deleting media from uploaded posters.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wps_pgfw_delete_poster_by_media_id_from_table() {
		check_ajax_referer( 'pgfw_delete_media_by_id', 'nonce' );
		$media_id                 = array_key_exists( 'media_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['media_id'] ) ) : '';
		$pgfw_pdf_upload_settings = get_option( 'pgfw_pdf_upload_save_settings', array() );
		$pgfw_poster_doc          = array_key_exists( 'sub_pgfw_poster_image_upload', $pgfw_pdf_upload_settings ) ? $pgfw_pdf_upload_settings['sub_pgfw_poster_image_upload'] : '';
		if ( '' !== $media_id && '' !== $pgfw_poster_doc ) {
			$poster_doc_arr = json_decode( $pgfw_poster_doc, true );
			$key            = is_array( $poster_doc_arr ) ? array_search( (int) $media_id, $poster_doc_arr, true ) : '';
			if ( false !== $key ) {
				unset( $poster_doc_arr[ $key ] );
				$pgfw_pdf_upload_settings['sub_pgfw_poster_image_upload'] = wp_json_encode( $poster_doc_arr );
				update_option( 'pgfw_pdf_upload_save_settings', $pgfw_pdf_upload_settings );
			}
		}
		echo esc_html( is_array( $poster_doc_arr ) ? count( $poster_doc_arr ) : 0 );
		wp_die();
	}
	/**
	 * Deleting PDF from server schedular.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_delete_pdf_form_server_scheduler() {
		if ( ! wp_next_scheduled( 'pgfw_cron_delete_pdf_from_server' ) ) {
			wp_schedule_event( time(), 'weekly', 'pgfw_cron_delete_pdf_from_server' );
		}
	}
	/**
	 * Deleting PDF from server.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_delete_pdf_from_server() {
		 $upload_dir   = wp_upload_dir();
		$pgfw_pdf_dir = $upload_dir['basedir'] . '/post_to_pdf/';
		if ( is_dir( $pgfw_pdf_dir ) ) {
			$files = glob( $pgfw_pdf_dir . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					@unlink( $file ); // phpcs:ignore
				}
			}
		}
	}
	/**
	 * Reset Default settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_reset_default_settings() {
		 check_ajax_referer( 'pgfw_delete_media_by_id', 'nonce' );
		$this->pgfw_default_settings_update();
		wp_die();
	}
	/**
	 * Update deafult settings in options table.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_default_settings_update() {
		$pgfw_new_settings = array(
			'pgfw_general_settings_save'       => array(
				'pgfw_enable_plugin'                => 'yes',
				'pgfw_general_pdf_show_categories'  => 'yes',
				'pgfw_general_pdf_show_tags'        => 'yes',
				'pgfw_general_pdf_show_post_date'   => 'yes',
				'pgfw_general_pdf_show_author_name' => 'yes',
				'pgfw_general_pdf_generate_mode'    => 'download_locally',
				'pgfw_general_pdf_file_name'        => 'post_name',
				'pgfw_custom_pdf_file_name'         => '',
			),
			'pgfw_save_admin_display_settings' => array(
				'pgfw_user_access'                  => 'yes',
				'pgfw_guest_access'                 => 'yes',
				'pgfw_guest_download_or_email'      => 'direct_download',
				'pgfw_user_download_or_email'       => 'direct_download',
				'pgfw_display_pdf_icon_after'       => 'after_content',
				'pgfw_display_pdf_icon_alignment'   => 'center',
				'sub_pgfw_pdf_single_download_icon' => '',
				'sub_pgfw_pdf_bulk_download_icon'   => '',
				'pgfw_pdf_icon_width'               => 25,
				'pgfw_pdf_icon_height'              => 45,
			),
			'pgfw_header_setting_submit'       => array(
				'pgfw_header_use_in_pdf'       => 'yes',
				'sub_pgfw_header_image_upload' => '',
				'pgfw_header_company_name'     => 'Company Name',
				'pgfw_header_tagline'          => 'Address | Phone | Link | Email',
				'pgfw_header_color'            => '#000000',
				'pgfw_header_width'            => 8,
				'pgfw_header_font_style'       => 'helvetica',
				'pgfw_header_font_size'        => 10,
				'pgfw_header_top'              => -60,
			),
			'pgfw_footer_setting_submit'       => array(
				'pgfw_footer_use_in_pdf' => 'yes',
				'pgfw_footer_tagline'    => 'Footer Tagline',
				'pgfw_footer_color'      => '#000000',
				'pgfw_footer_width'      => 12,
				'pgfw_footer_font_style' => 'helvetica',
				'pgfw_footer_font_size'  => 10,
				'pgfw_footer_bottom'     => -140,
			),
			'pgfw_body_save_settings'          => array(
				'pgfw_body_title_font_style'  => 'helvetica',
				'pgfw_body_title_font_size'   => 20,
				'pgfw_body_title_font_color'  => '#000000',
				'pgfw_body_page_size'         => 'a4',
				'pgfw_body_page_orientation'  => 'portrait',
				'pgfw_body_page_font_style'   => 'helvetica',
				'pgfw_content_font_size'      => 12,
				'pgfw_body_font_color'        => '#000000',
				'pgfw_body_border_size'       => 0,
				'pgfw_body_border_color'      => '',
				'pgfw_body_margin_top'        => 70,
				'pgfw_body_margin_left'       => 35,
				'pgfw_body_margin_right'      => 10,
				'pgfw_body_margin_bottom'     => 60,
				'pgfw_body_rtl_support'       => 'no',
				'pgfw_body_add_watermark'     => 'yes',
				'pgfw_body_watermark_text'    => 'default watermark',
				'pgfw_body_watermark_color'   => '#000000',
				'pgfw_body_page_template'     => 'template1',
				'pgfw_body_post_template'     => 'template1',
				'pgfw_border_position_top'    => -110,
				'pgfw_border_position_left'   => -34,
				'pgfw_border_position_right'  => -15,
				'pgfw_border_position_bottom' => -60,
			),
			'pgfw_advanced_save_settings'      => array(
				'pgfw_advanced_show_post_type_icons' => array( 'page', 'post', 'product' ),
			),
			'pgfw_meta_fields_save_settings'   => array(
				'pgfw_meta_fields_post_show'    => 'no',
				'pgfw_meta_fields_product_show' => 'no',
				'pgfw_meta_fields_page_show'    => 'no',
				'pgfw_meta_fields_product_list' => '',
				'pgfw_meta_fields_post_list'    => '',
				'pgfw_meta_fields_page_list'    => '',
			),
			'pgfw_pdf_upload_save_settings'    => array(
				'sub_pgfw_poster_image_upload' => '',
				'pgfw_poster_user_access'      => 'yes',
				'pgfw_poster_guest_access'     => 'yes',
			),
		);
		foreach ( $pgfw_new_settings as $key => $val ) {
			update_option( $key, $val );
		}
		do_action( 'wps_pgfw_save_default_pro_settings' );
	}

	/**
	 * This function is used to count pending post.
	 *
	 * @param string $type type.
	 * @param string $action action.
	 * @return int $result result.
	 */
	public function wps_wpg_get_count( $type = 'all', $action = 'count' ) {
		global $wpdb;
		$option_result = wp_load_alloptions();
		$result        = array();
		foreach ( $option_result as $option_key => $option_value ) {

			if ( ( similar_text( 'mwb_pgfw_onboarding_data_skipped', $option_key ) == 32 ) || ( similar_text( 'mwb_all_plugins_active', $option_key ) == 22 ) || ( similar_text( 'mwb_pgfw_onboarding_data_sent', $option_key ) == 29 )
				|| ( similar_text( 'mwb_wpg_check_license_daily', $option_key ) == 27 )
				|| ( similar_text( 'mwb_wpg_activated_timestamp', $option_key ) == 27 ) || ( similar_text( 'mwb_wpg_plugin_update', $option_key ) == 21 )
				|| ( similar_text( 'mwb_wpg_license_key', $option_key ) == 19 ) || ( similar_text( 'mwb_wpg_license_check', $option_key ) == 21 )
				|| ( similar_text( 'mwb_wpg_meta_fields_in_page', $option_key ) == 27 ) || ( similar_text( 'mwb_wpg_meta_fields_in_post', $option_key ) == 27 )
				|| ( similar_text( 'mwb_wpg_meta_fields_in_product', $option_key ) == 30 )
			) {

				$array_val = array(
					'option_name'  => $option_key,
					'option_value' => $option_value,
				);
				$result[]  = $array_val;
			}
		}
		if ( empty( $result ) ) {
			return 0;
		}

		if ( 'count' === $action ) {
			$result = ! empty( $result ) ? count( $result ) : 0;
		}

		return $result;
	}

	/**
	 * This is a ajax callback function for migration.
	 */
	public function wps_wpg_ajax_callbacks() {
		check_ajax_referer( 'wps_wpg_migrated_nonce', 'nonce' );
		$event = ! empty( $_POST['event'] ) ? sanitize_text_field( wp_unslash( $_POST['event'] ) ) : '';
		if ( method_exists( $this, $event ) ) {
			$data = $this->$event( $_POST );
		} else {
			$data = esc_html__( 'method not found', 'pdf-generator-for-wp' );
		}
		echo wp_json_encode( $data );
		wp_die();
	}

	/**
	 * Upgrade_wp_options. (use period)
	 *
	 * Upgrade_wp_options.
	 *
	 * @since    1.0.0
	 */
	public function wpg_import_options_table() {
		$wp_options = array(
			'mwb_pgfw_onboarding_data_skipped' => '',
			'mwb_all_plugins_active'           => '',
			'mwb_pgfw_onboarding_data_sent'    => '',
			'mwb_wpg_check_license_daily' => '',
			'mwb_wpg_activated_timestamp' => '',
			'mwb_wpg_plugin_update'       => '',
			'mwb_wpg_license_key'        => '',
			'mwb_wpg_license_check'       => '',
			'mwb_wpg_meta_fields_in_page' => '',
			'mwb_wpg_meta_fields_in_post' => '',
			'mwb_wpg_meta_fields_in_product' => '',
		);

		foreach ( $wp_options as $key => $value ) {

			$new_key = str_replace( 'mwb_', 'wps_', $key );
			$new_value = get_option( $key, $value );

			$arr_val = array();
			if ( is_array( $new_value ) ) {
				foreach ( $new_value as $keys => $values ) {
					$new_key1 = str_replace( 'mwb_', 'wps_', $keys );
					$new_key2 = str_replace( 'mwb-', 'wps-', $new_key1 );

					$value_1 = str_replace( 'mwb-', 'wps-', $values );
					$value_2 = str_replace( 'mwb_', 'wps_', $value_1 );
					$arr_val[ $new_key2 ] = $value_2;
				}

				update_option( $new_key, $arr_val );
				update_option( 'copy_' . $new_key, $new_value );
				delete_option( $key );
			} else {
				update_option( $new_key, $new_value );
				update_option( 'copy_' . $new_key, $new_value );
				delete_option( $key );
			}
		}
	}

	/**
	 * Get Previous log data with wps keys.
	 */
	public function wpg_import_pdflog() {
		global $wpdb;
		$table_name      = $wpdb->prefix . 'wps_pdflog';
		$charset_collate = $wpdb->get_charset_collate();
		$sql             = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			postid text,
			username varchar(500),
			email varchar(320),
			time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );
		$sql = $wpdb->get_results( $wpdb->prepare( 'INSERT INTO  ' . $wpdb->prefix . 'wps_pdflog select * from ' . $wpdb->prefix . 'mwb_pdflog' ) );
	}

	/**
	 *
	 * Adding the default menu into the WordPress menu.
	 *
	 * @name wpswings_callback_function
	 * @since 1.0.0
	 */
	public function wps_pgfw_welcome_callback_function() {
		include_once plugin_dir_path( __DIR__ ) . 'admin/partials/pdf-generator-for-wp-welcome.php';
	}

	// PRO TAG /////////////////////////////////.
	/**
	 * Taxonomy fields.
	 *
	 * @param string $pgfw_taxonomy_settings_arr pgfw_taxonomy_settings_arr.
	 */
	public function pgfw_setting_fields_for_customising_taxonomy_dummy( $pgfw_taxonomy_settings_arr ) {
		$pgfw_taxonomy_settings = get_option( 'pgfw_taxonomy_fields_save_settings', array() );

		$pgfw_taxonomy_settings_arr = array();
		$post_types                = get_post_types( array( 'public' => true ) );
		unset( $post_types['attachment'] );
		$i = 0;
		foreach ( $post_types as $post_type ) {
			$post_taxonomy_fields = get_object_taxonomies( $post_type );
			$post_taxonomy_field  = array();
			foreach ( $post_taxonomy_fields as $val ) {
				$post_taxonomy_field[ $val ] = $val;
			}
			$pgfw_show_type_taxonomy_val = array_key_exists( 'pgfw_taxonomy_fields_' . $post_type . '_show', $pgfw_taxonomy_settings ) ? $pgfw_taxonomy_settings[ 'pgfw_taxonomy_fields_' . $post_type . '_show' ] : '';

			$pgfw_taxonomy_settings_arr[] =
				array(
					'title'        => __( 'Show Taxonomy Fields for ', 'pdf-generator-for-wp' ) . $post_type,
					'type'         => 'checkbox',
					'description'  => __( 'Selecting this will show the taxonomy fields on PDF.', 'pdf-generator-for-wp' ),
					'id'           => 'pgfw_taxonomy_fields_' . $post_type . '_show',
					'value'        => $pgfw_show_type_taxonomy_val,
					'class'        => 'wps_pgfw_pro_tag pgfw_taxonomy_fields_' . $post_type . '_show',
					'name'         => 'pgfw_taxonomy_fields_' . $post_type . '_show',
					'parent-class' => ( 0 === $i ) ? '' : 'wps_pgfw_setting_separate_border',
				);
			if ( is_array( $post_taxonomy_field ) && count( $post_taxonomy_field ) > 0 ) {
				$pgfw_taxonomy_settings_sub_arr = array();
				foreach ( $post_taxonomy_field as $taxonomy_key ) {
					$pgfw_taxonomy_key_name           = array_key_exists( $taxonomy_key, $pgfw_taxonomy_settings ) ? $pgfw_taxonomy_settings[ $taxonomy_key ] : '';
					$pgfw_taxonomy_checkbox_value     = array_key_exists( $taxonomy_key . '_checkbox', $pgfw_taxonomy_settings ) ? $pgfw_taxonomy_settings[ $taxonomy_key . '_checkbox' ] : '';
					$pgfw_taxonomy_settings_sub_arr[] = array(
						'title'          => $taxonomy_key,
						'type'           => 'text',
						'id'             => 'wps_wpg_' . $taxonomy_key,
						'value'          => $pgfw_taxonomy_key_name,
						'class'          => 'wps_pgfw_pro_tag wps_wpg_' . $taxonomy_key,
						'name'           => $taxonomy_key,
						'placeholder'    => $taxonomy_key,
						'checkbox_name'  => $taxonomy_key . '_checkbox',
						'checkbox_id'    => $taxonomy_key . '_checkbox',
						'checkbox_value' => $pgfw_taxonomy_checkbox_value,
					);
				}
				$pgfw_taxonomy_settings_arr[] = array(
					'title' => __( 'Rename Taxonomy Fields', 'pdf-generator-for-wp' ),
					'type'  => 'multiwithcheck',
					'id'    => 'pgfw_meta_fields_detail',
					'value' => $pgfw_taxonomy_settings_sub_arr,
				);
			}
			$i++;
		}
		$pgfw_taxonomy_settings_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_taxonomy_fields_save_settings',
			'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
			'class'       => 'pgfw_taxonomy_fields_save_settings',
			'name'        => 'pgfw_taxonomy_fields_save_settings',
		);
		return $pgfw_taxonomy_settings_arr;
	}
	/**
	 * Adding custom subtab for template settings in customisation tab.
	 *
	 * @since 3.0.0
	 * @param array $pgfw_default_tabs array containing subtabs in customisation ta.
	 * @return array
	 */
	public function pgfw_add_custom_template_settings_tab_dummy( $pgfw_default_tabs ) {
		$pgfw_default_tabs['pdf-generator-for-wp-cover-page-setting'] = array(
			'title' => esc_html__( 'Cover Page', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-cover-page-setting',
		);

		$pgfw_default_tabs['pdf-generator-for-wp-internal-page-setting'] = array(
			'title' => esc_html__( 'Internal Page', 'pdf-generator-for-wp' ),
			'name'  => 'pdf-generator-for-wp-internal-page-setting',
		);
		return $pgfw_default_tabs;
	}
	/**
	 * General setting page for pdf.
	 *
	 * @param array $pgfw_template_pdf_settings array containing the html for the fields.
	 * @return array
	 */
	public function pgfw_template_pdf_settings_page_dummy( $pgfw_template_pdf_settings ) {
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

			$order_stat = wc_get_order_statuses();
		} else {

			$order_stat = array();
		}
		$temp       = array(
			'wc-never' => __( 'Never', 'pdf-generator-for-wp' ),
		);
		$sub_pgfw_pdf_single_download_icon = '';
		// appending the default value.
		$order_statuses = is_array( $order_stat ) ? $temp + $order_stat : $temp;
		// array of html for pdf setting fields.
		$pgfw_template_pdf_settings   = array(
			array(
				'title'       => __( 'Enable Invoice Feature', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to start the plugin functionality for users.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_enable_plugin',
				'value'       => '',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_enable_plugin',
			),
			array(
				'title'       => __( 'Automatically Attach Invoice', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to attach invoices with woocommerce mails.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_send_invoice_automatically',
				'value'       => '',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_send_invoice_automatically',
			),
			array(
				'title'       => __( 'Order Status to Send Invoice For', 'pdf-generator-for-wp' ),
				'type'        => 'select',
				'description' => __( 'Please choose the status of orders to send invoice for. If you do not want to send invoice please choose never.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_send_invoice_for',
				'value'       => get_option( 'pgfw_send_invoice_for' ),
				'name'        => 'pgfw_send_invoice_for',
				'class'       => 'wps_pgfw_pro_tag',
				'placeholder' => '',
				'options'     => $order_statuses,
			),
			array(
				'title'       => __( 'Download Invoice for Users at Order Status', 'pdf-generator-for-wp' ),
				'type'        => 'multiselect',
				'description' => __( 'Please choose the status of orders to allow invoice download for users.', 'pdf-generator-for-wp' ),
				'id'          => 'wpg_allow_invoice_generation_for_orders',
				'value'       => get_option( 'wpg_allow_invoice_generation_for_orders', array() ),
				'name'        => 'wpg_allow_invoice_generation_for_orders',
				'class'       => 'wps_pgfw_pro_tag wpg-multiselect-class wpg-defaut-multiselect',
				'placeholder' => '',
				'options'     => $order_statuses,
			),
			array(
				'title'       => __( 'Generate Invoice from Cache', 'pdf-generator-for-wp' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to generate invoices from cache( invoices once downloaded will be stored in the preferred location and will be used later ), please note that once this is enabled changes after invoice generation will not reflect for earlier invoices, however changes will work for new order invoice downloads.', 'pdf-generator-for-wp' ),
				'id'          => 'wpg_generate_invoice_from_cache',
				'value'       => '',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'wpg_generate_invoice_from_cache',
			),
			array(
				'title' => __( 'How Do You Want To View PDF?', 'pdf-generator-for-wp' ),
				'type'  => 'select',
				'description'  => __( 'Choose if You want to  view in new tab or you want to download.', 'pdf-generator-for-wp' ),
				'id'    => 'wpg_view_pdf',
				'name' => 'wpg_view_pdf',
				'value' => get_option( 'wpg_view_pdf' ),
				'class' => 'wps_pgfw_pro_tag',
				'placeholder' => __( 'Select', 'pdf-generator-for-wp' ),
				'options' => array(
					'' => __( 'Select option', 'pdf-generator-for-wp' ),
					'view' => __( 'View in new tab', 'pdf-generator-for-wp' ),
					'download' => __( 'Download', 'pdf-generator-for-wp' ),
				),
			),
		);

		$pgfw_template_pdf_settings   = apply_filters( 'wpg_template_pdf_settings_array_filter', $pgfw_template_pdf_settings );
		$pgfw_template_pdf_settings[] = array(
			'type'        => 'button',
			'id'          => 'wpg_general_setting_save',
			'button_text' => __( 'Save settings', 'pdf-generator-for-wp' ),
			'class'       => 'wpg_general_setting_save',
			'name'        => 'wpg_general_setting_save',
		);

		return $pgfw_template_pdf_settings;
	}
	/**
	 * Invoice settting html array.
	 *
	 * @param array $invoice_settings_arr invoice setting array fields.
	 * @since 1.0.0
	 * @return array
	 */
	public function pgfw_template_invoice_setting_html_fields_dummy( $invoice_settings_arr ) {
		$sub_wpg_upload_invoice_company_logo = get_option( 'sub_wpg_upload_invoice_company_logo' );
		$pgfw_invoice_number_renew_month      = get_option( 'wpg_invoice_number_renew_month' );
		$pgfw_months                          = array(
			'never' => __( 'Never', 'pdf-generator-for-wp' ),
			1       => __( 'January', 'pdf-generator-for-wp' ),
			2       => __( 'February', 'pdf-generator-for-wp' ),
			3       => __( 'March', 'pdf-generator-for-wp' ),
			4       => __( 'April', 'pdf-generator-for-wp' ),
			5       => __( 'May', 'pdf-generator-for-wp' ),
			6       => __( 'June', 'pdf-generator-for-wp' ),
			7       => __( 'July', 'pdf-generator-for-wp' ),
			8       => __( 'August', 'pdf-generator-for-wp' ),
			9       => __( 'September', 'pdf-generator-for-wp' ),
			10      => __( 'October', 'pdf-generator-for-wp' ),
			11      => __( 'November', 'pdf-generator-for-wp' ),
			12      => __( 'December', 'pdf-generator-for-wp' ),
		);

		if ( ( 'never' !== $pgfw_invoice_number_renew_month ) && ( $pgfw_invoice_number_renew_month && '' !== $pgfw_invoice_number_renew_month ) ) {
			$number_of_days = cal_days_in_month( CAL_GREGORIAN, $pgfw_invoice_number_renew_month, gmdate( 'Y' ) );
			$dates          = range( 1, $number_of_days );
			$pgfw_date      = array_combine( $dates, $dates );
		} else {
			$pgfw_date = array();
		}

		$invoice_settings_arr = array(
			array(
				'title' => __( 'Company Details', 'pdf-generator-for-wp' ),
				'type'  => 'multi',
				'id'    => 'pgfw_company_details',
				'value' => array(
					array(
						'title'       => __( 'Name', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'pgfw_company_name',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'pgfw_company_name' ),
						'name'        => 'pgfw_company_name',
						'placeholder' => __( 'name', 'pdf-generator-for-wp' ),
					),
					array(
						'title'       => __( 'Address', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'pgfw_company_address',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'pgfw_company_address' ),
						'name'        => 'pgfw_company_address',
						'placeholder' => __( 'address', 'pdf-generator-for-wp' ),
					),
					array(
						'title'       => __( 'City', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'pgfw_company_city',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'pgfw_company_city' ),
						'name'        => 'pgfw_company_city',
						'placeholder' => __( 'city', 'pdf-generator-for-wp' ),
					),
					array(
						'title'       => __( 'State', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'pgfw_company_state',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'pgfw_company_state' ),
						'name'        => 'pgfw_company_state',
						'placeholder' => __( 'state', 'pdf-generator-for-wp' ),
					),
					array(
						'title'       => __( 'Pin', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'pgfw_company_pin',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'pgfw_company_pin' ),
						'name'        => 'pgfw_company_pin',
						'placeholder' => __( 'pin', 'pdf-generator-for-wp' ),
					),
					array(
						'title'       => __( 'Phone', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'pgfw_company_phone',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'pgfw_company_phone' ),
						'name'        => 'pgfw_company_phone',
						'placeholder' => __( 'phone', 'pdf-generator-for-wp' ),
					),
					array(
						'title'       => __( 'Email', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'pgfw_company_email',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'pgfw_company_email' ),
						'name'        => 'pgfw_company_email',
						'placeholder' => __( 'email', 'pdf-generator-for-wp' ),
					),
				),
			),
			array(
				'title'       => __( 'Invoice Number', 'pdf-generator-for-wp' ),
				'type'        => 'multi',
				'id'          => 'wpg_invoice_number',
				'description' => __( 'This combination will be used as the invoice ID : prefix + number of digits + suffix.', 'pdf-generator-for-wp' ),
				'value'       => array(
					array(
						'title'       => __( 'Prefix', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'wpg_invoice_number_prefix',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'wpg_invoice_number_prefix' ),
						'name'        => 'wpg_invoice_number_prefix',
						'placeholder' => __( 'Prefix', 'pdf-generator-for-wp' ),
					),
					array(
						'title'       => __( 'Digit', 'pdf-generator-for-wp' ),
						'type'        => 'number',
						'id'          => 'wpg_invoice_number_digit',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'wpg_invoice_number_digit' ),
						'name'        => 'wpg_invoice_number_digit',
						'placeholder' => __( 'digit', 'pdf-generator-for-wp' ),
						'min' => 0,
						'max' => 3000,
					),
					array(
						'title'       => __( 'Suffix', 'pdf-generator-for-wp' ),
						'type'        => 'text',
						'id'          => 'wpg_invoice_number_suffix',
						'class'       => 'wps_pgfw_pro_tag',
						'value'       => get_option( 'wpg_invoice_number_suffix' ),
						'name'        => 'wpg_invoice_number_suffix',
						'placeholder' => __( 'suffix', 'pdf-generator-for-wp' ),
					),
				),
			),
			array(
				'title'       => __( 'Invoice Number Renew Date', 'pdf-generator-for-wp' ),
				'type'        => 'date-picker',
				'description' => __( 'Please choose the invoice number to renew date', 'pdf-generator-for-wp' ),
				'id'          => 'wpg_invoice_number_renew',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'wpg_invoice_number_renew',
				'value'       => array(
					'month' => array(
						'id'      => 'wpg_invoice_number_renew_month',
						'title'   => __( 'Month', 'pdf-generator-for-wp' ),
						'type'    => 'select',
						'class'   => 'wpg_invoice_number_renew_month wps_pgfw_pro_tag',
						'name'    => 'wpg_invoice_number_renew_month',
						'value'   => $pgfw_invoice_number_renew_month,
						'options' => $pgfw_months,
					),
					'date'  => array(
						'id'      => 'wpg_invoice_number_renew_date',
						'title'   => __( 'Date', 'pdf-generator-for-wp' ),
						'type'    => 'select',
						'class'   => 'wpg_invoice_number_renew_date wps_pgfw_pro_tag',
						'name'    => 'wpg_invoice_number_renew_date',
						'value'   => get_option( 'wpg_invoice_number_renew_date' ),
						'options' => $pgfw_date,
					),
				),
			),
			array(
				'title'       => __( 'Disclaimer', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'description' => __( 'Please enter desclaimer of your choice', 'pdf-generator-for-wp' ),
				'id'          => 'wpg_invoice_disclaimer',
				'class'       => 'wps_pgfw_pro_tag',
				'value'       => get_option( 'wpg_invoice_disclaimer' ),
				'placeholder' => __( 'disclaimer', 'pdf-generator-for-wp' ),
				'name'        => 'wpg_invoice_disclaimer',

			),
			array(
				'title'       => __( 'Color', 'pdf-generator-for-wp' ),
				'type'        => 'color',
				'class'       => 'wps_pgfw_pro_tag wpg_invoice_color',
				'id'          => 'wpg_invoice_color',
				'description' => __( 'Choose color of your choice for invoices', 'pdf-generator-for-wp' ),
				'value'       => get_option( 'wpg_invoice_color' ),
				'name'        => 'wpg_invoice_color',
			),
			array(
				'title'        => __( 'Choose Company Logo', 'pdf-generator-for-wp' ),
				'type'         => 'upload-button',
				'button_text'  => __( 'Upload Logo', 'pdf-generator-for-wp' ),
				'class'        => 'wps_pgfw_pro_tag',
				'id'           => 'sub_wpg_upload_invoice_company_logo',
				'value'        => $sub_wpg_upload_invoice_company_logo,
				'sub_id'       => 'wpg_upload_invoice_company_logo',
				'sub_class'    => 'wpg_upload_invoice_company_logo',
				'sub_name'     => 'wpg_upload_invoice_company_logo',
				'name'         => 'sub_wpg_upload_invoice_company_logo',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'description'  => '',
				'img-tag'      => array(
					'img-class' => 'wpg_invoice_company_logo_image',
					'img-id'    => 'wpg_invoice_company_logo_image',
					'img-style' => ( $sub_wpg_upload_invoice_company_logo ) ? 'margin:10px;height:100px;width:100px;' : 'display:none;margin:10px;height:100px;width:100px;',
					'img-src'   => $sub_wpg_upload_invoice_company_logo,
				),
				'img-remove'   => array(
					'btn-class' => 'wpg_invoice_company_logo_image_remove',
					'btn-id'    => 'wpg_invoice_company_logo_image_remove',
					'btn-text'  => __( 'Remove Logo', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove Logo', 'pdf-generator-for-wp' ),
					'btn-name'  => 'wpg_invoice_company_logo_image_remove',
					'btn-style' => ! ( $sub_wpg_upload_invoice_company_logo ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'Add Logo On Invoice', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'description' => __( 'Please select if you want the above selected image to be used on invoice.', 'pdf-generator-for-wp' ),
				'id'          => 'wpg_is_add_logo_invoice',
				'value'       => get_option( 'wpg_is_add_logo_invoice' ),
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'wpg_is_add_logo_invoice',
			),
			array(
				'title'       => __( 'Choose Template', 'pdf-generator-for-wp' ),
				'type'        => 'temp-select',
				'id'          => 'wpg_invoice_template',
				'class'       => 'wps_pgfw_pro_tag',
				'description' => __( 'This template will be used as the invoice and packing slip', 'pdf-generator-for-wp' ),
				'selected'    => get_option( 'wpg_invoice_template' ),
				'value'       => array(
					array(
						'title' => __( 'Template1', 'pdf-generator-for-wp' ),
						'type'  => 'radio',
						'id'    => 'wpg_invoice_template_one',
						'class' => 'wpg_invoice_preview wpg_invoice_template_one',
						'name'  => 'wpg_invoice_template',
						'value' => 'one',
						'src'   => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/template1.png',
					),
					array(
						'title' => __( 'Template2', 'pdf-generator-for-wp' ),
						'type'  => 'radio',
						'id'    => 'wpg_invoice_template_two',
						'class' => 'wpg_invoice_preview wpg_invoice_template_two',
						'src'   => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/template2.png',
						'name'  => 'wpg_invoice_template',
						'value' => 'two',
					),
					array(
						'title' => __( 'Template3', 'pdf-generator-for-wp' ),
						'type'  => 'radio',
						'id'    => 'wpg_invoice_template_three',
						'class' => 'wpg_invoice_preview wpg_invoice_template_three',
						'src'   => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/temp3.png',
						'name'  => 'wpg_invoice_template',
						'value' => 'three',
					),
				),
			),
		);

		$invoice_settings_arr[] = array(
			'type'        => 'button',
			'id'          => 'wpg_invoice_setting_save',
			'button_text' => __( 'Save settings', 'pdf-generator-for-wp' ),
			'class'       => 'wpg_invoice_setting_save',
			'name'        => 'wpg_invoice_setting_save',
		);
		return $invoice_settings_arr;
	}
	/**
	 * Html fields for cover page layout.
	 *
	 * @since 3.0.0
	 * @param array $cover_page_html_arr cover page html array.
	 * @return array
	 */
	public function pgfw_cover_page_html_layout_fields_dummy( $cover_page_html_arr ) {
		$pgfw_coverpage_settings_data = get_option( 'pgfw_coverpage_setting_save', array() );
		$coverpage_single_enable     = array_key_exists( 'pgfw_cover_page_single_enable', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_single_enable'] : '';
		$coverpage_bulk_enable       = array_key_exists( 'pgfw_cover_page_bulk_enable', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_bulk_enable'] : '';
		$coverpage_company_name      = array_key_exists( 'pgfw_cover_page_company_name', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_company_name'] : '';
		$coverpage_company_tagline   = array_key_exists( 'pgfw_cover_page_company_tagline', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_company_tagline'] : '';
		$coverpage_company_email     = array_key_exists( 'pgfw_cover_page_company_email', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_company_email'] : '';
		$coverpage_company_address   = array_key_exists( 'pgfw_cover_page_company_address', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_company_address'] : '';
		$coverpage_company_url       = array_key_exists( 'pgfw_cover_page_company_url', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_company_url'] : '';
		$cover_page_image            = array_key_exists( 'sub_pgfw_cover_page_image_upload', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['sub_pgfw_cover_page_image_upload'] : '';
		$cover_page_company_logo     = array_key_exists( 'sub_pgfw_cover_page_company_logo_upload', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['sub_pgfw_cover_page_company_logo_upload'] : '';
		$coverpage_company_phone     = array_key_exists( 'pgfw_cover_page_company_Phone', $pgfw_coverpage_settings_data ) ? $pgfw_coverpage_settings_data['pgfw_cover_page_company_Phone'] : '';

		$cover_page_html_arr = array(
			array(
				'title'       => __( 'Add Cover Page to Single PDF', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'id'          => 'pgfw_cover_page_single_enable',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_single_enable',
				'value'       => $coverpage_single_enable,
				'description' => __( 'Selecting this will add cover page to generated single PDFs', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Add Cover Page to Bulk PDF', 'pdf-generator-for-wp' ),
				'type'        => 'checkbox',
				'id'          => 'pgfw_cover_page_bulk_enable',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_bulk_enable',
				'value'       => $coverpage_bulk_enable,
				'description' => __( 'Selecting this will add cover page to generated bulk continuation PDFs', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Company Name', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'id'          => 'pgfw_cover_page_company_name',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_company_name',
				'value'       => $coverpage_company_name,
				'description' => __( 'Add company name at the cover page.', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'company name', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Company Logo', 'pdf-generator-for-wp' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Image', 'pdf-generator-for-wp' ),
				'sub_class'   => 'pgfw_cover_page_company_logo_upload',
				'sub_id'      => 'pgfw_cover_page_company_logo_upload',
				'id'          => 'sub_pgfw_cover_page_company_logo_upload',
				'name'        => 'sub_pgfw_cover_page_company_logo_upload',
				'class'       => 'wps_pgfw_pro_tag',
				'value'       => $cover_page_company_logo,
				'sub_name'    => 'pgfw_cover_page_company_logo_upload',
				'img-tag'     => array(
					'img-class' => 'pgfw_cover_page_company_logo',
					'img-id'    => 'pgfw_cover_page_company_logo',
					'img-style' => ( $cover_page_company_logo ) ? 'width:100px;height:100px;margin-right:10px;' : 'display:none;margin-right:10px;width:100px;height:100px;',
					'img-src'   => $cover_page_company_logo,
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_cover_page_company_logo_remove',
					'btn-id'    => 'pgfw_cover_page_company_logo_remove',
					'btn-text'  => __( 'Remove logo', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove logo', 'pdf-generator-for-wp' ),
					'btn-name'  => 'pgfw_cover_page_company_logo_remove',
					'btn-style' => ! ( $cover_page_company_logo ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'Cover Page Image', 'pdf-generator-for-wp' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Image', 'pdf-generator-for-wp' ),
				'sub_class'   => 'pgfw_cover_page_image_upload',
				'sub_id'      => 'pgfw_cover_page_image_upload',
				'id'          => 'sub_pgfw_cover_page_image_upload',
				'name'        => 'sub_pgfw_cover_page_image_upload',
				'class'       => 'wps_pgfw_pro_tag',
				'value'       => $cover_page_image,
				'sub_name'    => 'pgfw_cover_page_image_upload',
				'img-tag'     => array(
					'img-class' => 'pgfw_cover_page_image',
					'img-id'    => 'pgfw_cover_page_image',
					'img-style' => ( $cover_page_image ) ? 'width:100px;height:100px;margin-right:10px;' : 'display:none;margin-right:10px;width:100px;height:100px;',
					'img-src'   => $cover_page_image,
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_cover_page_image_remove',
					'btn-id'    => 'pgfw_cover_page_image_remove',
					'btn-text'  => __( 'Remove image', 'pdf-generator-for-wp' ),
					'btn-title' => __( 'Remove image', 'pdf-generator-for-wp' ),
					'btn-name'  => 'pgfw_cover_page_image_remove',
					'btn-style' => ! ( $cover_page_image ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'Company Tagline', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'id'          => 'pgfw_cover_page_company_tagline',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_company_tagline',
				'value'       => $coverpage_company_tagline,
				'description' => __( 'Add company tagline at the cover page.', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'company tagline', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Company Email', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'id'          => 'pgfw_cover_page_company_email',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_company_email',
				'value'       => $coverpage_company_email,
				'description' => __( 'Add email at the cover page bottom.', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'email', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Company Address', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'id'          => 'pgfw_cover_page_company_address',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_company_address',
				'value'       => $coverpage_company_address,
				'description' => __( 'Add address at the cover page bottom.', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'address', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Company URL', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'id'          => 'pgfw_cover_page_company_url',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_company_url',
				'value'       => $coverpage_company_url,
				'description' => __( 'Add URL at the cover page bottom.', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'url', 'pdf-generator-for-wp' ),
			),
			array(
				'title'       => __( 'Company Phone No.', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'id'          => 'pgfw_cover_page_company_Phone',
				'class'       => 'wps_pgfw_pro_tag',
				'name'        => 'pgfw_cover_page_company_Phone',
				'value'       => $coverpage_company_phone,
				'description' => __( 'Add Phone at the cover page bottom.', 'pdf-generator-for-wp' ),
				'placeholder' => __( 'phone no.', 'pdf-generator-for-wp' ),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_coverpage_setting_save',
				'button_text' => __( 'Save Settings', 'pdf-generator-for-wp' ),
				'class'       => 'pgfw_coverpage_setting_save',
				'name'        => 'pgfw_coverpage_setting_save',
			),
		);
		return $cover_page_html_arr;
	}
	/**
	 * Add custom Page size in dropdown.
	 *
	 * @since 3.0.0
	 * @param array $wpg_custom_page_size array containing font styles.
	 * @return array
	 */
	public function wpg_custom_page_size_in_dropdown( $wpg_custom_page_size ) {

		$wpg_custom_page_size['custom_page'] = __( 'Custom page size', 'pdf-generator-for-wp' );

		return $wpg_custom_page_size;
	}
	/**
	 * Set cron.
	 *
	 * @since 3.0.0
	 */
	public function wps_pgfw_set_cron_for_plugin_notification() {
		$wps_pgfw_offset = get_option( 'gmt_offset' );
		$wps_pgfw_time   = time() + $wps_pgfw_offset * 60 * 60;
		if ( ! wp_next_scheduled( 'wps_wgm_check_for_notification_update' ) ) {
			wp_schedule_event( $wps_pgfw_time, 'daily', 'wps_wgm_check_for_notification_update' );
		}
	}
	/**
	 * Add Notice.
	 *
	 * @since 3.0.0
	 */
	public function wps_pgfw_save_notice_message() {
		$wps_notification_data = $this->wps_pgfw_get_update_notification_data();
		if ( is_array( $wps_notification_data ) && ! empty( $wps_notification_data ) ) {
			$banner_id      = array_key_exists( 'notification_id', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_id'] : '';
			$banner_image = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_image'] : '';
			$banner_url = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_url'] : '';
			$banner_type = array_key_exists( 'notification_message', $wps_notification_data[0] ) ? $wps_notification_data[0]['wps_banner_type'] : '';
			update_option( 'wps_wgm_notify_new_banner_id', $banner_id );
			update_option( 'wps_wgm_notify_new_banner_image', $banner_image );
			update_option( 'wps_wgm_notify_new_banner_url', $banner_url );
			if ( 'regular' == $banner_type ) {
				update_option( 'wps_wgm_notify_hide_baneer_notification', 0 );
			}
		}
	}
	/**
	 * Update notification data.
	 *
	 * @since 3.0.0
	 */
	public function wps_pgfw_get_update_notification_data() {
		$wps_notification_data = array();
		$url                   = 'https://demo.wpswings.com/client-notification/woo-gift-cards-lite/wps-client-notify.php';
		$attr                  = array(
			'action'         => 'wps_notification_fetch',
			'plugin_version' => PDF_GENERATOR_FOR_WP_VERSION,
		);
		$query                 = esc_url_raw( add_query_arg( $attr, $url ) );
		$response              = wp_remote_get(
			$query,
			array(
				'timeout'   => 20,
				'sslverify' => false,
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			echo '<p><strong>Something went wrong: ' . esc_html( stripslashes( $error_message ) ) . '</strong></p>';
		} else {
			$wps_notification_data = json_decode( wp_remote_retrieve_body( $response ), true );
		}
		return $wps_notification_data;
	}


	/**
	 * Register google embed block.
	 *
	 * @return void
	 */
	public function register_google_embed_blocks() {
		$wps_wpg_is_pro_active = false;
		$wps_tofw_is_pro_active = false;
		$wps_wpg_plugin_list = get_option( 'active_plugins' );
		$wps_wpg_plugin = 'wordpress-pdf-generator/wordpress-pdf-generator.php';
		if ( in_array( $wps_wpg_plugin, $wps_wpg_plugin_list ) ) {
			$wps_wpg_is_pro_active = true;
		}

		$wps_tofw_plugin = 'track-orders-for-woocommerce/track-orders-for-woocommerce.php';
		if ( in_array( $wps_tofw_plugin, $wps_wpg_plugin_list ) ) {
			$wps_tofw_is_pro_active = true;
		}
		$license_check = get_option( 'wps_wpg_license_check', 0 );

		wp_register_script(
			'google-embed-block',
			plugins_url( 'src/js/pdf-google-embed-block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-editor', 'wp-element', 'wp-components' ),
			filemtime( plugin_dir_path( __FILE__ ) . '/src/js/pdf-google-embed-block.js' )
		);

		register_block_type(
			'wpswings/google-embed',
			array(
				'editor_script' => 'google-embed-block',
			)
		);

		register_block_type(
			'custom/calendly-embed',
			array(
				'editor_script' => 'google-embed-block',
				'render_callback' => 'wps_render_calendly_block',
				'attributes' => array(
					'url' => array(
						'type' => 'string',
						'default' => 'https://calendly.com/princekumaryadav-wpswings/new-meeting-2',
					),
				),
			)
		);

		wp_localize_script(
			'google-embed-block',
			'embed_block_param',
			array(
				'ajaxurl'             => admin_url( 'admin-ajax.php' ),
				'reloadurl'           => admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ),
				'is_pro_active' => $wps_wpg_is_pro_active,
				'license_check' => $license_check,
				'is_tofw_is_active' => $wps_tofw_is_pro_active,
				'is_linkedln_active' => get_option( 'wps_embed_source_linkedln', '' ),
				'is_loom_active' => get_option( 'wps_embed_source_loom', '' ),
				'is_twitch_active' => get_option( 'wps_embed_source_twitch', '' ),
				'is_ai_chatbot_active' => get_option( 'wps_embed_source_ai_chatbot', '' ),
				'is_canva_active' => get_option( 'wps_embed_source_canva', '' ),
				'is_reddit_active' => get_option( 'wps_embed_source_reddit', '' ),
				'is_google_active' => get_option( 'wps_embed_source_google_elements', '' ),
				'is_calendly_active' => get_option( 'wps_embed_source_calendly', '' ),
				'is_strava_active' => get_option( 'wps_embed_source_strava', '' ),
				'is_rss_feed_active' => get_option( 'wps_embed_source_rss_feed', '' ),
				'is_x_active' => get_option( 'wps_embed_source_x', '' ),
				'is_view_pdf_active' => get_option( 'wps_embed_source_pdf_embed', '' ),
				'is_wps_track_order_active' => get_option( 'wps_embed_source_tracking_info', '' ),
			)
		);
	}

	/**
	 * Callback of calendly Block.
	 *
	 * @param array $attributes string of HTML.
	 * @return string
	 */
	public function wps_render_calendly_block( $attributes ) {
		$url = esc_url( $attributes['url'] );
		return '<iframe src="' . $url . '" width="100%" height="600px" style="border: none;color : red;" ></iframe>';
	}

	/**
	 * This is a ajax callback function for migration.
	 */
	public function wps_pgfw_save_embed_source_callback() {

		$source = ! empty( $_POST['souce_name'] ) ? sanitize_text_field( wp_unslash( $_POST['souce_name'] ) ) : '';
		$value = ! empty( $_POST['is_enable'] ) ? sanitize_text_field( wp_unslash( $_POST['is_enable'] ) ) : '';
		$wps_nonce = ! empty( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $wps_nonce, 'wps_wpg_embed_ajax_nonce' ) ) {
			wp_send_json_error( 'Invalid nonce' );
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Permission denied' );
		}

		update_option( "wps_embed_source_{$source}", $value );
		wp_send_json_success( "Saved $source as $value" );

		wp_die();
	}

	/**
	 * Display Notice.
	 *
	 * @since 3.0.0
	 */
	public function wps_pgfw_dismiss_notice_banner_callback() {
		if ( isset( $_REQUEST['wps_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['wps_nonce'] ) ), 'wps-pgfw-verify-notice-nonce' ) ) {

			$banner_id = get_option( 'wps_wgm_notify_new_banner_id', false );

			if ( isset( $banner_id ) && '' != $banner_id ) {
				update_option( 'wps_wgm_notify_hide_baneer_notification', $banner_id );
			}

			wp_send_json_success();
		}
	}

	/**
	 * Flipbook Custom Post Type and Taxonomy registration callback.
	 *
	 * @since 3.0.0
	 */
	public function wps_pgfw_flipbook_settings_callback() {
		register_post_type(
			'flipbook',
			array(
				'label' => 'Flipbooks',
				'public' => false,
				'show_ui' => true,
				'show_in_menu' => true, // Recommended for non-public, admin-only CPTs.
				'menu_icon' => 'dashicons-book-alt',
				'supports' => array( 'title' ),
				'taxonomies' => array( 'flipbook_category' ),
			)
		);

		register_taxonomy(
			'flipbook_category',
			'flipbook',
			array(
				'label' => 'Flipbook Categories',
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'rewrite' => array( 'slug' => 'flipbook-category' ),
			)
		);
	}

	/**
	 * Flipbook Metabox registration callback.
	 *
	 * @since 3.0.0
	 */
	public function wps_pgfw_add_flipbook_metabox_callback() {
		add_meta_box(
			'flipbook_settings',
			'Flipbook Settings',
			array( $this, 'wps_pgfw_settings_box' ),
			'flipbook',
			'normal',
			'high'
		);

		add_meta_box(
			'flipbook_useful_links',
			'Useful Links',
			array( $this, 'wps_pgfw_useful_links_box' ),
			'flipbook',
			'side',  // show on the right side like your screenshot.
			'default'
		);
	}

	/**
	 * Flipbook Useful links box callback.
	 *
	 * @param WP_Post $post Current post object.
	 * @since 3.0.0
	 */
	public function wps_pgfw_useful_links_box( $post ) {
		// Fetch saved meta.
		$demo_link   = 'https://demo.wpswings.com/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-demo&utm_medium=pdf-org-doc&utm_campaign=demo';
		$video_link  = 'https://youtu.be/RljECeP3JJk';
		$service_url = 'https://wpswings.com/woocommerce-services/';
		$pro_link    = 'https://wpswings.com/product/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-pro&utm_medium=referral&utm_campaign=pdf-pro';

		?>
	<div class="wps-fb_info-box">
		<?php if ( $video_link ) : ?>
			<div class="video"><a href="<?php echo esc_url( $video_link ); ?>" target="_blank">
				<span class="dashicons dashicons-video-alt3"></span> See Video Tutorial
			</a></div>
		<?php endif; ?>

		<?php if ( $demo_link ) : ?>
			<div class="demo"><a href="<?php echo esc_url( $demo_link ); ?>" target="_blank">
				<span class="dashicons dashicons-welcome-widgets-menus"></span> Live Demo
			</a></div>
		<?php endif; ?>

		<?php if ( $service_url ) : ?>
			<div class="service"><a href="<?php echo esc_url( $service_url ); ?>" target="_blank">
				<span class="dashicons dashicons-admin-generic"></span> Service Page
			</a></div>
		<?php endif; ?>

		<?php if ( $pro_link ) : ?>
			<div class="upgrade_to_pro"><a href="<?php echo esc_url( $pro_link ); ?>" target="_blank">
				<span class="dashicons dashicons-star-filled"></span> Upgrade to Pro Version
			</a></div>
		<?php endif; ?>
	</div>
		<?php
	}


	/**
	 * Flipbook Settings box callback.
	 *
	 * @param WP_Post $post Current post object.
	 * @since 3.0.0
	 */
	public function wps_pgfw_settings_box( $post ) {
		$width  = ! empty( get_post_meta( $post->ID, '_fb_width', true ) ) ? get_post_meta( $post->ID, '_fb_width', true ) : 1000;
		$height = ! empty( get_post_meta( $post->ID, '_fb_height', true ) ) ? get_post_meta( $post->ID, '_fb_height', true ) : 1509;

		$show_cover  = get_post_meta( $post->ID, '_fb_show_cover', true );
		$cover_image = get_post_meta( $post->ID, '_fb_cover_image', true );
		$back_image  = get_post_meta( $post->ID, '_fb_back_image', true );
		$tool_btn    = get_post_meta( $post->ID, '_fb_tool_btn', true );
		$pdf_url     = get_post_meta( $post->ID, '_fb_pdf_url', true );
		$flip_sound_url = get_post_meta( $post->ID, '_fb_flip_sound_url', true );
		$flip_sound_volume = get_post_meta( $post->ID, '_fb_flip_sound_volume', true );
		$popup_enabled = get_post_meta( $post->ID, '_fb_popup_enabled', true );
		$image_urls_json = get_post_meta( $post->ID, '_fb_image_urls', true );
		$image_urls_json = is_string( $image_urls_json ) ? $image_urls_json : '';

		// ✅ Config values with defaults.
		$mobile_scroll_support = get_post_meta( $post->ID, '_fb_mobileScrollSupport', true );
		$max_shadow_opacity    = get_post_meta( $post->ID, '_fb_maxShadowOpacity', true );
		$flipping_time        = get_post_meta( $post->ID, '_fb_flippingTime', true );
		$start_page           = get_post_meta( $post->ID, '_fb_startPage', true );
		$swipe_distance       = get_post_meta( $post->ID, '_fb_swipeDistance', true );
		$use_mouse_events      = get_post_meta( $post->ID, '_fb_useMouseEvents', true );
		$size                = get_post_meta( $post->ID, '_fb_size', true );

		$mobile_scroll_support = ( '' !== $mobile_scroll_support ) ? $mobile_scroll_support : '1';
		$max_shadow_opacity    = ( '' !== $max_shadow_opacity ) ? $max_shadow_opacity : 0.5;
		$flipping_time         = ( '' !== $flipping_time ) ? $flipping_time : 1000;
		$start_page            = ( '' !== $start_page ) ? $start_page : 0;
		$swipe_distance        = ( '' !== $swipe_distance ) ? $swipe_distance : 30;
		$use_mouse_events      = ( '' !== $use_mouse_events ) ? 'true' : 'false';
		$size                  = ( '' !== $size ) ? $size : 'stretch';
		$flip_sound_url        = ( '' !== $flip_sound_url ) ? $flip_sound_url : '';
		$flip_sound_volume     = ( '' !== $flip_sound_volume ) ? $flip_sound_volume : 1;
		$popup_enabled         = ( '' !== $popup_enabled ) ? (int) $popup_enabled : 0;

		// Optional validation notice (from save_post validation).
		$validation_notice = get_transient( 'wps_pgfw_notice_' . $post->ID );
		?>
	
	<div class="fb-tabs">
		<?php if ( $validation_notice ) : ?>
			<div id="ifb-validation-notice" class="notice notice-warning" style="margin:12px 0 0;">
				<p><?php echo esc_html( $validation_notice ); ?></p>
			</div>
			<?php
			delete_transient( 'wps_pgfw_notice_' . $post->ID );
endif;
		?>
		<div class="fb-tab-nav">
			<a href="#fb-layout" class="active"><?php esc_html_e( 'Layout', 'pdf-generator-for-wp' ); ?></a>
			<a href="#fb-config"><?php esc_html_e( 'Config', 'pdf-generator-for-wp' ); ?></a>
			<span class="fb-shortcode"><?php esc_html_e( 'Shortcode: ', 'pdf-generator-for-wp' ); ?><strong>[flipbook id="<?php echo esc_attr( $post->ID ); ?>"]</strong></span>
		</div>

		<!-- Layout Tab. -->
		<div id="fb-layout" class="fb-tab-content active">
			<?php wp_nonce_field( 'wps_pgfw_save_flipbook', 'wps_pgfw_flipbook_nonce' ); ?>

			<table class="form-table striped">
				<tbody>
					<!-- Book Content Source. -->
					<tr>
						<th><label for="fb_pdf_url"><?php esc_html_e( 'Content Source', 'pdf-generator-for-wp' ); ?></label>
						</th>
						<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Use either a PDF file or a set of images to build the flipbook. If images are selected, they will be used; otherwise the PDF source will be used.', 'pdf-generator-for-wp' ); ?>"></span>
							<div class="fb-source-block">
								<h4><?php esc_html_e( 'PDF', 'pdf-generator-for-wp' ); ?></h4>
								<input type="url" id="fb_pdf_url" name="fb_pdf_url" value="<?php echo esc_attr( $pdf_url ); ?>" placeholder="Enter PDF URL" style="width:100%; margin-bottom:10px;">
								<div class="pdf-preview">
									<?php if ( $pdf_url ) : ?>
										<a href="<?php echo esc_url( $pdf_url ); ?>" target="_blank" rel="noopener"><?php esc_html_e( 'Preview Uploaded PDF', 'pdf-generator-for-wp' ); ?></a>
									<?php endif; ?>
								</div>
								<button type="button" class="button upload-pdf-btn"><?php echo $pdf_url ? esc_html__( 'Change PDF', 'pdf-generator-for-wp' ) : esc_html__( 'Upload/Select PDF', 'pdf-generator-for-wp' ); ?></button>
								<?php
								if ( $pdf_url ) :
									?>
									<button type="button" class="button remove-pdf-btn"><?php esc_html_e( 'Remove', 'pdf-generator-for-wp' ); ?></button><?php endif; ?>
								<div id="fb_pdf_spinner" style="display:none; margin-top:10px;">
									<span class="spinner is-active" style="float:none;"></span> <?php esc_html_e( 'Converting PDF, please wait...', 'pdf-generator-for-wp' ); ?>
								</div>

								<hr style="margin:14px 0; border:none; border-top:1px solid #dcdcde;">
								<h4><?php esc_html_e( 'Images', 'pdf-generator-for-wp' ); ?></h4>
								<input type="hidden" id="fb_image_urls" name="fb_image_urls" value='<?php echo esc_attr( $image_urls_json ); ?>'>
								<div class="images-preview" style="display:flex; flex-wrap:wrap; gap:8px;">
									<?php
									$existing_imgs = json_decode( $image_urls_json, true );
									if ( is_array( $existing_imgs ) ) {
										foreach ( $existing_imgs as $u ) {
											echo '<div class="fb-img-chip" data-url="' . esc_url( $u ) . '" style="position:relative;width:60px;height:60px;">
                                                    <img src="' . esc_url( $u ) . '" style="width:60px;height:60px;object-fit:cover;border:1px solid #ddd;border-radius:4px;display:block;" />
                                                    <button type="button" class="button-link-delete fb-img-remove" title="Remove" style="position:absolute;top:-8px;right:-6px;background:#d63638;color:#fff;border:none;border-radius:999px;width: 20px;height: 20px;line-height: 1;text-align:center;cursor:pointer;display: inline-flex;align-items: center;justify-content: center;font-size: 14px;">&times;</button>
                                                </div>';
										}
									}
									?>
								</div>
								<div class="button-group">
									<button type="button" class="button upload-images-btn"><?php esc_html_e( 'Upload/Select Images', 'pdf-generator-for-wp' ); ?></button>
									<button type="button" class="button clear-images-btn" <?php echo empty( $existing_imgs ) ? 'style="display:none;"' : ''; ?>><?php esc_html_e( 'Clear', 'pdf-generator-for-wp' ); ?></button>
								</div>
								<p class="description" style="margin-top:8px;"><?php esc_html_e( 'If images are selected, they will be used to create the flipbook pages. Otherwise, the PDF source will be used.', 'pdf-generator-for-wp' ); ?></p>
							</div>
						</td>
					</tr>
					<tr style="display:none;">

						 <td><textarea name="fb_pdf_html" id="fb_pdf_html" rows="6" class="fb-wide"></textarea></td>
					</tr>
					<tr>
						<th><label for="fb_width"><?php esc_html_e( 'Width', 'pdf-generator-for-wp' ); ?></label>
						</th>
						<td><span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Enter the width of the flipbook in pixels.', 'pdf-generator-for-wp' ); ?>"></span><input type="number" name="fb_width" id="fb_width" value="<?php echo esc_attr( $width ); ?>"> px</td>
					</tr>
					
					<tr>
						<th><label for="fb_height"><?php esc_html_e( 'Height', 'pdf-generator-for-wp' ); ?></label>
						</th>
						<td><span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Enter the height of the flipbook in pixels.', 'pdf-generator-for-wp' ); ?>"></span><input type="number" name="fb_height" id="fb_height" value="<?php echo esc_attr( $height ); ?>"> px</td>
					</tr>
					<!-- Cover Toggle -->
					<tr>
						<th><label for="fb_tool_btn"><?php esc_html_e( 'Flipbook Tool Button', 'pdf-generator-for-wp' ); ?></label>
						</th>
						<td><span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Enable this option to add a tool button like go,next and previous to the flipbook.', 'pdf-generator-for-wp' ); ?>"></span><input type="checkbox" name="fb_tool_btn" id="fb_tool_btn" value="1" <?php checked( (int) $tool_btn, 1 ); ?>></td>
					</tr>
					<!-- Cover Toggle. -->
					<tr>
						<th><label for="fb_show_cover"><?php esc_html_e( 'Add Cover Page', 'pdf-generator-for-wp' ); ?></label>
						</th>
						<td><span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Enable this option to add a cover page to the flipbook.', 'pdf-generator-for-wp' ); ?>"></span><input type="checkbox" name="fb_show_cover" id="fb_show_cover" value="1" <?php checked( (int) $show_cover, 1 ); ?>></td>
					</tr>
					<!-- Cover Image. -->
					<tr class="cover-settings-row">
						<th><label for="fb_cover_image"><?php esc_html_e( 'Cover Image', 'pdf-generator-for-wp' ); ?></label>
						</th>
						<td><span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Enter the URL of the cover image for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="url" id="fb_cover_image" name="fb_cover_image" value="<?php echo esc_attr( $cover_image ); ?>" placeholder="Paste cover image URL or select below">
							<div class="cover-preview" style="margin-top:10px;">
									<?php
									if ( $cover_image ) :
										?>
										<img src="<?php echo esc_url( $cover_image ); ?>" style="max-width:250px; border:1px solid #ccc; display:block; margin-bottom:5px;"><?php endif; ?>
							</div>
							<button type="button" class="button upload-cover-btn"><?php echo $cover_image ? esc_html__( 'Change Cover Image', 'pdf-generator-for-wp' ) : esc_html__( 'Select Cover Image', 'pdf-generator-for-wp' ); ?></button>
								<?php
								if ( $cover_image ) :
									?>
									<button type="button" class="button remove-cover-btn"><?php esc_html_e( 'Remove', 'pdf-generator-for-wp' ); ?></button><?php endif; ?>
						</td>
					</tr>
					<!-- Back Cover Image. -->
					<tr class="cover-settings-row">
						<th><label for="fb_back_image"><?php esc_html_e( 'Back Cover Image', 'pdf-generator-for-wp' ); ?></label>
						</th>
						<td><span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Enter the URL of the back cover image for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="url" id="fb_back_image" name="fb_back_image" value="<?php echo esc_attr( $back_image ); ?>" placeholder="Paste back cover image URL or select below">
							<div class="back-preview" style="margin-top:10px;">
									<?php
									if ( $back_image ) :
										?>
										<img src="<?php echo esc_url( $back_image ); ?>" style="max-width:250px; border:1px solid #ccc; display:block; margin-bottom:5px;"><?php endif; ?>
							</div>
							<button type="button" class="button upload-back-btn"><?php echo $back_image ? esc_html__( 'Change Back Image', 'pdf-generator-for-wp' ) : esc_html__( 'Select Back Image', 'pdf-generator-for-wp' ); ?></button>
								<?php
								if ( $back_image ) :
									?>
									<button type="button" class="button remove-back-btn"><?php esc_html_e( 'Remove', 'pdf-generator-for-wp' ); ?></button><?php endif; ?>
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Config Tab. -->
		<div id="fb-config" class="fb-tab-content">
			<table class="form-table striped">
				<tbody>
					<tr>
						<th><label for="fb_popup_enabled"><?php esc_html_e( 'Open in Popup Modal', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Show a small flipbook icon. On click, open the flipbook in a modal.', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="checkbox" name="fb_popup_enabled" id="fb_popup_enabled" value="1" <?php checked( (int) $popup_enabled, 1 ); ?>>
						</td>
					</tr>
					<tr>
						<th><label for="fb_maxShadowOpacity"><?php esc_html_e( 'Max Shadow Opacity', 'pdf-generator-for-wp' ); ?></label>
							
						</th>
						<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Set the maximum shadow opacity for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="number" step="0.1" id="fb_maxShadowOpacity" name="fb_maxShadowOpacity" value="<?php echo esc_attr( $max_shadow_opacity ); ?>"></td>
					</tr>
					<tr>
						<th><label for="fb_flippingTime"><?php esc_html_e( 'Flipping Time (ms)', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Set the flipping time in milliseconds for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="number" id="fb_flippingTime" name="fb_flippingTime" min = "0" value="<?php echo esc_attr( $flipping_time ); ?>"></td>
					</tr>
					<tr>
						<th><label for="fb_startPage"><?php esc_html_e( 'Start Page', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Set the starting page for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="number" id="fb_startPage" name="fb_startPage" value="<?php echo esc_attr( $start_page ); ?>"></td>
					</tr>
					<tr>
						<th><label for="fb_swipeDistance"><?php esc_html_e( 'Swipe Distance', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Set the swipe distance for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="number" id="fb_swipeDistance" name="fb_swipeDistance" value="<?php echo esc_attr( $swipe_distance ); ?>"></td>
					</tr>
					<tr>
						<th><label for="fb_useMouseEvents"><?php esc_html_e( 'Use Mouse Events', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Enable or disable mouse events for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<select name="fb_useMouseEvents" id="fb_useMouseEvents">
								<option value="1" <?php selected( $use_mouse_events, '1' ); ?>>Yes</option>
								<option value="0" <?php selected( $use_mouse_events, '0' ); ?>>No</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="fb_size"><?php esc_html_e( 'Book Size Mode', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Set the book size mode for the flipbook.', 'pdf-generator-for-wp' ); ?>"></span>
							<select id="fb_size" name="fb_size">
								<option value="fixed" <?php selected( $size, 'fixed' ); ?>>Fixed</option>
								<option value="stretch" <?php selected( $size, 'stretch' ); ?>>Stretch</option>
							</select>
						</td>
					</tr>
					<tr>
						<th><label for="fb_flip_sound_url"><?php esc_html_e( 'Flip Sound URL', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Provide an audio file URL to play on page flip (e.g., MP3, WAV).', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="url" name="fb_flip_sound_url" id="fb_flip_sound_url" value="<?php echo esc_attr( $flip_sound_url ); ?>" placeholder="https://example.com/flip.mp3">
							<div class="audio-preview" style="margin:8px 0;">
									<?php if ( $flip_sound_url ) : ?>
									<audio controls src="<?php echo esc_url( $flip_sound_url ); ?>"></audio>
								<?php endif; ?>
							</div>
							<button type="button" class="button upload-audio-btn"><?php echo $flip_sound_url ? 'Change Audio' : 'Upload/Select Audio'; ?></button>
								<?php
								if ( $flip_sound_url ) :
									?>
									<button type="button" class="button remove-audio-btn">Remove</button><?php endif; ?>
						</td>
					</tr>
					<tr>
						<th><label for="fb_flip_sound_volume"><?php esc_html_e( 'Flip Sound Volume', 'pdf-generator-for-wp' ); ?></label>
					</th>
					<td>
							<span class="dashicons dashicons-editor-help" title="<?php esc_attr_e( 'Volume from 0.0 (mute) to 1.0 (max).', 'pdf-generator-for-wp' ); ?>"></span>
							<input type="number" step="0.1" min="0" max="1" name="fb_flip_sound_volume" id="fb_flip_sound_volume" value="<?php echo esc_attr( $flip_sound_volume ); ?>">
						</td>
					</tr>
				</tbody>
			</table>
		</div>

		<!-- Shortcode Tab. -->
		<div id="fb-shortcode" class="fb-tab-content">
			<p><strong>Use this shortcode:</strong></p>
			<code>[flipbook id="<?php echo esc_attr( $post->ID ); ?>"]</code>
		</div>
	</div>
		<?php
	}

	/**
	 * Flipbook Metabox save callback.
	 *
	 * @param int $post_id Current post ID.
	 * @since 3.0.0
	 */
	public function wps_pgfw_save_flipbook_metabox_callback( $post_id ) {

		if (
		! isset( $_POST['wps_pgfw_flipbook_nonce'] ) ||
		! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['wps_pgfw_flipbook_nonce'] ) ), 'wps_pgfw_save_flipbook' )
		) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['fb_width'] ) ) {
			update_post_meta( $post_id, '_fb_width', (int) $_POST['fb_width'] );
		}
		if ( isset( $_POST['fb_height'] ) ) {
			update_post_meta( $post_id, '_fb_height', (int) $_POST['fb_height'] );
		}
		if ( isset( $_POST['fb_flip_sound_url'] ) ) {
			update_post_meta(
				$post_id,
				'_fb_flip_sound_url',
				esc_url_raw( wp_unslash( $_POST['fb_flip_sound_url'] ) )
			);
		}
		if ( isset( $_POST['fb_flip_sound_volume'] ) ) {
			$vol = floatval( $_POST['fb_flip_sound_volume'] );
			if ( $vol < 0 ) {
				$vol = 0; }
			if ( $vol > 1 ) {
				$vol = 1; }
			update_post_meta( $post_id, '_fb_flip_sound_volume', $vol );
		}
		if ( isset( $_POST['fb_popup_enabled'] ) ) {
			update_post_meta( $post_id, '_fb_popup_enabled', (int) $_POST['fb_popup_enabled'] );
		} else {
			update_post_meta( $post_id, '_fb_popup_enabled', 0 );
		}
		// Save images JSON safely.
		if ( isset( $_POST['fb_image_urls'] ) ) {
			// Unslash the raw JSON string from POST.
			$raw = sanitize_textarea_field( wp_unslash( $_POST['fb_image_urls'] ) );

			// Decode JSON to array.
			$arr = json_decode( $raw, true );

			if ( is_array( $arr ) ) {
				$clean = array();

				foreach ( $arr as $u ) {
					// Sanitize each image URL.
					$url = esc_url_raw( $u );
					if ( ! empty( $url ) ) {
						$clean[] = $url;
					}
				}

				// Re-encode sanitized array and save.
				update_post_meta( $post_id, '_fb_image_urls', wp_json_encode( array_values( $clean ) ) );
			} else {
				update_post_meta( $post_id, '_fb_image_urls', '' );
			}
		}

		// Validation: ensure at least one source (PDF or images) is provided.
		$saved_pdf_url = get_post_meta( $post_id, '_fb_pdf_url', true );
		$saved_imgs_json = get_post_meta( $post_id, '_fb_image_urls', true );
		$saved_imgs = $saved_imgs_json ? json_decode( $saved_imgs_json, true ) : array();
		$has_images = is_array( $saved_imgs ) && count( $saved_imgs ) > 0;
		$has_pdf = ! empty( $saved_pdf_url );
		if ( ! $has_images && ! $has_pdf ) {
			set_transient( 'wps_pgfw_notice_' . $post_id, 'Please set a Content Source: either select a PDF or choose images.', 60 );
		} else {
			delete_transient( 'wps_pgfw_notice_' . $post_id );
		}
		if ( ! empty( $_POST['fb_pdf_html'] ) ) {
			$allowed_html = array(
				'div' => array(
					'class' => true,
					'id'    => true,
					'style' => true,
				),
				'img' => array(
					'src'   => true,
					'style' => true,
					'alt'   => true,
				),
			);

			$html_content = wp_kses( wp_unslash( $_POST['fb_pdf_html'] ), $allowed_html );
			update_post_meta(
				$post_id,
				'_fb_pdf_html',
				$html_content
			);
		}

		// Save Cover Image URL.
		if ( isset( $_POST['fb_cover_image'] ) ) {
			update_post_meta(
				$post_id,
				'_fb_cover_image',
				esc_url_raw( wp_unslash( $_POST['fb_cover_image'] ) )
			);
		}

		// Save Back Cover Image URL.
		if ( isset( $_POST['fb_back_image'] ) ) {
			update_post_meta(
				$post_id,
				'_fb_back_image',
				esc_url_raw( wp_unslash( $_POST['fb_back_image'] ) )
			);
		}

		// Save Show Cover (checkbox).
		if ( isset( $_POST['fb_show_cover'] ) ) {
			update_post_meta(
				$post_id,
				'_fb_show_cover',
				absint( wp_unslash( $_POST['fb_show_cover'] ) )
			);
		} else {
			update_post_meta( $post_id, '_fb_show_cover', 0 );
		}

		if ( isset( $_POST['fb_tool_btn'] ) ) {
			update_post_meta( $post_id, '_fb_tool_btn', (int) $_POST['fb_tool_btn'] );
		} else {
			update_post_meta( $post_id, '_fb_tool_btn', 0 );
		}
		if ( ! empty( $_POST['fb_pdf_url'] ) ) {
			update_post_meta(
				$post_id,
				'_fb_pdf_url',
				esc_url_raw( wp_unslash( $_POST['fb_pdf_url'] ) )
			);
		} else {
			update_post_meta( $post_id, '_fb_pdf_url', '' );
		}

		// ✅ Save Config Settings.
		$config_keys = array(
			'mobileScrollSupport',
			'maxShadowOpacity',
			'flippingTime',
			'startPage',
			'swipeDistance',
			'useMouseEvents',
			'size',
		);

		foreach ( $config_keys as $key ) {
			if ( isset( $_POST[ 'fb_' . $key ] ) ) {
				// Always unslash first to remove WordPress-added backslashes.
				$val = isset( $_POST[ 'fb_' . $key ] )
				? sanitize_text_field( wp_unslash( $_POST[ 'fb_' . $key ] ) )
				: '';

				// Sanitize and cast based on expected data type.
				if ( in_array( $key, array( 'mobileScrollSupport', 'useMouseEvents' ), true ) ) {
					// Expect "1" or "0" as string values.
					$val = ( '1' === $val ) ? '1' : '0';

				} elseif ( 'maxShadowOpacity' === $key ) {
					// Float values (opacity).
					$val = floatval( $val );

				} elseif ( in_array( $key, array( 'flippingTime', 'startPage', 'swipeDistance' ), true ) ) {
					// Integer values.
					$val = absint( $val );

				} elseif ( 'size' === $key ) {
					// Only allow specific string options.
					$val = in_array( $val, array( 'fixed', 'stretch' ), true )
						? sanitize_text_field( $val )
						: 'stretch';

				} else {
					// Default sanitization for any future keys.
					$val = sanitize_text_field( $val );
				}

				update_post_meta( $post_id, '_fb_' . $key, $val );
			}
		}
	}

	/**
	 * Manage Flipbook posts columns callback.
	 *
	 * @param array $columns Existing columns.
	 * @return array Modified columns.
	 * @since 3.0.0
	 */
	public function wps_pgfw_manage_flipbook_posts_columns( $columns ) {
		$columns['shortcode'] = 'Shortcode';
		return $columns;
	}

	/**
	 * Flipbook posts custom column callback.
	 *
	 * @param string $column  Current column name.
	 * @param int    $post_id Current post ID.
	 * @since 3.0.0
	 */
	public function wps_pgfw_flipbook_posts_custom_column( $column, $post_id ) {
		if ( 'shortcode' === $column ) {
			echo '<code>[flipbook id="' . esc_attr( $post_id ) . '"]</code>';
		}
	}
}
