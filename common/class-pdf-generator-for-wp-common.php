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
use Automattic\WooCommerce\Utilities\OrderUtil;
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
				'email_submit_error' => '<span style="color:#8e4b86;">' . esc_html__( 'Some unexpected error occurred. Kindly Resubmit again', 'pdf-generator-for-wp' ) . '</span>',
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
		// footer .
		$pgfw_footer_settings   = get_option( 'pgfw_footer_setting_submit', array() );
		$pgfw_general_pdf_show_pageno = array_key_exists( 'pgfw_general_pdf_show_pageno', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_general_pdf_show_pageno'] : '';
		$pgfw_pageno_position_left    = array_key_exists( 'pgfw_pageno_position_left', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_pageno_position_left'] : '';
		$pgfw_pageno_position_top    = array_key_exists( 'pgfw_pageno_position_top', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_pageno_position_top'] : '';

		// end footer .
		$body_settings_arr       = get_option( 'pgfw_body_save_settings', array() );
		$pgfw_body_custom_page_size_height        = array_key_exists( 'pgfw_body_custom_page_size_height', $body_settings_arr ) ? $body_settings_arr['pgfw_body_custom_page_size_height'] : '';
		$pgfw_body_custom_page_size_width        = array_key_exists( 'pgfw_body_custom_page_size_width', $body_settings_arr ) ? $body_settings_arr['pgfw_body_custom_page_size_width'] : '';
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
			$document_name = ( $post ) ? strip_tags( $post->post_title ) : 'document';
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
		if ( 'custom_page' == $body_page_size && ! empty( $pgfw_body_custom_page_size_width ) && ! empty( $pgfw_body_custom_page_size_height ) ) {
			$paper_size = array( 0, 0, $pgfw_body_custom_page_size_width * 2.834, $pgfw_body_custom_page_size_height * 2.834 );
		} else {
			$paper_size = array_key_exists( $body_page_size, $paper_sizes ) ? $paper_sizes[ $body_page_size ] : 'a4';
		}
		$options = new Options();
		$options->set( 'isRemoteEnabled', true );
		$dompdf = new Dompdf( $options );

		$contxt = stream_context_create(
			array(
				'http' => array(
					'header'     => "Content-type: application/x-www-form-urlencoded\r\n",
					'method'     => 'GET',
					'user_agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
				),
				'ssl' => array(
					'verify_peer'       => false,
					'verify_peer_name'  => false,
					'allow_self_signed' => true,
				),
			)
		);

		$dompdf->setHttpContext( $contxt );

		$dompdf->loadHtml( $html, 'UTF-8' );
		$dompdf->set_option( 'isRemoteEnabled', true );

		/* addedcode end */

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
			$x               = ( ( $w - $textwidth ) / 2 );
			$y               = ( ( $h - $textheight ) / 2 );
			$hex             = $body_watermark_color;
			list($r, $g, $b) = sscanf( $hex, '#%02x%02x%02x' );

		}

		if ( 'yes' == $pgfw_general_pdf_show_pageno ) {
			$canvas       = $dompdf->getCanvas();
			if ( ! empty( $pgfw_pageno_position_left ) && ! empty( $pgfw_pageno_position_top ) ) {
				$canvas->page_text( $pgfw_pageno_position_left, $pgfw_pageno_position_top, '{PAGE_NUM}/{PAGE_COUNT}', $font, 8, array( 0, 0, 0 ) );
			} else {
				$canvas->page_text( 100, 100, '{PAGE_NUM}/{PAGE_COUNT}', $font, 8, array( 0, 0, 0 ) );
			}
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
					@ob_end_clean(); // phpcs:ignore
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
			@ob_end_clean(); // phpcs:ignore
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
	/**
	 * Export pdf in bulk.
	 */
	public function pgfw_aspose_pdf_exporter_bulk_action() {
		require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		$general_settings_arr = get_option( 'pgfw_general_settings_save', array() );
		$pgfw_generate_mode   = array_key_exists( 'pgfw_general_pdf_generate_mode', $general_settings_arr ) ? $general_settings_arr['pgfw_general_pdf_generate_mode'] : 'download_locally';

		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['path'] . '/';

		$html_file = $upload_path . 'outpuut.html';
		$pdf_file = $upload_path . 'outpuut.pdf';

		@unlink( $html_file );
		@unlink( $pdf_file );

		global $typenow;
		$post_type = $typenow;

		// get the action.
		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc.
		$action = $wp_list_table->current_action();

		$allowed_actions = array( 'export' );

		if ( ! in_array( $action, $allowed_actions ) ) {
			return;
		}

		// security check.
		check_admin_referer( 'bulk-posts' );

		// make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'.
		if ( isset( $_REQUEST['post'] ) ) {
			$post_ids = array_map( 'intval', $_REQUEST['post'] );
		}

		if ( empty( $post_ids ) ) {
			return;
		}

		// this is based on wp-admin/edit.php.
		$sendback = remove_query_arg( array( 'exported', 'untrashed', 'deleted', 'ids' ), wp_get_referer() );

		if ( ! $sendback ) {
			$sendback = admin_url( "edit.php?post_type=$post_type" );
		}

		$pagenum = $wp_list_table->get_pagenum();

		$sendback = add_query_arg( 'paged', $pagenum, $sendback );

		switch ( $action ) {
			case 'export':
				$exported = count( $post_ids );
				$file_name = '';
				if ( is_array( $post_ids ) && count( $post_ids ) > 0 ) {
					$file_name1 = $this->pgfw_aspose_pdf_exporter_array_to_html( $post_ids );
					$template_name = apply_filters( 'wps_pgfw_product_post_ids_in_pdf_filter_hook', $file_name1, $post_ids );
					if ( 'template1' == $template_name ) {
						$file_name .= apply_filters( 'wps_pgfw_add_cover_page_template_to_bulk_pdf', $html );
						$file_name .= $this->pgfw_aspose_pdf_exporter_array_to_html( $post_ids );
					} else {
						$file_name .= apply_filters( 'wps_pgfw_add_cover_page_template_to_bulk_pdf', $html );
						$file_name .= apply_filters( 'wps_pgfw_product_post_ids_in_pdf_filter_hook', $file_name1, $post_ids );
					}
					header( 'Content-Type: application/pdf' );
					$options = new Options();
					$options->set( 'isRemoteEnabled', true );
					$dompdf = new Dompdf( $options );
					$contxt = stream_context_create(
						array(
							'http' => array(
								'header'     => "Content-type: application/x-www-form-urlencoded\r\n",
								'method'     => 'GET',
								'user_agent' => 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)',
							),
							'ssl' => array(
								'verify_peer'       => false,
								'verify_peer_name'  => false,
								'allow_self_signed' => true,
							),
						)
					);
					$dompdf->setHttpContext( $contxt );
					$dompdf->loadHtml( $file_name );
					$dompdf->set_option( 'isRemoteEnabled', true );

					/* addedcode end */
					$document_name = 'bulk_post_to_pdf_' . strtotime( gmdate( 'y-m-d H:i:s' ) );
					@ob_end_clean(); // phpcs:ignore.
					$dompdf->render();
					if ( 'download_locally' === $pgfw_generate_mode ) {
						@ob_end_clean(); // phpcs:ignore.
						$dompdf->stream(
							$document_name . '.pdf',
							array(
								'compress'   => 0,
								'Attachment' => 1,
							)
						);
					} elseif ( 'open_window' === $pgfw_generate_mode ) {

								@ob_end_clean(); // phpcs:ignore
						$dompdf->stream(
							$document_name . '.pdf',
							array(
								'compress'   => 0,
								'Attachment' => 0,
							)
						);
					}
				}
		}

	}
	/**
	 * Bulk export button for posts.
	 *
	 * @param string $bulk_actions $bulk_actions.
	 */
	public function wpg_add_custom_bulk_action_post( $bulk_actions ) {
		$bulk_actions['export'] = __( 'Export', 'pdf-generator-for-wp' );
		return $bulk_actions;
	}
	/**
	 * Bulk export button for pages.
	 *
	 * @param string $bulk_actions $bulk_actions.
	 */
	public function wpg_add_custom_bulk_actions_page( $bulk_actions ) {
		$bulk_actions['export'] = __( 'Export', 'pdf-generator-for-wp' );
		return $bulk_actions;
	}
	/**
	 * Bulk export button for products.
	 *
	 * @param string $bulk_actions $bulk_actions.
	 */
	public function wpg_add_custom_bulk_actionss_product( $bulk_actions ) {
		$bulk_actions['export'] = __( 'Export', 'pdf-generator-for-wp' );
		return $bulk_actions;
	}
	/**
	 * Getting template data for post ids.
	 *
	 * @param array $post_ids postids.
	 */
	public function pgfw_aspose_pdf_exporter_array_to_html( $post_ids ) {

		$ids = $post_ids;
		$body_settings_arr       = get_option( 'pgfw_body_save_settings', array() );
		$pgfw_body_page_template = array_key_exists( 'pgfw_body_page_template', $body_settings_arr ) ? $body_settings_arr['pgfw_body_page_template'] : 'template1';
		$pgfw_body_post_template = array_key_exists( 'pgfw_body_post_template', $body_settings_arr ) ? $body_settings_arr['pgfw_body_post_template'] : 'template1';
		$template_file_name = PDF_GENERATOR_FOR_WP_DIR_PATH . 'common/templates/bulk-pdf-template.php';
		require_once $template_file_name;
		return bulk_pdf_exporter_html( $ids );

	}
	// invoice .

	/**
	 * Reset invoice number.
	 *
	 * @since 1.0.1
	 * @return void
	 */
	public function wpg_reset_invoice_number() {
		$month = get_option( 'wpg_invoice_number_renew_month' );
		$date  = get_option( 'wpg_invoice_number_renew_date' );
		if ( '' !== $month && 'never' !== $month ) {
			if ( ( (int) current_time( 'm' ) === (int) $month ) && ( (int) current_time( 'd' ) === (int) $date ) ) {
				update_option( 'wpg_current_invoice_id', 0 );
			}
		}
	}
	/**
	 * Generate Invoice Number.
	 *
	 * @param int $order_id order ID to generate invoice number for.
	 * @since 1.0.0
	 * @return string
	 */
	public function wpg_invoice_number( $order_id ) {
		$digit  = get_option( 'wpg_invoice_number_digit' );
		$prefix = get_option( 'wpg_invoice_number_prefix' );
		$suffix = get_option( 'wpg_invoice_number_suffix' );
		$digit  = ( $digit ) ? $digit : 4;
		$order = wc_get_order( $order_id );
		// get_post_meta.
		if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
			// HPOS usage is enabled.
			$in_id = $order->get_meta( 'wpg_order_invoice_id', true );
		} else {
			$in_id  = get_post_meta( $order_id, 'wpg_order_invoice_id', true );
		}
		if ( $in_id ) {
			$invoice_id = $in_id;
		} else {
			$prev_invoice_id = get_option( 'wpg_current_invoice_id' );
			if ( $prev_invoice_id ) {
				$curr_invoice_id = $prev_invoice_id + 1;
			} else {
				$curr_invoice_id = 1;
			}
			update_option( 'wpg_current_invoice_id', $curr_invoice_id );
			$invoice_number = str_pad( $curr_invoice_id, $digit, '0', STR_PAD_LEFT );
			$invoice_id     = $prefix . $invoice_number . $suffix;

			// update_post_meta.
			if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
				// HPOS usage is enabled.
				$order->update_meta_data( 'wpg_order_invoice_id', $invoice_id );
				$order->save();

			} else {
				update_post_meta( $order_id, 'wpg_order_invoice_id', $invoice_id );
			}
		}
		return $invoice_id;
	}
	/**
	 * Invoice name for the file to be stored or downloaded.
	 *
	 * @param string $type invoice or packing slip.
	 * @param int    $order_id order id to generate invoice for.
	 * @return string
	 */
	public function wpg_invoice_name_for_file( $type, $order_id ) {
		$invoice_name_option = get_option( 'wps_wpg_invoice_name' );
		$invoice_id          = $this->wpg_invoice_number( $order_id );
		if ( 'invoice' === $type ) {
			if ( 'custom' === $invoice_name_option ) {
				$custom_invoice_name = get_option( 'wps_wpg_custom_invoice_name' );
				$invoice_name        = $custom_invoice_name . '_' . $order_id;
			} elseif ( 'invoice_orderid' === $invoice_name_option ) {
				$invoice_name = 'invoice_' . $order_id;
			} elseif ( 'invoice_id' === $invoice_name_option ) {
				$invoice_name = $invoice_id;
			} else {
				$invoice_name = $type . '_' . $order_id;
			}
		} else {
			$invoice_name = $type . '_' . $order_id;
		}
		return $invoice_name;
	}

	/**
	 * Download file by passing file url.
	 *
	 * @param string $file_url url of the file to download.
	 * @return void
	 */
	public function wpg_download_already_existing_invoice_file( $file_url ) {
		@ob_end_clean(); // phpcs:ignore
		header( 'Content-Type: application/octet-stream' );
		header( 'Content-Transfer-Encoding: Binary' );
		header( 'Content-disposition: attachment; filename="' . basename( $file_url ) . '"' );
		readfile( $file_url ); // phpcs:ignore WordPress
		exit;
	}
	/**
	 * Common method for generating pdf.
	 *
	 * @param int    $order_id order id to print invoice for.
	 * @param string $type type invoice or packing slip.
	 * @param string $action either download locally or on server.
	 * @return string
	 */
	public function wpg_common_generate_pdf( $order_id, $type, $action ) {
		require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		$pgfw_invoice_template            = get_option( 'wpg_invoice_template' );
		$pgfw_generate_invoice_from_cache = get_option( 'wpg_generate_invoice_from_cache' );
		$invoice_id                       = $this->wpg_invoice_number( $order_id );
		$invoice_name                     = $this->wpg_invoice_name_for_file( $type, $order_id );
		$upload_dir                       = wp_upload_dir();
		$upload_basedir                   = $upload_dir['basedir'] . '/invoices/';
		$path                             = $upload_basedir . $invoice_name . '.pdf';
		$file_url                         = $upload_dir['baseurl'] . '/invoices/' . $invoice_name . '.pdf';
		if ( ( 'yes' === $pgfw_generate_invoice_from_cache ) && file_exists( $path ) ) {
			if ( 'download_locally' === $action ) {
				$this->wpg_download_already_existing_invoice_file( $file_url );
			} elseif ( 'download_on_server' === $action ) {
				return $path;
			}
		} else {
			if ( $pgfw_invoice_template ) {
				$template = $pgfw_invoice_template;
			} else {
				$template = 'one';
			}
			if ( 'one' === $template ) {
				$template_path = PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wp-invoice-pdflayout1.php';
			} else if( 'two' === $template ) {
				$template_path = PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wp-invoice-pdflayout2.php';
			}else if( 'three' === $template ) {
				$template_path = PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wp-invoice-pdflayout3.php';
			}
			$template_path = apply_filters( 'wpg_load_template_for_invoice_generation', $template_path );
			require_once $template_path;
			$html   = return_ob_value( $order_id, $type, $invoice_id );

			$dompdf = new Dompdf( array( 'enable_remote' => true ) );
			$dompdf->loadHtml( $html );
			$dompdf->setPaper( 'A4' );
			@ob_end_clean(); // phpcs:ignore
			$dompdf->render();
			if ( ! file_exists( $upload_basedir ) ) {
				wp_mkdir_p( $upload_basedir );
			}

			if ( 'download_locally' === $action ) {
				$output = $dompdf->output();
				if ( file_exists( $path ) ) {
					@unlink( $path ); // phpcs:ignore
				}
				if ( ! file_exists( $path ) ) {
					@file_put_contents( $path, $output ); // phpcs:ignore
				}
				if ( 'invoice' === $type ) {
					do_action( 'mwb_wpg_upload_invoice_in_storage', $path, $file_url, $order_id, $invoice_name );
				}
				$dompdf->stream( $invoice_name . '.pdf', array( 'Attachment' => 1 ) );
			}
			if ( 'open_window' === $action ) {
				$output = $dompdf->output();
				$dompdf->stream( $invoice_name . '.pdf', array( 'Attachment' => 0 ) );
			}
			if ( 'download_on_server' === $action ) {
				$output = $dompdf->output();
				if ( file_exists( $path ) ) {
					@unlink( $path ); // phpcs:ignore
				}
				if ( ! file_exists( $path ) ) {
					@file_put_contents( $path, $output ); // phpcs:ignore
				}
				if ( 'invoice' === $type ) {
					do_action( 'mwb_wpg_upload_invoice_in_storage', $path, $file_url, $order_id, $invoice_name );
				}
				return $path;
			}
		}
	}
	/**
	 * Adding shortcodes for fetching order details.
	 *
	 * @return void
	 */
	public function wpg_fetch_order_details_shortcode() {
		add_shortcode( 'WPG_FETCH_ORDER', array( $this, 'wpg_fetch_order_details' ) );
	}
	/**
	 * Fetching all order details and storing in array.
	 *
	 * @param array $atts attributes which are passed while using shortcode.
	 * @return array
	 */
	public function wpg_fetch_order_details( $atts = array() ) {
		$atts  = shortcode_atts(
			array(
				'order_id' => '',
			),
			$atts
		);
		$order = wc_get_order( $atts['order_id'] );
		if ( is_a( $order, 'WC_Order_Refund' ) ) {
			$order = wc_get_order( $order->get_parent_id() );
		}
		if ( $order ) {
			$order_subtotal     = preg_replace( '/[^0-9,.]/', '', $order->get_subtotal() );
			$decimal_separator  = wc_get_price_decimal_separator();
			$thousand_separator = wc_get_price_thousand_separator();
			$decimals           = wc_get_price_decimals();
			$pgfw_coupon_fee    = array();
			$coupon_fees        = $order->get_fees();
			if ( is_array( $coupon_fees ) ) {
				foreach ( $coupon_fees as $item_fee ) {
					$fee_name                     = $item_fee->get_name();
					$fee_total                    = $item_fee->get_total();
					$pgfw_coupon_fee[ $fee_name ] = $fee_total;
				}
			}
			$order_product_details = array();
			foreach ( $order->get_items() as  $item_key => $item_values ) {
				$_tax                    = new WC_Tax();
				$item_data               = $item_values->get_data();
				$product_tax             = $_tax->get_rates( $item_data['tax_class'] );
				$product_tax             = is_array( $product_tax ) ? array_shift( $product_tax ) : $product_tax;
				$product_tax             = is_array( $product_tax ) ? array_shift( $product_tax ) : $product_tax;
				$product_tax             = ( $product_tax ) ? $product_tax : 0;
				$order_product_details[] = array(
					'product_id'       => get_post_meta( $item_data['product_id'], '_sku', true ),
					'id'               => $item_data['product_id'],
					'item_meta'        => $item_values->get_formatted_meta_data(),
					'product_name'     => $item_data['name'],
					'product_quantity' => $item_data['quantity'],
					'product_price'    => ( 0 !== (int) $item_data['quantity'] ) ? number_format( ( preg_replace( '/,/', '.', $item_data['total'] ) / $item_data['quantity'] ), $decimals, $decimal_separator, $thousand_separator ) : 0,
					'product_tax'      => number_format( preg_replace( '/,/', '.', $item_data['total_tax'] ), $decimals, $decimal_separator, $thousand_separator ),
					'product_total'    => number_format( ( preg_replace( '/,/', '.', $item_data['total'] ) + preg_replace( '/,/', '.', $item_data['total_tax'] ) ), $decimals, $decimal_separator, $thousand_separator ),
					'tax_percent'      => number_format( $product_tax, $decimals, $decimal_separator, $thousand_separator ),
				);
			}
			$shipping_total          = preg_replace( '/[^0-9,.]/', '', $order->get_shipping_total() );
			$shipping_total_format   = ( $shipping_total ) ? number_format( $shipping_total, $decimals, $decimal_separator, $thousand_separator ) : 0;
			$shipping_tax            = preg_replace( '/[^0-9,.]/', '', $order->get_shipping_tax() );
			$shipping_total_with_tax = ( $shipping_total ) ? number_format( ( $shipping_total + $shipping_tax ), $decimals, $decimal_separator, $thousand_separator ) : number_format( $shipping_tax, $decimals, $decimal_separator, $thousand_separator );
			$shipping_details        = array(
				'shipping_first_name'     => $order->get_shipping_first_name(),
				'shipping_last_name'      => $order->get_shipping_last_name(),
				'shipping_address_1'      => $order->get_shipping_address_1(),
				'shipping_address_2'      => $order->get_shipping_address_2(),
				'shipping_city'           => $order->get_shipping_city(),
				'shipping_company'        => $order->get_shipping_company(),
				'shipping_state'          => $order->get_shipping_state(),
				'shipping_postcode'       => $order->get_shipping_postcode(),
				'shipping_country'        => $order->get_shipping_country(),
				'shipping_method'         => $order->get_shipping_method(),
				'shipping_total'          => $shipping_total_format,
				'shipping_tax'            => $shipping_tax,
				'shipping_total_with_tax' => $shipping_total_with_tax,
				'order_status'            => $order->get_status(),
			);
			$cart_total              = preg_replace( '/[^0-9,.]/', '', $order->get_total() );
			$tax_total               = preg_replace( '/[^0-9,.]/', '', $order->get_total_tax() );
			$billing_details         = array(
				'coupon_details'     => $pgfw_coupon_fee,
				'customer_id'        => $order->get_customer_id(),
				'billing_email'      => $order->get_billing_email(),
				'billing_phone'      => $order->get_billing_phone(),
				'billing_first_name' => $order->get_billing_first_name(),
				'billing_last_name'  => $order->get_billing_last_name(),
				'billing_company'    => $order->get_billing_company(),
				'billing_address_1'  => $order->get_billing_address_1(),
				'billing_address_2'  => $order->get_billing_address_2(),
				'billing_city'       => $order->get_billing_city(),
				'billing_state'      => $order->get_billing_state(),
				'billing_postcode'   => $order->get_billing_postcode(),
				'billing_country'    => $order->get_billing_country(),
				'payment_method'     => $order->get_payment_method_title(),
				'order_subtotal'     => number_format( $order_subtotal, $decimals, $decimal_separator, $thousand_separator ),
				'order_currency'     => get_woocommerce_currency_symbol( $order->get_currency() ),
				'cart_total'         => number_format( $cart_total, $decimals, $decimal_separator, $thousand_separator ),
				'tax_totals'         => ( $tax_total ) ? number_format( $tax_total, $decimals, $decimal_separator, $thousand_separator ) : 0,
				'order_created_date' => $order->get_date_created()->format( get_option( 'date_format', 'd-m-Y' ) ),
			);
			$payment = $order->get_checkout_payment_url();
			$order_details_arr       = array(
				'shipping_details' => $shipping_details,
				'billing_details'  => $billing_details,
				'product_details'  => $order_product_details,
				'payment_url' => $payment,
			);
			return wp_json_encode( $order_details_arr );
		}
		return false;
	}

}
