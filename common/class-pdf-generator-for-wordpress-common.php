<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/common
 */

use Dompdf\Dompdf;
/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common stylesheet and JavaScript.
 * namespace pdf_generator_for_wordpress_common.
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/common
 * @author     makewebbetter <webmaster@makewebbetter.com>
 */
class Pdf_Generator_For_WordPress_Common {
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
	 * Register the stylesheets for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_common_enqueue_styles() {
		wp_enqueue_style( $this->plugin_name . 'common', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'common/src/scss/pdf-generator-for-wordpress-common.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_common_enqueue_scripts() {
		wp_register_script( $this->plugin_name . 'common', PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'common/src/js/pdf-generator-for-wordpress-common.js', array( 'jquery' ), $this->version, false );
		wp_localize_script( $this->plugin_name . 'common', 'pgfw_common_param', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		wp_enqueue_script( $this->plugin_name . 'common' );
	}
	/**
	 * Catching link for pdf generation user.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_generate_pdf_link_catching_user() {
		$display_setings_arr = get_option( 'mwb_pgfw_display_settings', array() );
		$user_access_pdf     = array_key_exists( 'user_access', $display_setings_arr ) ? $display_setings_arr['user_access'] : '';
		$guest_access_pdf    = array_key_exists( 'guest_access', $display_setings_arr ) ? $display_setings_arr['guest_access'] : '';
		if ( isset( $_GET['action'] ) ) { // phpcs:ignore
			$prod_id = array_key_exists( 'id', $_GET ) ? sanitize_text_field( wp_unslash( $_GET['id'] ) ) : ''; // phpcs:ignore
			if ( ( 'genpdf' === $_GET['action'] ) && ( $prod_id ) ) { // phpcs:ignore
				if ( ( 'yes' === $guest_access_pdf ) && ( 'yes' === $user_access_pdf ) ) {
					$this->pgfw_generate_pdf( $prod_id );
				} elseif ( ( 'yes' === $guest_access_pdf ) && ! is_user_logged_in() ) {
					$this->pgfw_generate_pdf( $prod_id );
				} elseif ( ( 'yes' === $user_access_pdf ) && is_user_logged_in() ) {
					$this->pgfw_generate_pdf( $prod_id );
				}
			}
		}
	}
	/**
	 * This will generate pdf from the inputted html and data.
	 *
	 * @param int $prod_id post id.
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function pgfw_generate_pdf( $prod_id ) {
		require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wordpress-admin-temp1.php';
		$general_settings_arr = get_option( 'mwb_pgfw_general_settings', array() );
		$pgfw_generate_mode   = array_key_exists( 'pdf_generate_mode', $general_settings_arr ) ? $general_settings_arr['pdf_generate_mode'] : 'download_locally';
		$pdf_file_name        = array_key_exists( 'pdf_file_name', $general_settings_arr ) ? $general_settings_arr['pdf_file_name'] : 'post_name';
		$document_name        = '';
		$post                 = get_post( $prod_id );
		if ( 'custom' === $pdf_file_name ) {
			$pdf_file_name_custom = array_key_exists( 'pdf_file_name_custom', $general_settings_arr ) ? $general_settings_arr['pdf_file_name_custom'] : '';
			$document_name        = ( ( '' !== $pdf_file_name_custom ) && ( $post ) ) ? $pdf_file_name_custom . '_' . $post->ID : 'document';
		} elseif ( 'post_name' === $pdf_file_name ) {
			$document_name = ( $post ) ? $post->post_title : 'document';
		} else {
			$document_name = ( $post ) ? 'document_' . $post->ID : 'document';
		}
		$html   = return_ob_html( $prod_id );
		$dompdf = new Dompdf( array( 'enable_remote' => true ) );
		$dompdf->loadHtml( $html );
		$dompdf->setPaper( 'A4', '' );
		@ob_end_clean(); // phpcs:ignore
		$dompdf->render();
		if ( 'download_locally' === $pgfw_generate_mode ) {
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'Attachment' => 1,
				),
			);
		} else {
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'compress'   => 0,
					'Attachment' => 0,
				),
			);
		}
	}
}
