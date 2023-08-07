<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace pdf_generator_for_wp_public.
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/public
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Pdf_Generator_For_Wp_Public {

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
	 * @param      string $plugin_name       The name of the plugin.
	 * @param      string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_public_enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, PDF_GENERATOR_FOR_WP_DIR_URL . 'public/src/scss/pdf-generator-for-wp-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name . 'public-js', PDF_GENERATOR_FOR_WP_DIR_URL . 'public/src/js/pdf-generator-for-wp-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . 'public-js', 'pgfw_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name . 'public-js' );
		add_thickbox();
	}
	/**
	 * Showing pdf generate icons to users.
	 *
	 * @since 1.0.0
	 * @param string $desc string containing paragrapgh of description.
	 * @return string
	 */
	public function pgfw_show_download_icon_to_users( $desc ) {
		$id = get_the_ID();
		global $wp;
		$url_here                     = home_url( $wp->request );
		$display_setings_arr          = get_option( 'pgfw_save_admin_display_settings', array() );
		$user_access_pdf              = array_key_exists( 'pgfw_user_access', $display_setings_arr ) ? $display_setings_arr['pgfw_user_access'] : '';
		$guest_access_pdf             = array_key_exists( 'pgfw_guest_access', $display_setings_arr ) ? $display_setings_arr['pgfw_guest_access'] : '';
		$pgfw_guest_download_or_email = array_key_exists( 'pgfw_guest_download_or_email', $display_setings_arr ) ? $display_setings_arr['pgfw_guest_download_or_email'] : '';
		$pgfw_user_download_or_email  = array_key_exists( 'pgfw_user_download_or_email', $display_setings_arr ) ? $display_setings_arr['pgfw_user_download_or_email'] : '';
		$pgfw_pdf_icon_after          = array_key_exists( 'pgfw_display_pdf_icon_after', $display_setings_arr ) ? $display_setings_arr['pgfw_display_pdf_icon_after'] : '';
		$pgfw_advanced_settings_arr   = get_option( 'pgfw_advanced_save_settings', array() );
		$pgfw_show_icons_to_posts     = array_key_exists( 'pgfw_advanced_show_post_type_icons', $pgfw_advanced_settings_arr ) ? $pgfw_advanced_settings_arr['pgfw_advanced_show_post_type_icons'] : array();
		$temp                         = false;
		if ( is_array( $pgfw_show_icons_to_posts ) && in_array( get_post_type( $id ), $pgfw_show_icons_to_posts, true ) ) {
			if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins', array() ) ), true ) ) {
				if ( ! is_cart() && ! is_checkout() && ! is_shop() && ! is_account_page() ) {
					$temp = true;
				} else {
					return $desc;
				}
			} else {
				$temp = true;
			}
		} else {
			return $desc;
		}
		$return_desc = '';
		if ( $temp ) {
			if ( ( 'after_content' === $pgfw_pdf_icon_after ) || ( '' === $pgfw_pdf_icon_after ) ) {
				$return_desc = $desc;
			}
			if ( ( 'yes' === $guest_access_pdf ) && ! is_user_logged_in() ) {
				if ( 'email' === $pgfw_guest_download_or_email ) {
					$return_desc .= $this->pgfw_modal_for_email_storing_during_pdf_generation( $url_here, $id );
				} else {
					$return_desc .= $this->pgfw_download_pdf_button_show( $url_here, $id );
				}
			} elseif ( ( 'yes' === $user_access_pdf ) && is_user_logged_in() ) {
				if ( 'email' === $pgfw_user_download_or_email ) {
					$return_desc .= $this->pgfw_modal_for_email_storing_during_pdf_generation( $url_here, $id );
				} else {
					$return_desc .= $this->pgfw_download_pdf_button_show( $url_here, $id );
				}
			}
			if ( 'before_content' === $pgfw_pdf_icon_after ) {
				$return_desc .= $desc;
				return $return_desc;
			} else {
				return $return_desc;
			}
		}
	}
	/**
	 * Pdf download button for users if woocommerce is active.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_show_download_icon_to_users_for_woocommerce() {
		$id = get_the_ID();
		global $wp;
		$url_here                     = home_url( $wp->request );
		$pgfw_advanced_settings_arr   = get_option( 'pgfw_advanced_save_settings', array() );
		$pgfw_show_icons_to_posts     = array_key_exists( 'pgfw_advanced_show_post_type_icons', $pgfw_advanced_settings_arr ) ? $pgfw_advanced_settings_arr['pgfw_advanced_show_post_type_icons'] : array();
		$display_setings_arr          = get_option( 'pgfw_save_admin_display_settings', array() );
		$user_access_pdf              = array_key_exists( 'pgfw_user_access', $display_setings_arr ) ? $display_setings_arr['pgfw_user_access'] : '';
		$guest_access_pdf             = array_key_exists( 'pgfw_guest_access', $display_setings_arr ) ? $display_setings_arr['pgfw_guest_access'] : '';
		$pgfw_guest_download_or_email = array_key_exists( 'pgfw_guest_download_or_email', $display_setings_arr ) ? $display_setings_arr['pgfw_guest_download_or_email'] : '';
		$pgfw_user_download_or_email  = array_key_exists( 'pgfw_user_download_or_email', $display_setings_arr ) ? $display_setings_arr['pgfw_user_download_or_email'] : '';
		$allowedposttags = array(
			'div' => array(

				'title' => array(),
				'class' => array(),
				'id' => true,
				'style'            => true,
			),
			'input' => array(
				'type'      => array(),
				'name'      => array(),
				'value'     => array(),
				'checked'   => array(),
				'style'            => true,
			),
			'a' => array(
				'href' => true,
				'title' => true,
				'img' => true,
				'class' => true,
				'style' => array(),
			),
			'img' => array(
				'src' => array(),
				'class' => array(),
				'alt' => array(),
				'title' => array(),
				'style' => array(),
			),
			'input'    => array(
				'type'  => true,
				'class' => true,
				'name'  => true,
				'value' => true,
				'id'    => true,
				'style' => true,
				'checked'   => array(),
			),
			'button' => array(
				'id' => true,
			),

		);

		if ( is_array( $pgfw_show_icons_to_posts ) && in_array( get_post_type( $id ), $pgfw_show_icons_to_posts, true ) ) {
			if ( ( 'yes' === $guest_access_pdf ) && ! is_user_logged_in() ) {
				if ( 'email' === $pgfw_guest_download_or_email ) {
					echo wp_kses( $this->pgfw_modal_for_email_storing_during_pdf_generation( $url_here, $id ), $allowedposttags );
				} else {
					echo wp_kses_post( $this->pgfw_download_pdf_button_show( $url_here, $id ) );
				}
			} elseif ( ( 'yes' === $user_access_pdf ) && is_user_logged_in() ) {
				if ( 'email' === $pgfw_user_download_or_email ) {
					echo wp_kses( $this->pgfw_modal_for_email_storing_during_pdf_generation( $url_here, $id ), $allowedposttags );
				} else {
					echo wp_kses_post( $this->pgfw_download_pdf_button_show( $url_here, $id ) );
				}
			}
		}

	}
	/**
	 * Show pdf download button.
	 *
	 * @since 1.0.0
	 * @param string $url_here url till this page.
	 * @param int    $id id of the post.
	 * @return string
	 */
	public function pgfw_download_pdf_button_show( $url_here, $id ) {
		require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php';
		$url_here = add_query_arg(
			array(
				'action' => 'genpdf',
				'id'     => $id,
			),
			$url_here
		);
		$html     = pgfw_pdf_download_button( $url_here, $id );
		return $html;
	}
	/**
	 * Modal for email during pdf generation.
	 *
	 * @since 1.0.0
	 * @param string $url_here url of this page.
	 * @param int    $id post id to print PDF for.
	 * @return string
	 */
	public function pgfw_modal_for_email_storing_during_pdf_generation( $url_here, $id ) {
		require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php';
		$url_here = add_query_arg(
			array(
				'action' => 'genpdf',
				'id'     => $id,
			),
			$url_here
		);

		$html = pgfw_modal_for_email_template( $url_here, $id );
		return $html;
	}
	/**
	 * Adding shortcode to show create pdf icon anywhere on the page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_shortcode_to_generate_pdf() {
		add_shortcode( 'WORDPRESS_PDF', array( $this, 'pgfw_callback_for_generating_pdf' ) );
	}
	/**
	 * Callback function for shortcode.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	public function pgfw_callback_for_generating_pdf() {
		if ( isset( $_GET['action'] ) && 'genpdf' === $_GET['action'] ) { // phpcs:ignore WordPress.Security.NonceVerification
			return;
		}
		global $wp;
		$post_id                      = get_the_ID();
		$url_here                     = home_url( $wp->request );
		$display_setings_arr          = get_option( 'pgfw_save_admin_display_settings', array() );
		$user_access_pdf              = array_key_exists( 'pgfw_user_access', $display_setings_arr ) ? $display_setings_arr['pgfw_user_access'] : '';
		$guest_access_pdf             = array_key_exists( 'pgfw_guest_access', $display_setings_arr ) ? $display_setings_arr['pgfw_guest_access'] : '';
		$pgfw_guest_download_or_email = array_key_exists( 'pgfw_guest_download_or_email', $display_setings_arr ) ? $display_setings_arr['pgfw_guest_download_or_email'] : '';
		$pgfw_user_download_or_email  = array_key_exists( 'pgfw_user_download_or_email', $display_setings_arr ) ? $display_setings_arr['pgfw_user_download_or_email'] : '';
		$html                         = '';
		if ( 'yes' === $user_access_pdf && is_user_logged_in() ) {
			if ( 'email' === $pgfw_user_download_or_email ) {
				$html = $this->pgfw_modal_for_email_storing_during_pdf_generation( $url_here, $post_id );
			} else {
				$html = $this->pgfw_download_pdf_button_show( $url_here, $post_id );
			}
		} elseif ( 'yes' === $guest_access_pdf && ! is_user_logged_in() ) {
			if ( 'email' === $pgfw_guest_download_or_email ) {
				$html = $this->pgfw_modal_for_email_storing_during_pdf_generation( $url_here, $post_id );
			} else {
				$html = $this->pgfw_download_pdf_button_show( $url_here, $post_id );
			}
		}
		return $html;
	}
}
