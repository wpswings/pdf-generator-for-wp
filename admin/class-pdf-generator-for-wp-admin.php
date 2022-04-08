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
		if ( isset( $screen->id ) && 'wp-swings_page_pdf_generator_for_wp_menu' == $screen->id ) { // phpcs:ignore

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
		}
		wp_enqueue_style( 'pgfw-admin-custom-css', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/css/pdf-generator-for-wp-admin-custom.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function pgfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'wp-swings_page_pdf_generator_for_wp_menu' == $screen->id ) { // phpcs:ignore
			wp_enqueue_script( 'wps-pgfw-select2', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/select-2/pdf-generator-for-wp-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'wps-pgfw-metarial-js', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'wps-pgfw-metarial-js2', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'wps-pgfw-metarial-lite', PDF_GENERATOR_FOR_WP_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/js/pdf-generator-for-wp-admin.js', array( 'jquery', 'wps-pgfw-select2', 'wps-pgfw-metarial-js', 'wps-pgfw-metarial-js2', 'wps-pgfw-metarial-lite' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'pgfw_admin_param',
				array(
					'ajaxurl'             => admin_url( 'admin-ajax.php' ),
					'reloadurl'           => admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ),
					'pgfw_gen_tab_enable' => get_option( 'pgfw_radio_switch_demo' ),
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
					'pending_settings' => $this->wps_wpg_get_count( 'settings' ),
					'hide_import'   => $migration_success,
				)
			);
		}
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
			'name'      => __( 'PDF Generator For Wp', 'pdf-generator-for-wp' ),
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
		$pgfw_show_post_tags       = array_key_exists( 'pgfw_general_pdf_show_tags', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_tags'] : '';
		$pgfw_show_post_taxonomy   = array_key_exists( 'pgfw_general_pdf_show_taxonomy', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_taxonomy'] : '';
		$pgfw_show_post_date       = array_key_exists( 'pgfw_general_pdf_show_post_date', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_post_date'] : '';
		$pgfw_show_post_author     = array_key_exists( 'pgfw_general_pdf_show_author_name', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_author_name'] : '';
		$pgfw_pdf_generate_mode    = array_key_exists( 'pgfw_general_pdf_generate_mode', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_generate_mode'] : '';
		$pgfw_pdf_file_name        = array_key_exists( 'pgfw_general_pdf_file_name', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_file_name'] : '';
		$pgfw_pdf_file_name_custom = array_key_exists( 'pgfw_custom_pdf_file_name', $general_settings_data ) ? $general_settings_data['pgfw_custom_pdf_file_name'] : '';

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
				'placeholder' => 'File Name',
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
												move_uploaded_file( $file_to_upload, $target_file );
											}
											$settings_general_arr[ $pgfw_genaral_setting['id'] ] = $file_name_to_upload;
										} else {
											$settings_general_arr[ $pgfw_genaral_setting['id'] ] = $pgfw_genaral_setting['value'];
										}
									} else {
										$settings_general_arr[ $pgfw_genaral_setting['id'] ] = '';
									}
								} else {
									if ( isset( $_POST[ $pgfw_genaral_setting['id'] ] ) ) {
										$settings_general_arr[ $pgfw_genaral_setting['id'] ] = is_array( $_POST[ $pgfw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ) );
									} else {
										$settings_general_arr[ $pgfw_genaral_setting['id'] ] = '';
									}
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
		$pgfw_display_settings             = get_option( 'pgfw_save_admin_display_settings', array() );
		$pgfw_user_access                  = array_key_exists( 'pgfw_user_access', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_user_access'] : '';
		$pgfw_guest_access                 = array_key_exists( 'pgfw_guest_access', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_guest_access'] : '';
		$pgfw_guest_download_or_email      = array_key_exists( 'pgfw_guest_download_or_email', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_guest_download_or_email'] : '';
		$pgfw_user_download_or_email       = array_key_exists( 'pgfw_user_download_or_email', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_user_download_or_email'] : '';
		$pgfw_pdf_icon_after               = array_key_exists( 'pgfw_display_pdf_icon_after', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_after'] : '';
		$pgfw_pdf_icon_alignment           = array_key_exists( 'pgfw_display_pdf_icon_alignment', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_alignment'] : '';
		$sub_pgfw_pdf_single_download_icon = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $pgfw_display_settings ) ? $pgfw_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
		$pgfw_pdf_icon_width               = array_key_exists( 'pgfw_pdf_icon_width', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_width'] : '';
		$pgfw_pdf_icon_height              = array_key_exists( 'pgfw_pdf_icon_height', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_height'] : '';
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
					'left'   => __( 'Left', 'pdf-generator-for-wp' ),
					'center' => __( 'Center', 'pdf-generator-for-wp' ),
					'right'  => __( 'Right', 'pdf-generator-for-wp' ),
				),
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
				'description'  => __( 'If no icon is choosen default icon will be used.', 'pdf-generator-for-wp' ),
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
				'title'       => __( 'Company Name', 'pdf-generator-for-wp' ),
				'type'        => 'text',
				'description' => __( 'Company name will be displayed in the right side of the header', 'pdf-generator-for-wp' ),
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
		$pgfw_body_watermark_text    = array_key_exists( 'pgfw_body_watermark_text', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_watermark_text'] : '';
		$pgfw_body_watermark_color   = array_key_exists( 'pgfw_body_watermark_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_watermark_color'] : '';
		$pgfw_body_page_template     = array_key_exists( 'pgfw_body_page_template', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_template'] : '';
		$pgfw_body_post_template     = array_key_exists( 'pgfw_body_post_template', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_post_template'] : '';
		$pgfw_border_position_top    = array_key_exists( 'pgfw_border_position_top', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_top'] : '';
		$pgfw_border_position_bottom = array_key_exists( 'pgfw_border_position_bottom', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_bottom'] : '';
		$pgfw_border_position_left   = array_key_exists( 'pgfw_border_position_left', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_left'] : '';
		$pgfw_border_position_right  = array_key_exists( 'pgfw_border_position_right', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_right'] : '';
		$pgfw_body_custom_css        = array_key_exists( 'pgfw_body_custom_css', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_custom_css'] : '';

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
				'options'      => array(
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
				),
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
				'title'       => __( 'Watermark Text', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'description' => __( 'Enter text to be used as watermark.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_watermark_text',
				'value'       => $pgfw_body_watermark_text,
				'class'       => 'pgfw_body_watermark_text',
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
					''          => __( 'Select option', 'pdf-generator-for-wp' ),
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
					''          => __( 'Select option', 'pdf-generator-for-wp' ),
					'template1' => __( 'Template1', 'pdf-generator-for-wp' ),
				),
			),
			array(
				'title'       => __( 'Custom CSS', 'pdf-generator-for-wp' ),
				'type'        => 'textarea',
				'description' => __( 'Add custom css for any html element this will be applied to the elements in the content.', 'pdf-generator-for-wp' ),
				'id'          => 'pgfw_body_custom_css',
				'value'       => $pgfw_body_custom_css,
				'class'       => 'pgfw_body_custom_css',
				'name'        => 'pgfw_body_custom_css',
				'placeholder' => __( 'custom css', 'pdf-generator-for-wp' ),
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
	 * @return int $result result.
	 */
	public function wps_wpg_get_count( $type = 'all' ) {
		global $wpdb;

		switch ( $type ) {
			case 'settings':
				$table = $wpdb->prefix . 'options';
				$sql = "SELECT `option_id`
				FROM `$table`
				WHERE `option_name` LIKE 'mwb_pgfw_onboarding_data_skipped' 
				OR `option_name` LIKE 'mwb_all_plugins_active'
				OR `option_name` LIKE 'mwb_pgfw_onboarding_data_sent'
				OR `option_name` LIKE 'mwb_wpg_check_license_daily'
				OR `option_name` LIKE 'mwb_wpg_activated_timestamp'
				OR `option_name` LIKE 'mwb_wpg_plugin_update'
				OR `option_name` LIKE 'mwb_wpg_license_key'
				OR `option_name` LIKE 'mwb_wpg_license_check'
				OR `option_name` LIKE 'mwb_wpg_meta_fields_in_page'
				OR `option_name` LIKE 'mwb_wpg_meta_fields_in_post'
				OR `option_name` LIKE 'mwb_wpg_meta_fields_in_product'";

				break;

			default:
				$sql = false;
				break;
		}

		if ( empty( $sql ) ) {
			return 0;
		}

		$result = $wpdb->get_results( $sql, ARRAY_A ); // @codingStandardsIgnoreLine.
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
			$data = esc_html__( 'method not found', 'pdf-generator-for-wp-pro' );
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
				update_option( 'copy_' . $key, $new_value );
				delete_option( $key );
			} else {
				update_option( $new_key, $new_value );
				update_option( 'copy_' . $key, $new_value );
				delete_option( $key );
			}
		}
	}

	/**
	 * Get Previous log data with wps keys
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
		$sql = $wpdb->query( 'INSERT INTO ' . $table_name . ' select * from ' . $wpdb->prefix . 'mwb_pdflog' );
	}
}
