<?php
/**
 * The common functionality of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/common
 */

use Dompdf\Dompdf;
use Dompdf\Options;
use Dompdf\FontMetrics;
/**
 * The common functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the common stylesheet and JavaScript.
 * namespace pdf_generator_for_wp_common.
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/common
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Pdf_Generator_For_Wp_Common {
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
	 * @since 1.0.0
	 * @var   string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since  1.0.0
	 * @param  string $plugin_name       The name of the plugin.
	 * @param  string $version    The version of this plugin.
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
		wp_enqueue_style( $this->plugin_name . 'common', PDF_GENERATOR_FOR_WP_DIR_URL . 'common/src/scss/pdf-generator-for-wp-common.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the common side of the site.
	 *
	 * @since    1.0.0
	 */
	public function pgfw_common_enqueue_scripts() {
		wp_register_script( $this->plugin_name . 'common', PDF_GENERATOR_FOR_WP_DIR_URL . 'common/src/js/pdf-generator-for-wp-common.js', array( 'jquery' ), $this->version, false );
		wp_localize_script(
			$this->plugin_name . 'common',
			'pgfw_common_param',
			array(
				'ajaxurl'            => admin_url( 'admin-ajax.php' ),
				'nonce'              => wp_create_nonce( 'pgfw_common_nonce' ),
				'loader'             => PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/loader.gif',
				'processing_html'    => '<span style="color:#1e73be;">' . esc_html__( 'Please wait....', 'pdf-generator-for-wp' ) . '</span>',
				'email_submit_error' => '<span style="color:#8e4b86;">' . esc_html__( 'Some unexpected error occured. Kindly Resubmit again', 'pdf-generator-for-wp' ) . '</span>',
			)
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
	public function wps_pgfw_generate_pdf_single_and_mail() {
		check_ajax_referer( 'pgfw_common_nonce', 'nonce' );
		$email   = array_key_exists( 'email', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
		$post_id = array_key_exists( 'post_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['post_id'] ) ) : '';
		if ( 'use_account_email' === $email ) {
			$current_user = wp_get_current_user();
			$email        = $current_user->user_email;
		}
		if ( ! is_email( $email ) ) {
			$color   = 'color:#8e4b86;';
			$message = __( 'Please Enter Valid Email Address to Receive Attachment.', 'pdf-generator-for-wp' );
			require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'common/templates/pdf-generator-for-wp-common-email-notice-template.php';
		} else {
			$this->pgfw_generate_pdf_from_library( $post_id, 'upload_on_server_and_mail', '', $email );
			$color   = 'color:green;';
			$message = __( 'Email Submitted Successfully.', 'pdf-generator-for-wp' );
			require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'common/templates/pdf-generator-for-wp-common-email-notice-template.php';
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
	 * @param string $template template to use for pdf generation in case of preview.
	 * @param string $template_name template name to preview for.
	 * @return string
	 */
	public function pgfw_generate_pdf_from_library( $prod_id, $pgfw_generate_mode, $mode = '', $email = '', $template = '', $template_name = '' ) {
		require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		$body_settings_arr       = get_option( 'pgfw_body_save_settings', array() );
		$pgfw_body_page_template = array_key_exists( 'pgfw_body_page_template', $body_settings_arr ) ? $body_settings_arr['pgfw_body_page_template'] : 'template1';
		$pgfw_body_post_template = array_key_exists( 'pgfw_body_post_template', $body_settings_arr ) ? $body_settings_arr['pgfw_body_post_template'] : 'template1';
		$post_id                 = is_array( $prod_id ) ? $prod_id[0] : $prod_id;
		if ( 'preview' === $pgfw_generate_mode ) {
			require_once $template;
		} else {
			if ( 'page' === get_post_type( $post_id ) ) {
				$template_file_name = PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wp-admin-' . $pgfw_body_page_template . '.php';
				$template_file_name = apply_filters( 'pgfw_load_templates_for_pdf_html', $template_file_name, $pgfw_body_page_template, $post_id );
				require_once $template_file_name;
			} else {
				$template_file_name = PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wp-admin-' . $pgfw_body_post_template . '.php';
				$template_file_name = apply_filters( 'pgfw_load_templates_for_pdf_html', $template_file_name, $pgfw_body_post_template, $post_id );
				require_once $template_file_name;
			}
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
			$html = apply_filters( 'wps_pgfw_add_cover_page_template_to_bulk_pdf', $html );
			foreach ( $prod_id as $id ) {
				$html .= return_ob_html( $id );
			}
		} else {
			$html  = '';
			$html  = apply_filters( 'wps_pgfw_add_cover_page_template_to_single_pdf', $html );
			$html .= return_ob_html( $prod_id, $template_name );
		}
		$html        = str_replace( '[WORDPRESS_PDF]', '', $html );
		$paper_sizes = array(
			'4a0'                      => array( 0, 0, 4767.87, 6740.79 ),
			'2a0'                      => array( 0, 0, 3370.39, 4767.87 ),
			'a0'                       => array( 0, 0, 2383.94, 3370.39 ),
			'a1'                       => array( 0, 0, 1683.78, 2383.94 ),
			'a2'                       => array( 0, 0, 1190.55, 1683.78 ),
			'a3'                       => array( 0, 0, 841.89, 1190.55 ),
			'a4'                       => array( 0, 0, 595.28, 841.89 ),
			'a5'                       => array( 0, 0, 419.53, 595.28 ),
			'a6'                       => array( 0, 0, 297.64, 419.53 ),
			'b0'                       => array( 0, 0, 2834.65, 4008.19 ),
			'b1'                       => array( 0, 0, 2004.09, 2834.65 ),
			'b2'                       => array( 0, 0, 1417.32, 2004.09 ),
			'b3'                       => array( 0, 0, 1000.63, 1417.32 ),
			'b4'                       => array( 0, 0, 708.66, 1000.63 ),
			'b5'                       => array( 0, 0, 498.90, 708.66 ),
			'b6'                       => array( 0, 0, 354.33, 498.90 ),
			'c0'                       => array( 0, 0, 2599.37, 3676.54 ),
			'c1'                       => array( 0, 0, 1836.85, 2599.37 ),
			'c2'                       => array( 0, 0, 1298.27, 1836.85 ),
			'c3'                       => array( 0, 0, 918.43, 1298.27 ),
			'c4'                       => array( 0, 0, 649.13, 918.43 ),
			'c5'                       => array( 0, 0, 459.21, 649.13 ),
			'c6'                       => array( 0, 0, 323.15, 459.21 ),
			'ra0'                      => array( 0, 0, 2437.80, 3458.27 ),
			'ra1'                      => array( 0, 0, 1729.13, 2437.80 ),
			'ra2'                      => array( 0, 0, 1218.90, 1729.13 ),
			'ra3'                      => array( 0, 0, 864.57, 1218.90 ),
			'ra4'                      => array( 0, 0, 609.45, 864.57 ),
			'sra0'                     => array( 0, 0, 2551.18, 3628.35 ),
			'sra1'                     => array( 0, 0, 1814.17, 2551.18 ),
			'sra2'                     => array( 0, 0, 1275.59, 1814.17 ),
			'sra3'                     => array( 0, 0, 907.09, 1275.59 ),
			'sra4'                     => array( 0, 0, 637.80, 907.09 ),
			'letter'                   => array( 0, 0, 612.00, 792.00 ),
			'legal'                    => array( 0, 0, 612.00, 1008.00 ),
			'ledger'                   => array( 0, 0, 1224.00, 792.00 ),
			'tabloid'                  => array( 0, 0, 792.00, 1224.00 ),
			'executive'                => array( 0, 0, 521.86, 756.00 ),
			'folio'                    => array( 0, 0, 612.00, 936.00 ),
			'commercial #10 envelope'  => array( 0, 0, 684, 297 ),
			'catalog #10 1/2 envelope' => array( 0, 0, 648, 864 ),
			'8.5x11'                   => array( 0, 0, 612.00, 792.00 ),
			'8.5x14'                   => array( 0, 0, 612.00, 1008.0 ),
			'11x17'                    => array( 0, 0, 792.00, 1224.00 ),
		);

		$paper_size = array_key_exists( $body_page_size, $paper_sizes ) ? $paper_sizes[ $body_page_size ] : 'a4';
		$dompdf     = new Dompdf( array( 'enable_remote' => true ) );
		$dompdf->loadHtml( $html );
		$dompdf->setPaper( $paper_size, $page_orientation );
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
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'Attachment' => 1,
				)
			);
		} elseif ( 'open_window' === $pgfw_generate_mode ) {
			$path = $upload_basedir . $document_name . '.pdf';
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'compress'   => 0,
					'Attachment' => 0,
				)
			);
		} elseif ( 'upload_on_server_and_mail' === $pgfw_generate_mode ) {
			$output = $dompdf->output();
			$path   = $upload_basedir . $document_name . '.pdf';
			if ( file_exists( $path ) ) {
				@unlink( $path ); // phpcs:ignore
			}
			@file_put_contents( $path, $output ); // phpcs:ignore
			wp_mail( $email, __( 'document form site', 'pdf-generator-for-wp' ), __( 'Please find these attachment', 'pdf-generator-for-wp' ), '', array( $path ) );
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
		} elseif ( 'preview' === $pgfw_generate_mode ) {
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'compress'   => 0,
					'Attachment' => 0,
				)
			);
			return;
		}
		do_action( 'wps_pgfw_update_pdf_details_indb', $prod_id, $user_name, $email );
	}
	/**
	 * Download button for posters as shortcode callback.
	 *
	 * @since 1.0.0
	 * @param string $atts attributes for shortcodes for downloading posters.
	 * @return string
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
		$_pgfw_poster_uploaded    = array_key_exists( 'sub_pgfw_poster_image_upload', $pgfw_pdf_upload_settings ) ? json_decode( $pgfw_pdf_upload_settings['sub_pgfw_poster_image_upload'], true ) : array();
		$poster_image_url         = get_the_guid( $atts['id'] );
		$doc_type                 = get_post_type( $atts['id'] );
		$html                     = '';
		if ( ! is_array( $_pgfw_poster_uploaded ) || ! in_array( $atts['id'], $_pgfw_poster_uploaded ) ) {
			return;
		}
		if ( ( 'yes' === $pgfw_poster_user_access && is_user_logged_in() ) || ( 'yes' === $pgfw_poster_guest_access && ! is_user_logged_in() ) ) {
			if ( '' !== $poster_image_url && 'attachment' === $doc_type ) {
				require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'common/templates/pdf-generator-for-wp-common-poster-download-template.php';
				$html = pgfw_poster_download_button_for_shortcode( $poster_image_url );
			}
		}
		return $html;
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
