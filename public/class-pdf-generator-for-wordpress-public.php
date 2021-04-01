<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 * namespace pdf_generator_for_wordpress_public.
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/public
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Pdf_Generator_For_WordPress_Public {

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

		wp_enqueue_style( $this->plugin_name, PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'public/src/scss/pdf-generator-for-wordpress-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_public_enqueue_scripts() {

		wp_register_script( $this->plugin_name . 'public-js', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'public/src/js/pdf-generator-for-wordpress-public.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . 'public-js', 'pgfw_public_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name . 'public-js' );
	}
	/**
	 * Showing pdf generate icons to users.
	 *
	 * @since 1.0.0
	 * @param string $desc string containing paragrapgh of description.
	 * @return void
	 */
	public function pgfw_show_download_icon_to_users( $desc ) {
		$id = get_the_ID();
		global $wp;
		$url_here            = home_url( $wp->request );
		$display_setings_arr = get_option( 'mwb_pgfw_display_settings', array() );
		$user_access_pdf     = array_key_exists( 'user_access', $display_setings_arr ) ? $display_setings_arr['user_access'] : '';
		$guest_access_pdf    = array_key_exists( 'guest_access', $display_setings_arr ) ? $display_setings_arr['guest_access'] : '';
		if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			if ( ! is_cart() && ! is_checkout() && ! is_shop() && ! is_account_page() ) {
				if ( ( 'yes' === $guest_access_pdf ) && ( 'yes' === $user_access_pdf ) ) {
					echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput
					$this->pgfw_download_pdf_button_show( $url_here, $id );
				} elseif ( ( 'yes' === $guest_access_pdf ) && ! is_user_logged_in() ) {
					echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput
					$this->pgfw_download_pdf_button_show( $url_here, $id );
				} elseif ( ( 'yes' === $user_access_pdf ) && is_user_logged_in() ) {
					echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput
					$this->pgfw_download_pdf_button_show( $url_here, $id );
				}
			} else {
				echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput
			}
		} else {
			if ( ( 'yes' === $guest_access_pdf ) && ( 'yes' === $user_access_pdf ) ) {
				echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput
				$this->pgfw_download_pdf_button_show( $url_here, $id );
			} elseif ( ( 'yes' === $guest_access_pdf ) && ! is_user_logged_in() ) {
				echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput
				$this->pgfw_download_pdf_button_show( $url_here, $id );
			} elseif ( ( 'yes' === $user_access_pdf ) && is_user_logged_in() ) {
				echo $desc; // phpcs:ignore WordPress.Security.EscapeOutput
				$this->pgfw_download_pdf_button_show( $url_here, $id );
			}
		}
	}
	/**
	 * Show pdf download button.
	 *
	 * @since 1.0.0
	 * @param string $url_here url till this page.
	 * @param int    $id id of the post.
	 * @return void
	 */
	public function pgfw_download_pdf_button_show( $url_here, $id ) {
		$url_here = add_query_arg(
			array(
				'action' => 'genpdf',
				'id'     => $id,
			),
			$url_here
		);
		?>
		<div style="text-align:center;">
			<div>
				<a href="<?php echo esc_html( $url_here ); ?>" class="pgfw-single-pdf-download-button"><img src="<?php echo esc_url( PDF_GENERATOR_FOR_WORDPRESS_DIR_URL ) . 'admin/src/images/PDF_Tray.svg'; ?>" title="<?php esc_html_e( 'Generate PDF', 'pdf-generator-for-wordpress' ); ?>"></a> | 
				<a href="javascript:void(0)" data-product-id="<?php echo esc_html( $id ); ?>" id="pgfw-bulk-product-add" class="pgfw-single-pdf-download-button"><img src="<?php echo esc_url( PDF_GENERATOR_FOR_WORDPRESS_DIR_URL ) . 'admin/src/images/download_PDF.svg'; ?>" title="<?php esc_html_e( 'Add to Bulk Cart', 'pdf-generator-for-wordpress' ); ?>"></a>
				<?php
				if ( isset( $_SESSION['bulk_products'] ) && count( $_SESSION['bulk_products'] ) > 0 ) {
					require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'public/partials/pdf-generator-for-wordpress-public-display-bulk.php';
				}
				?>
			</div>
		</div>
		<?php
	}

}
