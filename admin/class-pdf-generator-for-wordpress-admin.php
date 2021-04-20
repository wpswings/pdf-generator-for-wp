<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/admin
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Pdf_Generator_For_WordPress_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
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
		if ( isset( $screen->id ) && 'makewebbetter_page_pdf_generator_for_wordpress_menu' == $screen->id ) { // phpcs:ignore

			wp_enqueue_style( 'mwb-pgfw-select2-css', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/select-2/pdf-generator-for-wordpress-select2.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-pgfw-meterial-css', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-pgfw-meterial-css2', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.css', array(), time(), 'all' );
			wp_enqueue_style( 'mwb-pgfw-meterial-lite', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-lite.min.css', array(), time(), 'all' );

			wp_enqueue_style( 'mwb-pgfw-meterial-icons-css', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/icon.css', array(), time(), 'all' );

			wp_enqueue_style( $this->plugin_name . '-admin-global', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/scss/pdf-generator-for-wordpress-admin-global.css', array( 'mwb-pgfw-meterial-icons-css' ), time(), 'all' );

			wp_enqueue_style( $this->plugin_name, PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/scss/pdf-generator-for-wordpress-admin.scss', array(), $this->version, 'all' );
		}
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'pgfw-admin-commomn-css', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/scss/pdf-generator-for-wordpress-admin-common.css', array(), $this->version, 'all' );
		wp_enqueue_style( 'pgfw-datatable-css', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/datatable/datatables.min.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 * @param    string $hook      The plugin page slug.
	 */
	public function pgfw_admin_enqueue_scripts( $hook ) {

		$screen = get_current_screen();
		if ( isset( $screen->id ) && 'makewebbetter_page_pdf_generator_for_wordpress_menu' == $screen->id ) { // phpcs:ignore
			wp_enqueue_script( 'mwb-pgfw-select2', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/select-2/pdf-generator-for-wordpress-select2.js', array( 'jquery' ), time(), false );

			wp_enqueue_script( 'mwb-pgfw-metarial-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-pgfw-metarial-js2', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-components-v5.0-web.min.js', array(), time(), false );
			wp_enqueue_script( 'mwb-pgfw-metarial-lite', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/material-design/material-lite.min.js', array(), time(), false );

			wp_register_script( $this->plugin_name . 'admin-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/js/pdf-generator-for-wordpress-admin.js', array( 'jquery', 'mwb-pgfw-select2', 'mwb-pgfw-metarial-js', 'mwb-pgfw-metarial-js2', 'mwb-pgfw-metarial-lite' ), $this->version, false );

			wp_localize_script(
				$this->plugin_name . 'admin-js',
				'pgfw_admin_param',
				array(
					'ajaxurl'             => admin_url( 'admin-ajax.php' ),
					'reloadurl'           => admin_url( 'admin.php?page=pdf_generator_for_wordpress_menu' ),
					'pgfw_gen_tab_enable' => get_option( 'pgfw_radio_switch_demo' ),
				)
			);

			wp_enqueue_script( $this->plugin_name . 'admin-js' );
		}
		wp_enqueue_media();
		wp_enqueue_script( 'pgfw-datatable-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'package/lib/datatable/datatables.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'mwb-pgfw-admin-custom-setting-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/js/pdf-generator-for-wordpress-admin-custom.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
		wp_localize_script(
			'mwb-pgfw-admin-custom-setting-js',
			'pgfw_admin_custom_param',
			array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'delete_loader'      => esc_html__( 'Deleting....', 'pdf-generator-for-wordpress' ),
				'nonce'              => wp_create_nonce( 'pgfw_delete_media_by_id' ),
				'pgfw_doc_dummy_img' => PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/images/document-management-big.png',
				'upload_doc'         => esc_html__( 'Upload Doc', 'pdf-generator-for-wordpress' ),
				'use_doc'            => esc_html__( 'Use Doc', 'pdf-generator-for-wordpress' ),
				'upload_image'       => esc_html__( 'Upload Image', 'pdf-generator-for-wordpress' ),
				'use_image'          => esc_html__( 'Use Image', 'pdf-generator-for-wordpress' ),
				'confirm_text'       => esc_html__( 'Are you sure you want to delete Doc ?', 'pdf-generator-for-wordpress' ),
			),
		);
	}

	/**
	 * Adding settings menu for PDF Generator For WordPress.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_options_page() {
		global $submenu;
		if ( empty( $GLOBALS['admin_page_hooks']['mwb-plugins'] ) ) {
			add_menu_page( __( 'MakeWebBetter', 'pdf-generator-for-wordpress' ), __( 'MakeWebBetter', 'pdf-generator-for-wordpress' ), 'manage_options', 'mwb-plugins', array( $this, 'mwb_plugins_listing_page' ), PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/images/MWB_Grey-01.svg', 15 );
			$pgfw_menus = apply_filters( 'mwb_add_plugins_menus_array', array() );
			if ( is_array( $pgfw_menus ) && ! empty( $pgfw_menus ) ) {
				foreach ( $pgfw_menus as $pgfw_key => $pgfw_value ) {
					add_submenu_page( 'mwb-plugins', $pgfw_value['name'], $pgfw_value['name'], 'manage_options', $pgfw_value['menu_link'], array( $pgfw_value['instance'], $pgfw_value['function'] ) );
				}
			}
		}
	}

	/**
	 * Removing default submenu of parent menu in backend dashboard
	 *
	 * @since   1.0.0
	 */
	public function mwb_pgfw_remove_default_submenu() {
		global $submenu;
		if ( is_array( $submenu ) && array_key_exists( 'mwb-plugins', $submenu ) ) {
			if ( isset( $submenu['mwb-plugins'][0] ) ) {
				unset( $submenu['mwb-plugins'][0] );
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
			'name'      => __( 'PDF Generator For WordPress', 'pdf-generator-for-wordpress' ),
			'slug'      => 'pdf_generator_for_wordpress_menu',
			'menu_link' => 'pdf_generator_for_wordpress_menu',
			'instance'  => $this,
			'function'  => 'pgfw_options_menu_html',
		);
		return $menus;
	}


	/**
	 * PDF Generator For WordPress mwb_plugins_listing_page.
	 *
	 * @since 1.0.0
	 */
	public function mwb_plugins_listing_page() {
		$active_marketplaces = apply_filters( 'mwb_add_plugins_menus_array', array() );
		if ( is_array( $active_marketplaces ) && ! empty( $active_marketplaces ) ) {
			require PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'admin/partials/welcome.php';
		}
	}

	/**
	 * PDF Generator For WordPress admin menu page.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_options_menu_html() {

		include_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'admin/partials/pdf-generator-for-wordpress-admin-dashboard.php';
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
		$pgfw_show_post_date       = array_key_exists( 'pgfw_general_pdf_show_post_date', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_post_date'] : '';
		$pgfw_show_post_author     = array_key_exists( 'pgfw_general_pdf_show_author_name', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_author_name'] : '';
		$pgfw_pdf_generate_mode    = array_key_exists( 'pgfw_general_pdf_generate_mode', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_generate_mode'] : '';
		$pgfw_pdf_file_name        = array_key_exists( 'pgfw_general_pdf_file_name', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_file_name'] : '';
		$pgfw_pdf_file_name_custom = array_key_exists( 'pgfw_custom_pdf_file_name', $general_settings_data ) ? $general_settings_data['pgfw_custom_pdf_file_name'] : '';

		$pgfw_settings_general_html_arr = array(
			array(
				'title'       => __( 'Enable Plugin', 'pdf-generator-for-wordpress' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable plugin to start the functionality.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_enable_plugin',
				'value'       => $pgfw_enable_plugin,
				'class'       => 'pgfw_enable_plugin',
				'name'        => 'pgfw_enable_plugin',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wordpress' ),
					'no'  => __( 'NO', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'        => __( 'Include Categories', 'pdf-generator-for-wordpress' ),
				'type'         => 'checkbox',
				'description'  => __( 'Categories will be shown on PDF.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_general_pdf_show_categories',
				'value'        => $pgfw_show_post_categories,
				'class'        => 'pgfw_general_pdf_show_categories',
				'name'         => 'pgfw_general_pdf_show_categories',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
			),
			array(
				'title'       => __( 'Include Tag', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Tags will be shown on PDF.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_general_pdf_show_tags',
				'value'       => $pgfw_show_post_tags,
				'class'       => 'pgfw_general_pdf_show_tags',
				'name'        => 'pgfw_general_pdf_show_tags',
			),
			array(
				'title'       => __( 'Display Post Date', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Post date will be shown on PDF.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_general_pdf_show_post_date',
				'value'       => $pgfw_show_post_date,
				'class'       => 'pgfw_general_pdf_show_post_date',
				'name'        => 'pgfw_general_pdf_show_post_date',
			),
			array(
				'title'       => __( 'Display Author Name', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Author name will be shown on PDF.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_general_pdf_show_author_name',
				'value'       => $pgfw_show_post_author,
				'class'       => 'pgfw_general_pdf_show_author_name',
				'name'        => 'pgfw_general_pdf_show_author_name',
			),
			array(
				'title'        => __( 'PDF Download Option', 'pdf-generator-for-wordpress' ),
				'type'         => 'select',
				'description'  => __( 'Please choose either to download or open window.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_general_pdf_generate_mode',
				'value'        => $pgfw_pdf_generate_mode,
				'class'        => 'pgfw_general_pdf_generate_mode',
				'name'         => 'pgfw_general_pdf_generate_mode',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'options'      => array(
					''                 => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'download_locally' => __( 'Download Locally', 'pdf-generator-for-wordpress' ),
					'open_window'      => __( 'Open Window', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Default File Name', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'File name will be used as the name of the pdf generated.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_general_pdf_file_name',
				'value'       => $pgfw_pdf_file_name,
				'class'       => 'pgfw_general_pdf_file_name',
				'name'        => 'pgfw_general_pdf_file_name',
				'options'     => array(
					''                   => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'post_name'          => __( 'Post Name', 'pdf-generator-for-wordpress' ),
					'document_productid' => __( 'Document_ProductID', 'pdf-generator-for-wordpress' ),
					'custom'             => __( 'Custom', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Please Enter the Custom File Name', 'pdf-generator-for-wordpress' ),
				'type'        => 'text',
				'description' => __( 'For custom file name, product/page/post id will be used as suffix.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_custom_pdf_file_name',
				'class'       => 'pgfw_custom_pdf_file_name',
				'name'        => 'pgfw_custom_pdf_file_name',
				'value'       => $pgfw_pdf_file_name_custom,
				'style'       => ( 'custom' !== $pgfw_pdf_file_name ) ? 'display:none;' : '',
				'placeholder' => 'File Name',
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_general_settings_save',
				'button_text' => __( 'Save Setting', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_general_settings_save',
				'name'        => 'pgfw_general_settings_save',
			),
		);
		return $pgfw_settings_general_html_arr;
	}
	/**
	 * PDF Generator For WordPress save tab settings.
	 *
	 * @since 1.0.0
	 */
	public function pgfw_admin_save_tab_settings() {
		global $pgfw_mwb_pgfw_obj, $mwb_pgfw_gen_flag, $pgfw_save_check_flag;
		$settings_general_arr = array();
		$pgfw_save_check_flag = false;
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
				$mwb_pgfw_gen_flag = false;
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
								} else {
									if ( isset( $_POST[ $pgfw_genaral_setting['id'] ] ) ) {
										$settings_general_arr[ $pgfw_genaral_setting['id'] ] = is_array( $_POST[ $pgfw_genaral_setting['id'] ] ) ? map_deep( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ), 'sanitize_text_field' ) : sanitize_text_field( wp_unslash( $_POST[ $pgfw_genaral_setting['id'] ] ) );
									} else {
										$settings_general_arr[ $pgfw_genaral_setting['id'] ] = '';
									}
								}
							} else {
								$mwb_pgfw_gen_flag = true;
							}
						}
					}
					if ( ! $mwb_pgfw_gen_flag ) {
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
		$pgfw_bulk_download_enable         = array_key_exists( 'pgfw_bulk_download_enable', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_bulk_download_enable'] : '';
		$pgfw_guest_download_or_email      = array_key_exists( 'pgfw_guest_download_or_email', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_guest_download_or_email'] : '';
		$pgfw_user_download_or_email       = array_key_exists( 'pgfw_user_download_or_email', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_user_download_or_email'] : '';
		$pgfw_pdf_icon_after               = array_key_exists( 'pgfw_display_pdf_icon_after', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_after'] : '';
		$pgfw_pdf_icon_alignment           = array_key_exists( 'pgfw_display_pdf_icon_alignment', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_display_pdf_icon_alignment'] : '';
		$sub_pgfw_pdf_single_download_icon = array_key_exists( 'sub_pgfw_pdf_single_download_icon', $pgfw_display_settings ) ? $pgfw_display_settings['sub_pgfw_pdf_single_download_icon'] : '';
		$sub_pgfw_pdf_bulk_download_icon   = array_key_exists( 'sub_pgfw_pdf_bulk_download_icon', $pgfw_display_settings ) ? $pgfw_display_settings['sub_pgfw_pdf_bulk_download_icon'] : '';
		$pgfw_pdf_icon_width               = array_key_exists( 'pgfw_pdf_icon_width', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_width'] : '';
		$pgfw_pdf_icon_height              = array_key_exists( 'pgfw_pdf_icon_height', $pgfw_display_settings ) ? $pgfw_display_settings['pgfw_pdf_icon_height'] : '';
		$pgfw_pdf_icon_places              = array(
			''               => __( 'Select option', 'pdf-generator-for-wordpress' ),
			'before_content' => __( 'Before Content', 'pdf-generator-for-wordpress' ),
			'after_content'  => __( 'After Content', 'pdf-generator-for-wordpress' ),
		);
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			$woocommerce_hook_arr = array(
				'woocommerce_before_add_to_cart_form'      => __( 'Before Add to Cart Form', 'pdf-generator-for-wordpress' ),
				'woocommerce_product_meta_start'           => __( 'Before Product Meta Start', 'pdf-generator-for-wordpress' ),
				'woocommerce_product_meta_end'             => __( 'After Add to Cart Form', 'pdf-generator-for-wordpress' ),
				'woocommerce_after_single_product_summary' => __( 'After Single Product Summary', 'pdf-generator-for-wordpress' ),
				'woocommerce_before_single_product_summary' => __( 'Before Single Product Summary', 'pdf-generator-for-wordpress' ),
				'woocommerce_after_single_product'         => __( 'After Single Product', 'pdf-generator-for-wordpress' ),
				'woocommerce_before_single_product'        => __( 'Before Single Product', 'pdf-generator-for-wordpress' ),
				'woocommerce_share'                        => __( 'After Share Button', 'pdf-generator-for-wordpress' ),
			);
			foreach ( $woocommerce_hook_arr as $hooks => $name ) {
				$pgfw_pdf_icon_places[ $hooks ] = $name;
			}
		}

		$pgfw_settings_display_fields_html_arr = array(
			array(
				'title'       => __( 'Logged in Users', 'pdf-generator-for-wordpress' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to logged in users to download pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_user_access',
				'value'       => $pgfw_user_access,
				'class'       => 'pgfw_user_access',
				'name'        => 'pgfw_user_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wordpress' ),
					'no'  => __( 'NO', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Guest', 'pdf-generator-for-wordpress' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to guest users to download pdf', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_guest_access',
				'value'       => $pgfw_guest_access,
				'class'       => 'pgfw_guest_access',
				'name'        => 'pgfw_guest_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wordpress' ),
					'no'  => __( 'NO', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Enable Bulk Download', 'pdf-generator-for-wordpress' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to bulk download pdf', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_bulk_download_enable',
				'value'       => $pgfw_bulk_download_enable,
				'class'       => 'pgfw_bulk_download_enable',
				'name'        => 'pgfw_bulk_download_enable',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wordpress' ),
					'no'  => __( 'NO', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'        => __( 'Direct download or Email User', 'pdf-generator-for-wordpress' ),
				'type'         => 'select',
				'description'  => __( 'Please choose either to direct download or to email user', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_user_download_or_email',
				'value'        => $pgfw_user_download_or_email,
				'class'        => 'pgfw_user_download_or_email',
				'name'         => 'pgfw_user_download_or_email',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'options'      => array(
					''                => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'direct_download' => __( 'Direct Download', 'pdf-generator-for-wordpress' ),
					'email'           => __( 'Email', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'        => __( 'Direct download or Email Guest', 'pdf-generator-for-wordpress' ),
				'type'         => 'select',
				'description'  => __( 'Please choose either to direct download or to email guest', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_guest_download_or_email',
				'value'        => $pgfw_guest_download_or_email,
				'class'        => 'pgfw_guest_download_or_email',
				'name'         => 'pgfw_guest_download_or_email',
				'parent-class' => '',
				'options'      => array(
					''                => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'direct_download' => __( 'Direct Download', 'pdf-generator-for-wordpress' ),
					'email'           => __( 'Email', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'        => __( 'Show Pdf Icon', 'pdf-generator-for-wordpress' ),
				'type'         => 'select',
				'description'  => __( 'Pdf Icon will be shown after selected space.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_display_pdf_icon_after',
				'value'        => $pgfw_pdf_icon_after,
				'class'        => 'pgfw_display_pdf_icon_after',
				'name'         => 'pgfw_display_pdf_icon_after',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'options'      => $pgfw_pdf_icon_places,
			),
			array(
				'title'       => __( 'Pdf Icon Alignment', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Pdf Icon will be aligned according to the selected value.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_display_pdf_icon_alignment',
				'value'       => $pgfw_pdf_icon_alignment,
				'class'       => 'pgfw_display_pdf_icon_alignment',
				'name'        => 'pgfw_display_pdf_icon_alignment',
				'options'     => array(
					''       => __( 'Please Choose', 'pdf-generator-for-wordpress' ),
					'left'   => __( 'Left', 'pdf-generator-for-wordpress' ),
					'center' => __( 'Center', 'pdf-generator-for-woocommerce' ),
					'right'  => __( 'Right', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'        => __( 'Choose Single Download Pdf Icon', 'pdf-generator-for-wordpress' ),
				'type'         => 'upload-button',
				'button_text'  => __( 'Upload Icon', 'pdf-generator-for-wordpress' ),
				'class'        => 'sub_pgfw_pdf_single_download_icon',
				'id'           => 'sub_pgfw_pdf_single_download_icon',
				'value'        => $sub_pgfw_pdf_single_download_icon,
				'sub_id'       => 'pgfw_pdf_single_download_icon',
				'sub_class'    => 'pgfw_pdf_single_download_icon',
				'sub_name'     => 'pgfw_pdf_single_download_icon',
				'name'         => 'sub_pgfw_pdf_single_download_icon',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'img-tag'      => array(
					'img-class' => 'pgfw_single_pdf_icon_image',
					'img-id'    => 'pgfw_single_pdf_icon_image',
					'img-style' => ( $sub_pgfw_pdf_single_download_icon ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
					'img-src'   => $sub_pgfw_pdf_single_download_icon,
				),
				'img-remove'   => array(
					'btn-class' => 'pgfw_single_pdf_icon_image_remove',
					'btn-id'    => 'pgfw_single_pdf_icon_image_remove',
					'btn-text'  => __( 'Remove Icon', 'pdf-generator-for-wordpress' ),
					'btn-title' => __( 'Remove Icon', 'pdf-generator-for-wordpress' ),
					'btn-name'  => 'pgfw_single_pdf_icon_image_remove',
					'btn-style' => ! ( $sub_pgfw_pdf_single_download_icon ) ? 'display:none' : $sub_pgfw_pdf_single_download_icon,
				),
			),
			array(
				'title'       => __( 'Choose Bulk Download Pdf Icon', 'pdf-generator-for-wordpress' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Icon', 'pdf-generator-for-wordpress' ),
				'class'       => 'sub_pgfw_pdf_bulk_download_icon',
				'id'          => 'sub_pgfw_pdf_bulk_download_icon',
				'value'       => $sub_pgfw_pdf_bulk_download_icon,
				'sub_id'      => 'pgfw_pdf_bulk_download_icon',
				'sub_class'   => 'pgfw_pdf_bulk_download_icon',
				'sub_name'    => 'pgfw_pdf_bulk_download_icon',
				'name'        => 'sub_pgfw_pdf_bulk_download_icon',
				'img-tag'     => array(
					'img-class' => 'pgfw_bulk_pdf_icon_image',
					'img-id'    => 'pgfw_bulk_pdf_icon_image',
					'img-style' => ( $sub_pgfw_pdf_bulk_download_icon ) ? 'margin:10px;height:45px;width:45px;' : 'display:none;margin:10px;height:45px;width:45px;',
					'img-src'   => $sub_pgfw_pdf_bulk_download_icon,
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_bulk_pdf_icon_image_remove',
					'btn-id'    => 'pgfw_bulk_pdf_icon_image_remove',
					'btn-text'  => __( 'Remove Icon', 'pdf-generator-for-wordpress' ),
					'btn-title' => __( 'Remove Icon', 'pdf-generator-for-wordpress' ),
					'btn-name'  => 'pgfw_bulk_pdf_icon_image_remove',
					'btn-style' => ! ( $sub_pgfw_pdf_bulk_download_icon ) ? 'display:none' : $sub_pgfw_pdf_single_download_icon,
				),
			),
			array(
				'title'       => __( 'Icon size', 'pdf-generator-for-wordpress' ),
				'type'        => 'multi',
				'id'          => 'pgfw_pdf_icons_sizes',
				'description' => __( 'Enter icon width and height in pixels', 'pgfw-generator-for-wordpress' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_pdf_icon_width',
						'class'       => 'pgfw_pdf_icon_width',
						'name'        => 'pgfw_pdf_icon_width',
						'placeholder' => __( 'width', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_pdf_icon_width,
						'min'         => 0,
						'max'         => 50,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_pdf_icon_height',
						'class'       => 'pgfw_pdf_icon_height',
						'name'        => 'pgfw_pdf_icon_height',
						'placeholder' => __( 'height', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_pdf_icon_height,
						'min'         => 0,
						'max'         => 50,
					),
				),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_save_admin_display_settings',
				'button_text' => __( 'Save Setting', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_save_admin_display_settings',
				'name'        => 'pgfw_save_admin_display_settings',
			),
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

		$pgfw_settings_header_fields_html_arr = array(
			array(
				'title'       => __( 'Include Header', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this to include header on the page.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_header_use_in_pdf',
				'value'       => $pgfw_header_use_in_pdf,
				'class'       => 'pgfw_header_use_in_pdf',
				'name'        => 'pgfw_header_use_in_pdf',
			),
			array(
				'title'       => __( 'Choose logo', 'pdf-generator-for-wordpress' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Image', 'pdf-generator-for-wordpress' ),
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
					'img-style' => ( $pgfw_header_logo ) ? 'margin:10px;' : 'display:none;margin:10px;',
					'img-src'   => $pgfw_header_logo,
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_header_image_remove',
					'btn-id'    => 'pgfw_header_image_remove',
					'btn-text'  => __( 'Remove image', 'pdf-generator-for-wordpress' ),
					'btn-title' => __( 'Remove image', 'pdf-generator-for-wordpress' ),
					'btn-name'  => 'pgfw_header_image_remove',
					'btn-style' => ! ( $pgfw_header_logo ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'Company name', 'pdf-generator-for-wordpress' ),
				'type'        => 'text',
				'description' => __( 'Company name will be displayed in the right side of the header', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_header_company_name',
				'value'       => $pgfw_header_comp_name,
				'class'       => 'pgfw_header_company_name',
				'name'        => 'pgfw_header_company_name',
				'placeholder' => __( 'company name', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Tagline or address', 'pdf-generator-for-wordpress' ),
				'type'        => 'textarea',
				'class'       => 'pgfw_header_tagline',
				'id'          => 'pgfw_header_tagline',
				'name'        => 'pgfw_header_tagline',
				'description' => __( 'Enter the tagline or address to show in header' ),
				'placeholder' => __( 'tagline or address', 'pdf-generator-for-wordpress' ),
				'value'       => $pgfw_header_tagline,
			),
			array(
				'title'       => __( 'Choose color', 'pdf-generator-for-wordpress' ),
				'type'        => 'color',
				'description' => __( 'Please choose text color to display in the header', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_header_color',
				'value'       => $pgfw_header_color,
				'class'       => 'pgfw_color_picker pgfw_header_color',
				'name'        => 'pgfw_header_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Header Width', 'pdf-generator-for-wordpress' ),
				'type'        => 'number',
				'description' => __( 'Please enter width to display in the header accepted values are in px, please enter number only', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_header_width',
				'value'       => $pgfw_header_width,
				'class'       => 'pgfw_header_width',
				'name'        => 'pgfw_header_width',
				'placeholder' => __( 'width', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Choose font style', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Please choose font style to display in the header', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_header_font_style',
				'value'       => $pgfw_header_font_style,
				'class'       => 'pgfw_header_font_style',
				'name'        => 'pgfw_header_font_style',
				'placeholder' => __( 'font style', 'pdf-generator-for-wordpress' ),
				'options'     => array(
					''            => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'times-roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
					'symbol'      => __( 'Symbol', 'pdf-generator-for-wordpress' ),
					'zapfdinbats' => __( 'Zapfdinbats', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Choose font size', 'pdf-generator-for-wordpress' ),
				'type'        => 'number',
				'description' => __( 'Please choose font size to display in the header', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_header_font_size',
				'value'       => $pgfw_header_font_size,
				'class'       => 'pgfw_header_font_size',
				'name'        => 'pgfw_header_font_size',
				'placeholder' => __( 'font size', 'pdf-generator-for-wordpress' ),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_header_setting_submit',
				'button_text' => __( 'Save Settings', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_header_setting_submit',
				'name'        => 'pgfw_header_setting_submit',
			),
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

		$pgfw_settings_footer_fields_html_arr = array(
			array(
				'title'       => __( 'Include Footer', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this include footer on the page.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_footer_use_in_pdf',
				'value'       => $pgfw_footer_use_in_pdf,
				'class'       => 'pgfw_footer_use_in_pdf',
				'name'        => 'pgfw_footer_use_in_pdf',
			),
			array(
				'title'       => __( 'Tagline', 'pdf-generator-for-wordpress' ),
				'type'        => 'textarea',
				'class'       => 'pgfw_footer_tagline',
				'id'          => 'pgfw_footer_tagline',
				'name'        => 'pgfw_footer_tagline',
				'description' => __( 'Enter the tagline to show in footer' ),
				'placeholder' => __( 'tagline', 'pdf-generator-for-wordpress' ),
				'value'       => $pgfw_footer_tagline,
			),
			array(
				'title'       => __( 'Choose color', 'pdf-generator-for-wordpress' ),
				'type'        => 'color',
				'description' => __( 'Please choose color to display in the footer', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_footer_color',
				'value'       => $pgfw_footer_color,
				'class'       => 'pgfw_color_picker pgfw_footer_color',
				'name'        => 'pgfw_footer_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Choose width', 'pdf-generator-for-wordpress' ),
				'type'        => 'number',
				'description' => __( 'Please choose width to display in the footer accepted values are in px, please enter number only', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_footer_width',
				'value'       => $pgfw_footer_width,
				'class'       => 'pgfw_footer_width',
				'name'        => 'pgfw_footer_width',
				'placeholder' => __( 'width', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Choose font style', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Please choose font style to display in the footer', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_footer_font_style',
				'value'       => $pgfw_footer_font_style,
				'class'       => 'pgfw_footer_font_style',
				'name'        => 'pgfw_footer_font_style',
				'placeholder' => __( 'font style', 'pdf-generator-for-wordpress' ),
				'options'     => array(
					''            => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'times-roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
					'symbol'      => __( 'Symbol', 'pdf-generator-for-wordpress' ),
					'zapfdinbats' => __( 'Zapfdinbats', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Choose font size', 'pdf-generator-for-wordpress' ),
				'type'        => 'number',
				'description' => __( 'Please choose font size to display in the footer', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_footer_font_size',
				'value'       => $pgfw_footer_font_size,
				'class'       => 'pgfw_footer_font_size',
				'name'        => 'pgfw_footer_font_size',
				'placeholder' => __( 'font size', 'pdf-generator-for-wordpress' ),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_footer_setting_submit',
				'button_text' => __( 'Save Settings', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_footer_setting_submit',
				'name'        => 'pgfw_footer_setting_submit',
			),
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
		$pgfw_body_settings         = get_option( 'pgfw_body_save_settings', array() );
		$pgfw_body_title_font_style = array_key_exists( 'pgfw_body_title_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_style'] : '';
		$pgfw_body_title_font_size  = array_key_exists( 'pgfw_body_title_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_size'] : '';
		$pgfw_body_title_font_color = array_key_exists( 'pgfw_body_title_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_color'] : '';
		$pgfw_body_page_size        = array_key_exists( 'pgfw_body_page_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_size'] : '';
		$pgfw_body_page_orientation = array_key_exists( 'pgfw_body_page_orientation', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_orientation'] : '';
		$pgfw_body_page_font_style  = array_key_exists( 'pgfw_body_page_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_font_style'] : '';
		$pgfw_body_page_font_size   = array_key_exists( 'pgfw_content_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_content_font_size'] : '';
		$pgfw_body_page_font_color  = array_key_exists( 'pgfw_body_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_font_color'] : '';
		$pgfw_body_border_size      = array_key_exists( 'pgfw_body_border_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_border_size'] : '';
		$pgfw_body_border_color     = array_key_exists( 'pgfw_body_border_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_border_color'] : '';
		$pgfw_body_margin_top       = array_key_exists( 'pgfw_body_margin_top', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_top'] : '';
		$pgfw_body_margin_left      = array_key_exists( 'pgfw_body_margin_left', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_left'] : '';
		$pgfw_body_margin_right     = array_key_exists( 'pgfw_body_margin_right', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_right'] : '';
		$pgfw_body_rtl_support      = array_key_exists( 'pgfw_body_rtl_support', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_rtl_support'] : '';
		$pgfw_body_add_watermark    = array_key_exists( 'pgfw_body_add_watermark', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_add_watermark'] : '';
		$pgfw_body_watermark_text   = array_key_exists( 'pgfw_body_watermark_text', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_watermark_text'] : '';
		$pgfw_body_watermark_color  = array_key_exists( 'pgfw_body_watermark_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_watermark_color'] : '';
		$pgfw_body_page_template    = array_key_exists( 'pgfw_body_page_template', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_template'] : '';
		$pgfw_body_post_template    = array_key_exists( 'pgfw_body_post_template', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_post_template'] : '';

		$pgfw_body_html_arr = array(
			array(
				'title'       => __( 'Title Font Style', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Please choose page size to generate pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_title_font_style',
				'value'       => $pgfw_body_title_font_style,
				'class'       => 'pgfw_body_title_font_style',
				'name'        => 'pgfw_body_title_font_style',
				'placeholder' => __( 'title font_style', 'pdf-generator-for-wordpress' ),
				'options'     => array(
					''            => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'times-roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
					'symbol'      => __( 'Symbol', 'pdf-generator-for-wordpress' ),
					'zapfdinbats' => __( 'Zapfdinbats', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Title Font Size', 'pdf-generator-for-wordpress' ),
				'type'        => 'number',
				'description' => __( 'This will be the font size of the title.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_title_font_size',
				'value'       => $pgfw_body_title_font_size,
				'class'       => 'pgfw_body_title_font_size',
				'name'        => 'pgfw_body_title_font_size',
				'placeholder' => __( 'title font size', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Choose Title color', 'pdf-generator-for-wordpress' ),
				'type'        => 'color',
				'description' => __( 'Please choose color to display in the footer', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_title_font_color',
				'value'       => $pgfw_body_title_font_color,
				'class'       => 'pgfw_color_picker pgfw_body_title_font_color',
				'name'        => 'pgfw_body_title_font_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'        => __( 'Page Size', 'pdf-generator-for-wordpress' ),
				'type'         => 'select',
				'description'  => __( 'Please choose page size to generate pdf.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_body_page_size',
				'value'        => $pgfw_body_page_size,
				'class'        => 'pgfw_body_page_size',
				'name'         => 'pgfw_body_page_size',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'placeholder'  => __( 'page size', 'pdf-generator-for-wordpress' ),
				'options'      => array(
					''          => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'4A0'       => __( '4A0', 'pdf-generator-for-wordpress' ),
					'2A0'       => __( '2A0', 'pdf-generator-for-wordpress' ),
					'A0'        => __( 'A0', 'pdf-generator-for-wordpress' ),
					'A1'        => __( 'A1', 'pdf-generator-for-wordpress' ),
					'A2'        => __( 'A2', 'pdf-generator-for-wordpress' ),
					'A3'        => __( 'A3', 'pdf-generator-for-wordpress' ),
					'A4'        => __( 'A4', 'pdf-generator-for-wordpress' ),
					'A5'        => __( 'A5', 'pdf-generator-for-wordpress' ),
					'A6'        => __( 'A6', 'pdf-generator-for-wordpress' ),
					'A7'        => __( 'A7', 'pdf-generator-for-wordpress' ),
					'A8'        => __( 'A8', 'pdf-generator-for-wordpress' ),
					'A9'        => __( 'A9', 'pdf-generator-for-wordpress' ),
					'A10'       => __( 'A10', 'pdf-generator-for-wordpress' ),
					'letter'    => __( 'Letter', 'pdf-generator-for-wordpress' ),
					'legal'     => __( 'Legal', 'pdf-generator-for-wordpress' ),
					'executive' => __( 'Executive', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Page Orientation', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Choose page orientation to generate pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_page_orientation',
				'value'       => $pgfw_body_page_orientation,
				'class'       => 'pgfw_body_page_orientation',
				'name'        => 'pgfw_body_page_orientation',
				'placeholder' => __( 'page orientation', 'pdf-generator-for-wordpress' ),
				'options'     => array(
					''          => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'landscape' => __( 'Landscape', 'pdf-generator-for-wordpress' ),
					'portrait'  => __( 'Portrait', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'        => __( 'Content Font Style', 'pdf-generator-for-wordpress' ),
				'type'         => 'select',
				'description'  => __( 'Choose page font to generate pdf.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_body_page_font_style',
				'value'        => $pgfw_body_page_font_style,
				'class'        => 'pgfw_body_page_font_style',
				'name'         => 'pgfw_body_page_font_style',
				'placeholder'  => __( 'page font', 'pdf-generator-for-wordpress' ),
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'options'      => array(
					''            => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'times-roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
					'symbol'      => __( 'Symbol', 'pdf-generator-for-wordpress' ),
					'zapfdinbats' => __( 'Zapfdinbats', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Content font Size', 'pdf-generator-for-wordpress' ),
				'type'        => 'number',
				'description' => __( 'Choose content font size to generate pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_content_font_size',
				'value'       => $pgfw_body_page_font_size,
				'class'       => 'pgfw_content_font_size',
				'placeholder' => '',
			),
			array(
				'title'        => __( 'Choose body text color', 'pdf-generator-for-wordpress' ),
				'type'         => 'color',
				'description'  => __( 'Choose color to display in the footer', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_body_font_color',
				'value'        => $pgfw_body_page_font_color,
				'class'        => 'pgfw_color_picker pgfw_body_font_color',
				'name'         => 'pgfw_body_font_color',
				'placeholder'  => __( 'color', 'pdf-generator-for-wordpress' ),
				'parent-class' => 'mwb_pgfw_setting_separate_border',
			),
			array(
				'title'       => __( 'Border', 'pdf-generator-for-wordpress' ),
				'type'        => 'multi',
				'id'          => 'pgfw_body_border',
				'description' => __( 'Choose border: size in px and color.', 'pgfw-generator-for-wordpress' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_border_size',
						'class'       => 'pgfw_body_border_size',
						'name'        => 'pgfw_body_border_size',
						'placeholder' => __( 'border size', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_body_border_size,
						'min'         => 0,
						'max'         => 50,
					),
					array(
						'type'        => 'color',
						'id'          => 'pgfw_body_border_color',
						'class'       => 'pgfw_color_picker pgfw_body_border_color',
						'name'        => 'pgfw_body_border_color',
						'placeholder' => __( 'border color', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_body_border_color,
					),
				),
			),
			array(
				'title'       => __( 'Page Margin', 'pgfw-generator-for-wordpress' ),
				'id'          => 'pgfw_body_margin',
				'type'        => 'multi',
				'description' => __( 'Enter page margin : top, left, right.', 'pgfw-generator-for-wordpress' ),
				'value'       => array(
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_top',
						'class'       => 'pgfw_body_margin_top',
						'name'        => 'pgfw_body_margin_top',
						'placeholder' => __( 'Top', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_body_margin_top,
						'min'         => 0,
						'max'         => 50,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_left',
						'class'       => 'pgfw_body_margin_left',
						'name'        => 'pgfw_body_margin_left',
						'placeholder' => __( 'Left', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_body_margin_left,
						'min'         => 0,
						'max'         => 50,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_right',
						'class'       => 'pgfw_body_margin_right',
						'name'        => 'pgfw_body_margin_right',
						'placeholder' => __( 'Right', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_body_margin_right,
						'min'         => 0,
						'max'         => 50,
					),
				),
			),
			array(
				'title'        => __( 'RTL support', 'pdf-generator-for-wordpress' ),
				'type'         => 'checkbox',
				'description'  => __( 'Select this to enable RTL support.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_body_rtl_support',
				'value'        => $pgfw_body_rtl_support,
				'class'        => 'pgfw_body_rtl_support',
				'name'         => 'pgfw_body_rtl_support',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
			),
			array(
				'title'       => __( 'Add Watermark', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this to add watermark on the created PDF.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_add_watermark',
				'value'       => $pgfw_body_add_watermark,
				'class'       => 'pgfw_body_add_watermark',
				'name'        => 'pgfw_body_add_watermark',
			),
			array(
				'title'       => __( 'Watermark text', 'pdf-generator-for-wordpress' ),
				'type'        => 'textarea',
				'description' => __( 'Enter text to be used as watermark.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_watermark_text',
				'value'       => $pgfw_body_watermark_text,
				'class'       => 'pgfw_body_watermark_text',
				'name'        => 'pgfw_body_watermark_text',
				'placeholder' => __( 'Watermark text', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Choose watermark text color', 'pdf-generator-for-wordpress' ),
				'type'        => 'color',
				'description' => __( 'Please choose color to display the text of watermark', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_watermark_color',
				'value'       => $pgfw_body_watermark_color,
				'class'       => 'pgfw_color_picker pgfw_body_watermark_color',
				'name'        => 'pgfw_body_watermark_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'        => __( 'Page template', 'pdf-generator-for-wordpress' ),
				'type'         => 'select',
				'description'  => __( 'This will be used as the page template.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_body_page_template',
				'value'        => $pgfw_body_page_template,
				'class'        => 'pgfw_body_page_template',
				'name'         => 'pgfw_body_page_template',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'options'      => array(
					''          => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'template1' => __( 'Template1', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Post template', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'This will be used as the post template.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_post_template',
				'value'       => $pgfw_body_post_template,
				'class'       => 'pgfw_body_post_template',
				'name'        => 'pgfw_body_post_template',
				'options'     => array(
					''          => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'template1' => __( 'Template1', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_body_save_settings',
				'button_text' => __( 'Save settings', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_body_save_settings',
				'name'        => 'pgfw_body_save_settings',
			),
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

		$pgfw_advanced_settings_html_arr = array(
			array(
				'title'       => __( 'Show Icons for Post Type', 'pdf-generator-for-wordpress' ),
				'type'        => 'multiselect',
				'description' => __( 'PDF generate icons will be visible to selected post types.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_advanced_show_post_type_icons',
				'value'       => $pgfw_advanced_icon_show,
				'class'       => 'pgfw-multiselect-class mwb-defaut-multiselect pgfw_advanced_show_post_type_icons',
				'name'        => 'pgfw_advanced_show_post_type_icons',
				'options'     => $post_types,
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_advanced_save_settings',
				'button_text' => __( 'Save settings', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_advanced_save_settings',
				'name'        => 'pgfw_advanced_save_settings',
			),
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
				),
			);
			foreach ( $posts as $post ) {
				$post_meta_keys = get_post_custom_keys( $post->ID );
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
				'title'        => __( 'Show meta fields for ', 'pdf-generator-for-wordpress' ) . $post_type,
				'type'         => 'checkbox',
				'description'  => __( 'selecting this will show the meta fields on pdf.', 'pdf-generator-for-wordpress' ),
				'id'           => 'pgfw_meta_fields_' . $post_type . '_show',
				'value'        => $pgfw_show_type_meta_val,
				'class'        => 'pgfw_meta_fields_' . $post_type . '_show',
				'name'         => 'pgfw_meta_fields_' . $post_type . '_show',
				'parent-class' => ( 0 === $i ) ? '' : 'mwb_pgfw_setting_separate_border',
			);
			$pgfw_meta_settings_html_arr[] = array(
				'title'       => __( 'Meta fields in ', 'pdf-generator-for-wordpress' ) . $post_type,
				'type'        => 'multiselect',
				'description' => __( 'These meta fields will be shown on pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_meta_fields_' . $post_type . '_list',
				'name'        => 'pgfw_meta_fields_' . $post_type . '_list',
				'value'       => $pgfw_show_type_meta_arr,
				'class'       => 'pgfw-multiselect-class mwb-defaut-multiselect pgfw_meta_fields_' . $post_type . '_list',
				'placeholder' => '',
				'options'     => $post_meta_field,
			);
			$i++;
		}
		$pgfw_meta_settings_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_meta_fields_save_settings',
			'button_text' => __( 'Save settings', 'pdf-generator-for-wordpress' ),
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
				'title'       => __( 'Access to Users', 'pdf-generator-for-wordpress' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to Logged in users to download Posters.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_poster_user_access',
				'value'       => $pgfw_poster_user_access,
				'class'       => 'pgfw_poster_user_access',
				'name'        => 'pgfw_poster_user_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wordpress' ),
					'no'  => __( 'NO', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Access to Guests', 'pdf-generator-for-wordpress' ),
				'type'        => 'radio-switch',
				'description' => __( 'Enable this to give access to Guests to Download Posters.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_poster_guest_access',
				'value'       => $pgfw_poster_guest_access,
				'class'       => 'pgfw_poster_guest_access',
				'name'        => 'pgfw_poster_guest_access',
				'options'     => array(
					'yes' => __( 'YES', 'pdf-generator-for-wordpress' ),
					'no'  => __( 'NO', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'        => __( 'Choose Poster', 'pdf-generator-for-wordpress' ),
				'type'         => 'upload-button',
				'button_text'  => __( 'Upload Doc', 'pdf-generator-for-wordpress' ),
				'class'        => 'sub_pgfw_poster_image_upload',
				'id'           => 'sub_pgfw_poster_image_upload',
				'value'        => is_array( $pgfw_poster_doc ) ? wp_json_encode( $pgfw_poster_doc ) : $pgfw_poster_doc,
				'sub_id'       => 'pgfw_poster_image_upload',
				'sub_class'    => 'pgfw_poster_image_upload',
				'sub_name'     => 'pgfw_poster_image_upload',
				'name'         => 'sub_pgfw_poster_image_upload',
				'parent-class' => 'mwb_pgfw_setting_separate_border',
				'img-tag'      => array(
					'img-class' => 'pgfw_poster_image',
					'img-id'    => 'pgfw_poster_image',
					'img-style' => ( $pgfw_poster_doc ) ? 'margin:10px;height:35px;width:35px;' : 'display:none;margin:10px;height:35px;width:35px;',
					'img-src'   => PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/images/document-management-big.png',
				),
				'img-remove'   => array(
					'btn-class' => 'pgfw_poster_image_remove',
					'btn-id'    => 'pgfw_poster_image_remove',
					'btn-text'  => __( 'Remove doc', 'pdf-generator-for-wordpress' ),
					'btn-title' => __( 'Remove doc', 'pdf-generator-for-wordpress' ),
					'btn-name'  => 'pgfw_poster_image_remove',
					'btn-style' => ! ( $pgfw_poster_doc ) ? 'display:none' : '',
				),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_pdf_upload_save_settings',
				'button_text' => __( 'Save settings', 'pdf-generator-for-wordpress' ),
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
	public function mwb_pgfw_delete_poster_by_media_id_from_table() {
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
}
