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
		wp_enqueue_script( 'mwb-pgfw-admin-custom-setting-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/js/pdf-generator-for-wordpress-admin-custom.js', array( 'jquery', 'wp-color-picker' ), $this->version, true );
		wp_localize_script(
			'mwb-pgfw-admin-custom-setting-js',
			'mwb_pgfw_save_settings_obj',
			array(
				'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				'nonce'                 => wp_create_nonce( 'pgfw_setting_save_nonce' ),
				'loader_url'            => PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/images/loader.gif',
				'saved_msg'             => __( 'Re Submit', 'pdf-generator-for-wordpress' ),
				'custom_file_error_msg' => '<div class="notice notice-error is-dismissible">
												<p>' . esc_html__( 'Please fill custom file name if you choose the file name to be custom.', 'pdf-generator-for-wordpress' ) . '</p>
											</div>',
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
		$general_settings_data     = get_option( 'mwb_pgfw_general_settings', array() );
		$pgfw_enable_plugin        = array_key_exists( 'enable_plugin', $general_settings_data ) ? $general_settings_data['enable_plugin'] : '';
		$pgfw_default_pdf_icon     = array_key_exists( 'default_pdf_icon', $general_settings_data ) ? $general_settings_data['default_pdf_icon'] : '';
		$pgfw_show_post_categories = array_key_exists( 'show_post_categories', $general_settings_data ) ? $general_settings_data['show_post_categories'] : '';
		$pgfw_show_post_tags       = array_key_exists( 'show_post_tags', $general_settings_data ) ? $general_settings_data['show_post_tags'] : '';
		$pgfw_show_post_date       = array_key_exists( 'show_post_date', $general_settings_data ) ? $general_settings_data['show_post_date'] : '';
		$pgfw_show_post_author     = array_key_exists( 'show_post_author', $general_settings_data ) ? $general_settings_data['show_post_author'] : '';
		$pgfw_pdf_generate_mode    = array_key_exists( 'pdf_generate_mode', $general_settings_data ) ? $general_settings_data['pdf_generate_mode'] : '';
		$pgfw_pdf_file_name        = array_key_exists( 'pdf_file_name', $general_settings_data ) ? $general_settings_data['pdf_file_name'] : '';
		$pgfw_pdf_file_name_custom = array_key_exists( 'pdf_file_name_custom', $general_settings_data ) ? $general_settings_data['pdf_file_name_custom'] : '';

		$pgfw_settings_general_html_arr = array(
			array(
				'title'       => __( 'Enable plugin', 'pdf-generator-for-wordpress' ),
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
				'title'       => __( 'Default PDF Icon', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Default PDF icon will be shown for downloading pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_general_pdf_icon_default',
				'value'       => $pgfw_default_pdf_icon,
				'class'       => 'pgfw_general_pdf_icon_default',
				'name'        => 'pgfw_general_pdf_icon_default',
			),
			array(
				'title'       => __( 'Include Categories', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Categories will be shown on PDF.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_general_pdf_show_categories',
				'value'       => $pgfw_show_post_categories,
				'class'       => 'pgfw_general_pdf_show_categories',
				'name'        => 'pgfw_general_pdf_show_categories',
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
				'title'       => __( 'PDF Download option', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Please choose either to download or open window.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_general_pdf_generate_mode',
				'value'       => $pgfw_pdf_generate_mode,
				'class'       => 'pgfw_general_pdf_generate_mode',
				'name'        => 'pgfw_general_pdf_generate_mode',
				'options'     => array(
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
				'title'       => __( 'Please enter the custom file name', 'pdf-generator-for-wordpress' ),
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
	 * Saving general settings through ajax.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mwb_pgfw_saving_settings() {
		check_ajax_referer( 'pgfw_setting_save_nonce', 'nonce' );
		$setting_arr_data = array_key_exists( 'setting_arr_data', $_POST ) ? map_deep( wp_unslash( $_POST['setting_arr_data'] ), 'sanitize_text_field' ) : '';
		$key_to_save      = array_key_exists( 'key_to_save', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['key_to_save'] ) ) : '';
		if ( '' !== $key_to_save && '' !== $setting_arr_data ) {
			update_option( $key_to_save, $setting_arr_data );
			?>
			<div class="notice notice-success is-dismissible">
				<p><?php esc_html_e( 'Settings saved successfully', 'pdf-generator-for-wordpress' ); ?></p>
			</div>
			<?php
		} else {
			?>
			<div class="notice notice-error is-dismissible">
				<p><?php esc_html_e( 'There might be some error please reload the page and submit again.', 'pdf-generator-for-wordpress' ); ?></p>
			</div>
			<?php
		}
		wp_die();
	}
	/**
	 * Html fields for display setting.
	 *
	 * @since 1.0.0
	 * @param array $pgfw_settings_display_fields_html_arr array containing html fields.
	 * @return array
	 */
	public function pgfw_admin_display_settings_page( $pgfw_settings_display_fields_html_arr ) {
		$pgfw_display_settings = get_option( 'mwb_pgfw_display_settings', array() );
		$pgfw_user_access      = array_key_exists( 'user_access', $pgfw_display_settings ) ? $pgfw_display_settings['user_access'] : '';
		$pgfw_guest_access     = array_key_exists( 'guest_access', $pgfw_display_settings ) ? $pgfw_display_settings['guest_access'] : '';

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
				'type'        => 'button',
				'id'          => 'pgfw_save_admin_display_settings',
				'button_text' => __( 'Save Setting', 'pdf-generator-for-wordpress' ),
				'name'        => 'pgfw_save_admin_display_settings',
				'class'       => 'pgfw_save_admin_display_settings',
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
		$pgfw_header_settings   = get_option( 'mwb_pgfw_header_settings', array() );
		$pgfw_header_logo       = array_key_exists( 'header_image', $pgfw_header_settings ) ? $pgfw_header_settings['header_image'] : '';
		$pgfw_header_tagline    = array_key_exists( 'header_tagline', $pgfw_header_settings ) ? $pgfw_header_settings['header_tagline'] : '';
		$pgfw_header_color      = array_key_exists( 'header_color', $pgfw_header_settings ) ? $pgfw_header_settings['header_color'] : '';
		$pgfw_header_width      = array_key_exists( 'header_width', $pgfw_header_settings ) ? $pgfw_header_settings['header_width'] : '';
		$pgfw_header_font_style = array_key_exists( 'header_font_style', $pgfw_header_settings ) ? $pgfw_header_settings['header_font_style'] : '';
		$pgfw_header_font_size  = array_key_exists( 'header_font_size', $pgfw_header_settings ) ? $pgfw_header_settings['header_font_size'] : '';

		$pgfw_settings_header_fields_html_arr = array(
			array(
				'title'       => __( 'Choose logo', 'pdf-generator-for-wordpress' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Image', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_header_image_upload',
				'id'          => 'pgfw_header_image_upload',
				'img-tag'     => array(
					'img-class' => 'pgfw_header_image',
					'img-id'    => 'pgfw_header_image',
					'img-style' => ( $pgfw_header_logo ) ? 'margin:10px;' : 'display:none;margin:10px;',
					'img-src'   => $pgfw_header_logo,
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_header_image_remove',
					'btn-id'    => 'pgfw_header_image_remove',
					'btn-text'  => __( 'Remove image', 'pdf-generator-for-woocommerce' ),
					'btn-title' => __( 'Remove image', 'pdf-generator-for-woocommerce' ),
					'btn-name'  => 'pgfw_header_image_remove',
					'btn-style' => ! ( $pgfw_header_logo ) ? 'display:none' : '',
				),
			),
			array(
				'title'       => __( 'Tagline', 'pdf-generator-for-wordpress' ),
				'type'        => 'textarea',
				'class'       => 'pgfw_header_tagline',
				'id'          => 'pgfw_header_tagline',
				'name'        => 'pgfw_header_tagline',
				'description' => __( 'Enter the tagline to show in header' ),
				'placeholder' => __( 'tagline', 'pdf-generator-for-wordpress' ),
				'value'       => $pgfw_header_tagline,
			),
			array(
				'title'       => __( 'Choose color', 'pdf-generator-for-wordpress' ),
				'type'        => 'color',
				'description' => __( 'Please choose color to display in the header', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_header_color',
				'value'       => $pgfw_header_color,
				'class'       => 'pgfw_color_picker pgfw_header_color',
				'name'        => 'pgfw_header_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wordpress' ),
			),
			array(
				'title'       => __( 'Choose width', 'pdf-generator-for-wordpress' ),
				'type'        => 'number',
				'description' => __( 'Please choose width to display in the header accepted values are in px, please enter number only', 'pdf-generator-for-wordpress' ),
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
					'Helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'Courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'Times-Roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
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
		$pgfw_footer_settings   = get_option( 'mwb_pgfw_footer_settings', array() );
		$pgfw_footer_image      = array_key_exists( 'footer_image', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_image'] : '';
		$pgfw_footer_tagline    = array_key_exists( 'footer_tagline', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_tagline'] : '';
		$pgfw_footer_color      = array_key_exists( 'footer_color', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_color'] : '';
		$pgfw_footer_width      = array_key_exists( 'footer_width', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_width'] : '';
		$pgfw_footer_font_style = array_key_exists( 'footer_font_style', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_font_style'] : '';
		$pgfw_footer_font_size  = array_key_exists( 'footer_font_size', $pgfw_footer_settings ) ? $pgfw_footer_settings['footer_font_size'] : '';

		$pgfw_settings_footer_fields_html_arr = array(
			array(
				'title'       => __( 'Choose logo', 'pdf-generator-for-wordpress' ),
				'type'        => 'upload-button',
				'button_text' => __( 'Upload Image', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_footer_image_upload',
				'id'          => 'pgfw_footer_image_upload',
				'img-tag'     => array(
					'img-class' => 'pgfw_footer_image',
					'img-id'    => 'pgfw_footer_image',
					'img-style' => ( $pgfw_footer_image ) ? 'margin:10px;' : 'display:none;margin:10px;',
					'img-src'   => $pgfw_footer_image,
				),
				'img-remove'  => array(
					'btn-class' => 'pgfw_footer_image_remove',
					'btn-id'    => 'pgfw_footer_image_remove',
					'btn-text'  => __( 'Remove image', 'pdf-generator-for-woocommerce' ),
					'btn-title' => __( 'Remove image', 'pdf-generator-for-woocommerce' ),
					'btn-name'  => 'pgfw_footer_image_remove',
					'btn-style' => ! ( $pgfw_footer_image ) ? 'display:none' : '',
				),
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
					'Helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'Courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'Times-Roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
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
		$pgfw_body_settings         = get_option( 'mwb_pgfw_body_settings', array() );
		$pgfw_body_title_font_style = array_key_exists( 'body_title_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['body_title_font_style'] : '';
		$pgfw_body_title_font_size  = array_key_exists( 'body_title_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['body_title_font_size'] : '';
		$pgfw_body_title_font_color = array_key_exists( 'body_title_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['body_title_font_color'] : '';
		$pgfw_body_page_size        = array_key_exists( 'body_page_size', $pgfw_body_settings ) ? $pgfw_body_settings['body_page_size'] : '';
		$pgfw_body_page_orientation = array_key_exists( 'body_page_orientation', $pgfw_body_settings ) ? $pgfw_body_settings['body_page_orientation'] : '';
		$pgfw_body_page_font_style  = array_key_exists( 'body_page_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['body_page_font_style'] : '';
		$pgfw_body_page_font_size   = array_key_exists( 'body_page_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['body_page_font_size'] : '';
		$pgfw_body_page_font_color  = array_key_exists( 'body_page_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['body_page_font_color'] : '';
		$pgfw_body_border_size      = array_key_exists( 'body_border_size', $pgfw_body_settings ) ? $pgfw_body_settings['body_border_size'] : '';
		$pgfw_body_border_color     = array_key_exists( 'body_border_color', $pgfw_body_settings ) ? $pgfw_body_settings['body_border_color'] : '';
		$pgfw_body_margin_top       = array_key_exists( 'body_margin_top', $pgfw_body_settings ) ? $pgfw_body_settings['body_margin_top'] : '';
		$pgfw_body_margin_left      = array_key_exists( 'body_margin_left', $pgfw_body_settings ) ? $pgfw_body_settings['body_margin_left'] : '';
		$pgfw_body_margin_right     = array_key_exists( 'body_margin_right', $pgfw_body_settings ) ? $pgfw_body_settings['body_margin_right'] : '';
		$pgfw_body_rtl_support      = array_key_exists( 'body_rtl_support', $pgfw_body_settings ) ? $pgfw_body_settings['body_rtl_support'] : '';
		$pgfw_body_add_watermark    = array_key_exists( 'body_add_watermark', $pgfw_body_settings ) ? $pgfw_body_settings['body_add_watermark'] : '';
		$pgfw_body_watermark_text   = array_key_exists( 'body_watermark_text', $pgfw_body_settings ) ? $pgfw_body_settings['body_watermark_text'] : '';
		$pgfw_body_watermark_color  = array_key_exists( 'body_watermark_color', $pgfw_body_settings ) ? $pgfw_body_settings['body_watermark_color'] : '';
		$pgfw_body_cover_template   = array_key_exists( 'body_cover_template', $pgfw_body_settings ) ? $pgfw_body_settings['body_cover_template'] : '';
		$pgfw_body_page_template    = array_key_exists( 'body_page_template', $pgfw_body_settings ) ? $pgfw_body_settings['body_page_template'] : '';
		$pgfw_body_post_template    = array_key_exists( 'body_post_template', $pgfw_body_settings ) ? $pgfw_body_settings['body_post_template'] : '';

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
					'Helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'Courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'Times-Roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
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
				'title'       => __( 'Page Size', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Please choose page size to generate pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_page_size',
				'value'       => $pgfw_body_page_size,
				'class'       => 'pgfw_body_page_size',
				'name'        => 'pgfw_body_page_size',
				'placeholder' => __( 'page size', 'pdf-generator-for-wordpress' ),
				'options'     => array(
					''   => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'a4' => __( 'A4', 'pdf-generator-for-wordpress' ),
					'a5' => __( 'A5', 'pdf-generator-for-wordpress' ),
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
					''           => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'landscape'  => __( 'Landscape', 'pdf-generator-for-wordpress' ),
					'horizontal' => __( 'Horizontal', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Content Font Style', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'Choose page font to generate pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_page_font_style',
				'value'       => $pgfw_body_page_font_style,
				'class'       => 'pgfw_body_page_font_style',
				'name'        => 'pgfw_body_page_font_style',
				'placeholder' => __( 'page font', 'pdf-generator-for-wordpress' ),
				'options'     => array(
					''            => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'Helvetica'   => __( 'Helvetica', 'pdf-generator-for-wordpress' ),
					'Courier'     => __( 'Courier', 'pdf-generator-for-wordpress' ),
					'sans-serif'  => __( 'Sans Serif', 'pdf-generator-for-wordpress' ),
					'DejaVu Sans' => __( 'DejaVu Sans', 'pdf-generator-for-wordpress' ),
					'Times-Roman' => __( 'Times-Roman', 'pdf-generator-for-wordpress' ),
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
				'title'       => __( 'Choose body text color', 'pdf-generator-for-wordpress' ),
				'type'        => 'color',
				'description' => __( 'Choose color to display in the footer', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_font_color',
				'value'       => $pgfw_body_page_font_color,
				'class'       => 'pgfw_color_picker pgfw_body_font_color',
				'name'        => 'pgfw_body_font_color',
				'placeholder' => __( 'color', 'pdf-generator-for-wordpress' ),
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
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_left',
						'class'       => 'pgfw_body_margin_left',
						'name'        => 'pgfw_body_margin_left',
						'placeholder' => __( 'Left', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_body_margin_left,
					),
					array(
						'type'        => 'number',
						'id'          => 'pgfw_body_margin_right',
						'class'       => 'pgfw_body_margin_right',
						'name'        => 'pgfw_body_margin_right',
						'placeholder' => __( 'Right', 'pgfw-generator-for-wordpress' ),
						'value'       => $pgfw_body_margin_right,
					),
				),
			),
			array(
				'title'       => __( 'RTL support', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'Select this to enable RTL support.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_rtl_support',
				'value'       => $pgfw_body_rtl_support,
				'class'       => 'pgfw_body_rtl_support',
				'name'        => 'pgfw_body_rtl_support',
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
				'title'       => __( 'Cover Page template', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'This will be used as the cover page template.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_page_cover_template',
				'value'       => $pgfw_body_cover_template,
				'class'       => 'pgfw_body_page_cover_template',
				'name'        => 'pgfw_body_page_cover_template',
				'options'     => array(
					''          => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'template1' => __( 'Template1', 'pdf-generator-for-wordpress' ),
					'template2' => __( 'Template2', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'title'       => __( 'Page template', 'pdf-generator-for-wordpress' ),
				'type'        => 'select',
				'description' => __( 'This will be used as the page template.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_body_page_template',
				'value'       => $pgfw_body_page_template,
				'class'       => 'pgfw_body_page_template',
				'name'        => 'pgfw_body_page_template',
				'options'     => array(
					''          => __( 'Select option', 'pdf-generator-for-wordpress' ),
					'template1' => __( 'Template1', 'pdf-generator-for-wordpress' ),
					'template2' => __( 'Template2', 'pdf-generator-for-wordpress' ),
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
					'template2' => __( 'Template2', 'pdf-generator-for-wordpress' ),
				),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_body_save_settings',
				'button_text' => __( 'Save settings', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_body_save_settings',
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
		$pgfw_advanced_settings             = get_option( 'mwb_pgfw_advanced_settings', array() );
		$pgfw_advanced_icon_show            = array_key_exists( 'advanced_pdf_generate_icons_show', $pgfw_advanced_settings ) ? $pgfw_advanced_settings['advanced_pdf_generate_icons_show'] : '';
		$pgfw_advanced_pdf_password_protect = array_key_exists( 'advanced_pdf_password_protect', $pgfw_advanced_settings ) ? $pgfw_advanced_settings['advanced_pdf_password_protect'] : '';

		$post_types = array(
			'page'    => 'page',
			'post'    => 'post',
			'product' => 'product',
		);
		unset( $post_types['attachment'] );
		unset( $post_types['shop-order'] );
		$pgfw_advanced_settings_html_arr = array(
			array(
				'title'       => __( 'Show Icons for Post Type', 'pdf-generator-for-wordpress' ),
				'type'        => 'multiselect',
				'description' => __( 'PDF generate icons will be visible to selected post types.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_advanced_show_post_type_icons',
				'value'       => $pgfw_advanced_icon_show,
				'class'       => 'pgfw-multiselect-class mwb-defaut-multiselect pgfw_advanced_show_post_type_icons',
				'options'     => $post_types,
			),
			array(
				'title'       => __( 'Password Protection', 'pdf-generator-for-wordpress' ),
				'type'        => 'checkbox',
				'description' => __( 'PDFs will be password protected, password will be known to admin.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_advanced_password_protect',
				'value'       => $pgfw_advanced_pdf_password_protect,
				'class'       => 'pgfw_advanced_password_protect',
				'name'        => 'pgfw_advanced_password_protect',
				'placeholder' => __( 'Checkbox Demo', 'pdf-generator-for-wordpress' ),
			),
			array(
				'type'        => 'button',
				'id'          => 'pgfw_advanced_save_settings',
				'button_text' => __( 'Save settings', 'pdf-generator-for-wordpress' ),
				'class'       => 'pgfw_advanced_save_settings',
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
		$pgfw_meta_settings = get_option( 'mwb_pgfw_meta_fields_settings', array() );

		$pgfw_meta_settings_html_arr = array();
		$post_types                  = array( 'post', 'page', 'product' );
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
			$pgfw_show_type_meta_val = array_key_exists( '' . $post_type . '_meta_show', $pgfw_meta_settings ) ? $pgfw_meta_settings[ '' . $post_type . '_meta_show' ] : array();
			$pgfw_show_type_meta_arr = array_key_exists( '' . $post_type . '_meta_arr', $pgfw_meta_settings ) ? $pgfw_meta_settings[ '' . $post_type . '_meta_arr' ] : array();

			$pgfw_meta_settings_html_arr[] =
			array(
				'title'       => __( 'Show meta fields for ', 'pdf-generator-for-wordpress' ) . $post_type,
				'type'        => 'checkbox',
				'description' => __( 'selecting this will show the meta fields on pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_meta_fields_' . $post_type . '_show',
				'value'       => $pgfw_show_type_meta_val,
				'class'       => 'pgfw_meta_fields_' . $post_type . '_show',
				'name'        => 'pgfw_meta_fields_' . $post_type . '_show',
			);
			$pgfw_meta_settings_html_arr[] = array(
				'title'       => __( 'Meta fields in', 'pdf-generator-for-wordpress' ) . $post_type,
				'type'        => 'multiselect',
				'description' => __( 'These meta fields will be shown on pdf.', 'pdf-generator-for-wordpress' ),
				'id'          => 'pgfw_meta_fields_' . $post_type . '_list',
				'value'       => $pgfw_show_type_meta_arr,
				'class'       => 'pgfw-multiselect-class mwb-defaut-multiselect pgfw_meta_fields_' . $post_type . '_list',
				'placeholder' => '',
				'options'     => $post_meta_field,
			);
		}
		$pgfw_meta_settings_html_arr[] = array(
			'type'        => 'button',
			'id'          => 'pgfw_meta_fields_save_settings',
			'button_text' => __( 'Save settings', 'pdf-generator-for-wordpress' ),
			'class'       => 'pgfw_meta_fields_save_settings',
		);
		return $pgfw_meta_settings_html_arr;
	}

}
