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
use Dompdf\Options;
use Dompdf\FontMetrics;
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
		wp_localize_script(
			$this->plugin_name . 'common',
			'pgfw_common_param',
			array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'pgfw_common_nonce' ),
				'loader'             => PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/images/loader.gif',
				'processing_html'    => '<span style="color:#1e73be;">' . esc_html__( 'Please wait....', 'pdf-generator-for-wordpress' ) . '</span>',
				'email_submit_error' => '<span style="color:#8e4b86;">' . esc_html__( 'Some unexpected error occured. Kindly Resubmit again', 'pdf-generator-for-wordpress' ) . '</span>',
			),
		);
		wp_enqueue_script( $this->plugin_name . 'common' );
		add_thickbox();
	}
	/**
	 * Catching link for pdf generation user.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_generate_pdf_link_catching_user() {
		$display_setings_arr = get_option( 'pgfw_save_admin_display_settings', array() );
		$user_access_pdf     = array_key_exists( 'pgfw_user_access', $display_setings_arr ) ? $display_setings_arr['pgfw_user_access'] : '';
		$guest_access_pdf    = array_key_exists( 'pgfw_guest_access', $display_setings_arr ) ? $display_setings_arr['pgfw_guest_access'] : '';
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
		$general_settings_arr = get_option( 'pgfw_general_settings_save', array() );
		$pgfw_generate_mode   = array_key_exists( 'pgfw_general_pdf_generate_mode', $general_settings_arr ) ? $general_settings_arr['pgfw_general_pdf_generate_mode'] : 'download_locally';
		$this->pgfw_generate_pdf_from_library( $prod_id, $pgfw_generate_mode );
	}
	/**
	 * Email single PDF.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mwb_pgfw_generate_pdf_single_and_mail() {
		check_ajax_referer( 'pgfw_common_nonce', 'nonce' );
		$email   = array_key_exists( 'email', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$post_id = array_key_exists( 'post_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
		if ( 'use_account_email' === $email ) {
			$current_user = wp_get_current_user();
			$email        = $current_user->user_email;
		}
		if ( ! is_email( $email ) ) {
			?>
			<span style="color:#8e4b86;"><?php esc_html_e( 'Please Enter Valid Email Address to Receive Attachment.', 'pdf-generator-for-wordpress' ); ?></span>
			<?php
		} else {
			$this->pgfw_generate_pdf_from_library( $post_id, 'upload_on_server_and_mail', '', $email );
			?>
			<span style="color:green;"><?php esc_html_e( 'Email Submitted Successfully.', 'pdf-generator-for-wordpress' ); ?></span><div><?php esc_html_e( 'Thank You For Submitting Your Email. You Will Receive an Email Containing the PDF as Attachment.', 'pdf-generator-for-wordpress' ); ?></div>
			<?php
		}
		wp_die();
	}
	/**
	 * Generating pdf using dompdf library.
	 *
	 * @since 1.0.0
	 * @param array  $prod_id product id.
	 * @param string $pgfw_generate_mode mode to generate pdf : download_locally, open_window, upload_on_server.
	 * @param string $mode mode to update either zip or continuation.
	 * @param string $email email to send attachment.
	 * @return string
	 */
	public function pgfw_generate_pdf_from_library( $prod_id, $pgfw_generate_mode, $mode = '', $email = '' ) {
		require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		$body_settings_arr       = get_option( 'pgfw_body_save_settings', array() );
		$pgfw_body_page_template = array_key_exists( 'pgfw_body_page_template', $body_settings_arr ) ? $body_settings_arr['pgfw_body_page_template'] : 'template1';
		$pgfw_body_post_template = array_key_exists( 'pgfw_body_post_template', $body_settings_arr ) ? $body_settings_arr['pgfw_body_post_template'] : 'template1';
		$post_id                 = is_array( $prod_id ) ? $prod_id[0] : $prod_id;
		if ( 'page' === get_post_type( $post_id ) ) {
			require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wordpress-admin-' . $pgfw_body_page_template . '.php';
		} else {
			require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wordpress-admin-' . $pgfw_body_post_template . '.php';
		}
		$general_settings_arr = get_option( 'pgfw_general_settings_save', array() );
		$pdf_file_name        = array_key_exists( 'pgfw_general_pdf_file_name', $general_settings_arr ) ? $general_settings_arr['pgfw_general_pdf_file_name'] : 'post_name';
		$body_add_watermark   = array_key_exists( 'pgfw_body_add_watermark', $body_settings_arr ) ? $body_settings_arr['pgfw_body_add_watermark'] : '#000000';
		$body_watermark_color = array_key_exists( 'pgfw_body_watermark_color', $body_settings_arr ) ? $body_settings_arr['pgfw_body_watermark_color'] : '';
		$body_watermark_text  = array_key_exists( 'pgfw_body_watermark_text', $body_settings_arr ) ? $body_settings_arr['pgfw_body_watermark_text'] : '';
		$body_page_size       = array_key_exists( 'pgfw_body_page_size', $body_settings_arr ) ? $body_settings_arr['pgfw_body_page_size'] : 'a4';
		$page_orientation     = array_key_exists( 'pgfw_body_page_orientation', $body_settings_arr ) ? $body_settings_arr['pgfw_body_page_orientation'] : 'portrait';
		$document_name        = '';
		$post                 = get_post( $post_id );
		if ( 'custom' === $pdf_file_name ) {
			$pdf_file_name_custom = array_key_exists( 'pgfw_custom_pdf_file_name', $general_settings_arr ) ? $general_settings_arr['pgfw_custom_pdf_file_name'] : '';
			$document_name        = ( ( '' !== $pdf_file_name_custom ) && ( $post ) ) ? $pdf_file_name_custom . '_' . $post->ID : 'document';
		} elseif ( 'post_name' === $pdf_file_name ) {
			$document_name = ( $post ) ? $post->post_title : 'document';
		} else {
			$document_name = ( $post ) ? 'document_' . $post->ID : 'document';
		}
		if ( 'continuous_on_same_page' === $mode ) {
			$html = '';
			foreach ( $prod_id as $id ) {
				$html .= return_ob_html( $id );
			}
		} else {
			$html = return_ob_html( $prod_id );
		}
		$dompdf = new Dompdf( array( 'enable_remote' => true ) );
		$dompdf->loadHtml( $html );
		$dompdf->setPaper( $body_page_size, $page_orientation );
		@ob_end_clean(); // phpcs:ignore
		$dompdf->render();
		if ( 'yes' === $body_add_watermark ) {
			$options = new Options();
			$options->set( 'isPhpEnabled', 'true' );
			$canvas       = $dompdf->getCanvas();
			$fontmetrices = new FontMetrics( $canvas, $options );
			$w            = $canvas->get_width();
			$h            = $canvas->get_height();
			$font         = $fontmetrices->getFont( 'times' );
			$text         = $body_watermark_text;
			$textheight   = $fontmetrices->getFontHeight( $font, 150 );
			$textwidth    = $fontmetrices->getTextWidth( $text, $font, 40 );
			$canvas->set_opacity( .2, 'Multiply' );
			$x               = ( ( $w - $textwidth ) / 2 );
			$y               = ( ( $h - $textheight ) / 2 );
			$hex             = $body_watermark_color;
			list($r, $g, $b) = sscanf( $hex, '#%02x%02x%02x' );
			$canvas->page_text( $x, $y, $text, $font, 40, array( $r / 255, $g / 255, $b / 255 ), 0.0, 0.0, -20.0 );
			do_action( 'mwb_pgfw_password_protect_action_hook', $canvas );
		}
		$upload_dir     = wp_upload_dir();
		$upload_basedir = $upload_dir['basedir'] . '/post_to_pdf/';
		if ( ! file_exists( $upload_basedir ) ) {
			wp_mkdir_p( $upload_basedir );
		}
		$current_user = wp_get_current_user();
		$user_name    = $current_user->display_name;
		$email        = ( '' !== $email ) ? $email : $current_user->user_email;
		$output       = $dompdf->output();
		if ( 'download_locally' === $pgfw_generate_mode ) {
			$path = $upload_basedir . $document_name . '.pdf';
			if ( ! file_exists( $path ) ) {
				@file_put_contents( $path, $output ); // phpcs:ignore
			}
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'Attachment' => 1,
				),
			);
		} elseif ( 'open_window' === $pgfw_generate_mode ) {
			$path = $upload_basedir . $document_name . '.pdf';
			if ( ! file_exists( $path ) ) {
				@file_put_contents( $path, $output ); // phpcs:ignore
			}
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'compress'   => 0,
					'Attachment' => 0,
				),
			);
		} elseif ( 'upload_on_server_and_mail' === $pgfw_generate_mode ) {
			$output = $dompdf->output();
			$path   = $upload_basedir . $document_name . '.pdf';
			if ( ! file_exists( $path ) ) {
				@file_put_contents( $path, $output ); // phpcs:ignore
			}
			wp_mail( $email, __( 'document form site', 'pdf-generator-for-wordpress' ), __( 'Please find these attachment', 'pdf-generator-for-wordpress' ), '', array( $path ) );
		} elseif ( 'bulk' === $pgfw_generate_mode ) {
			if ( 'continuous_on_same_page' === $mode ) {
				$document_name = 'bulk_post_to_pdf_' . strtotime( gmdate( 'y-m-d H:i:s' ) );
				$path          = $upload_basedir . $document_name . '.pdf';
			} elseif ( 'bulk_zip' === $mode ) {
				$path = $upload_basedir . $document_name . '.pdf';
			}
			if ( ! file_exists( $path ) ) {
				@file_put_contents( $path, $output ); // phpcs:ignore
			}
			return $document_name;
		}
		do_action( 'mwb_pgfw_update_pdf_details_indb', $prod_id, $user_name, $email );
	}
	/**
	 * Download button for posters as shortcode callback.
	 *
	 * @since 1.0.0
	 * @param string $atts attributes for shortcodes for downloading posters.
	 * @return void
	 */
	public function pgfw_download_button_posters( $atts ) {
		$atts = shortcode_atts(
			array(
				'id' => 0,
			),
			$atts
		);

		$pgfw_pdf_upload_settings = get_option( 'pgfw_pdf_upload_save_settings', array() );
		$pgfw_poster_user_access  = array_key_exists( 'pgfw_poster_user_access', $pgfw_pdf_upload_settings ) ? $pgfw_pdf_upload_settings['pgfw_poster_user_access'] : '';
		$pgfw_poster_guest_access = array_key_exists( 'pgfw_poster_guest_access', $pgfw_pdf_upload_settings ) ? $pgfw_pdf_upload_settings['pgfw_poster_guest_access'] : '';
		$poster_image_url         = get_the_guid( $atts['id'] );
		$doc_type                 = get_post_type( $atts['id'] );
		if ( ( 'yes' === $pgfw_poster_user_access && is_user_logged_in() ) || ( 'yes' === $pgfw_poster_guest_access && ! is_user_logged_in() ) ) {
			if ( '' !== $poster_image_url && 'attachment' === $doc_type ) {
				?>
				<a href="<?php echo esc_url( $poster_image_url ); ?>" download><?php esc_html_e( 'Download Poster' ); ?></a>
				<?php
			}
		}
	}
	/**
	 * Shortcode for link generation of poster download.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_poster_download_shortcode() {
		add_shortcode( 'PGFW_DOWNLOAD_POSTER', array( $this, 'pgfw_download_button_posters' ) );
	}
}
