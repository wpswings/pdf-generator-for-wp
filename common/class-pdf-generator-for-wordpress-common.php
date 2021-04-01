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
				'ajaxurl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'pgfw_common_nonce' ),
				'loader'  => PDF_GENERATOR_FOR_WORDPRESS_DIR_URL . 'admin/src/images/loader.gif',
			),
		);
		wp_enqueue_script( $this->plugin_name . 'common' );
	}
	/**
	 * Catching link for pdf generation user.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_generate_pdf_link_catching_user() {
		global $post;
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
		$general_settings_arr = get_option( 'mwb_pgfw_general_settings', array() );
		$pgfw_generate_mode   = array_key_exists( 'pdf_generate_mode', $general_settings_arr ) ? $general_settings_arr['pdf_generate_mode'] : 'download_locally';
		$post                 = get_post( $prod_id );
		$this->pgfw_generate_pdf_from_library( $prod_id, $pgfw_generate_mode );
	}
	/**
	 * Starting session to store the bulk products for generating PDF.
	 *
	 * @return void
	 */
	public function pgfw_start_session_store_bulk_products() {
		if ( ! session_id() || ( session_status() !== PHP_SESSION_ACTIVE ) ) {
			session_start();
		}
	}
	/**
	 * Destroying session at logout.
	 *
	 * @return void
	 */
	public function pgfw_destroy_session_bulk_products() {
		session_unset();
		session_destroy();
	}
	/**
	 * Adding products to bulk products for pdf generation using ajax.
	 *
	 * @return void
	 */
	public function pgfw_bulk_add_products_ajax() {
		check_ajax_referer( 'pgfw_common_nonce', 'nonce' );
		$product_id = array_key_exists( 'product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		! array_key_exists( 'bulk_products', $_SESSION ) ? $_SESSION['bulk_products']              = array() : '';
		! in_array( $product_id, $_SESSION['bulk_products'], true ) ? $_SESSION['bulk_products'][] = $product_id : '';
		echo esc_html( count( $_SESSION['bulk_products'] ) );
		wp_die();
	}
	/**
	 * Html for listing bulk products details.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_build_html_from_session() {
		check_ajax_referer( 'pgfw_common_nonce', 'nonce' );
		?>
		<p>
		<div class="pgfw_bulk_wrapper">
			<ul class="pgfw_bulk_title">
				<li>
					<div><?php esc_html_e( 'PostImage', 'pdf-generator-for-wordpress' ); ?></div>
				</li>
				<li>
					<div><?php esc_html_e( 'PostName', 'pdf-generator-for-wordpress' ); ?></div>
				</li>
				<li>
					<div><?php esc_html_e( 'Action', 'pdf-generator-for-wordpress' ); ?></div>
				</li>
			</ul>
			<?php
			if ( isset( $_SESSION['bulk_products'] ) ) {
				$product_ids = $_SESSION['bulk_products'];
				foreach ( $product_ids as $product_id ) {
					$post          = get_post( $product_id );
					$thumbnail_url = get_the_post_thumbnail_url( $post );
					?>
					<ul class="pgfw_bulk_content">
						<li>
							<div><img style="width:70px;height:70px;"src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php esc_html_e( 'no image found', 'pdf-generator-for-wordpress' ); ?>"></div>
						</li>
						<li>
							<div><?php echo esc_html( $post->post_title ); ?></div>
						</li>
						<li>
							<div><a href="javascript:void(0);" class="pgfw-delete-this-products-bulk" data-product-id="<?php echo esc_html( $product_id ); ?>"><?php esc_html_e( 'delete', 'pdf-generator-for-wordpress' ); ?></a></div>
						</li>
					</ul>
					<?php
				}
			}
			?>
			</div>
			<button id="pgfw-create-zip-bulk"><?php esc_html_e( 'Create Zip', 'pdf-generator-for-wordpress' ); ?></button>
			<button id="pgfw-create-pdf-bulk"><?php esc_html_e( 'Create PDF', 'pdf-generator-for-wordpress' ); ?></button>
		</p>
		<?php
		wp_die();
	}
	/**
	 * Delete products from session on ajax request.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function pgfw_delete_product_from_session() {
		check_ajax_referer( 'pgfw_common_nonce', 'nonce' );
		$product_id = array_key_exists( 'product_id', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
		$key        = array_search( $product_id, $_SESSION['bulk_products'], true );
		if ( false !== $key ) {
			unset( $_SESSION['bulk_products'][ $key ] );
			$_SESSION['values'] = array_values( $_SESSION['bulk_products'] );
		}
		$this->pgfw_build_html_from_session();
	}
	/**
	 * Handling ajax request for creating bulk PDF.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function mwb_pgfw_ajax_for_zip_or_pdf() {
		check_ajax_referer( 'pgfw_common_nonce', 'nonce' );
		$name        = array_key_exists( 'name', $_POST ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$product_ids = isset( $_SESSION['bulk_products'] ) ? $_SESSION['bulk_products'] : array();
		if ( 'pdf_zip' === $name ) {
			$this->zip      = new ZipArchive();
			$upload_dir     = wp_upload_dir();
			$upload_basedir = $upload_dir['basedir'] . '/post_to_pdf/';
			$zip_path       = $upload_basedir . 'document.zip';
			$files          = glob( $upload_basedir . '*' );
			foreach ( $files as $file ) {
				if ( is_file( $file ) ) {
					@unlink( $file ); // phpcs:ignore
				}
			}
			$this->zip->open( $zip_path, ZipArchive::CREATE );
			foreach ( $product_ids as $product_id ) {
				$this->pgfw_generate_pdf_from_library( $product_id, 'upload_on_server' );
			}
			$this->zip->close();
			$upload_dir     = wp_upload_dir();
			$upload_baseurl = $upload_dir['baseurl'] . '/post_to_pdf/';
			$file_url       = $upload_baseurl . 'document.zip';
			echo esc_url( $file_url );
		} else {
			$this->pgfw_generate_pdf_from_library( $_SESSION['bulk_products'], 'upload_on_server', 'continuous_on_same_page' );
			$upload_dir     = wp_upload_dir();
			$upload_baseurl = $upload_dir['baseurl'] . '/post_to_pdf/';
			$file_url       = $upload_baseurl . 'bulk_post_to_pdf.pdf';
			echo esc_url( $file_url );
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
	 * @return void
	 */
	public function pgfw_generate_pdf_from_library( $prod_id, $pgfw_generate_mode, $mode = '' ) {
		require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'package/lib/dompdf/vendor/autoload.php';
		require_once PDF_GENERATOR_FOR_WORDPRESS_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wordpress-admin-temp1.php';
		$general_settings_arr = get_option( 'mwb_pgfw_general_settings', array() );
		$body_settings_arr    = get_option( 'mwb_pgfw_body_settings', array() );
		$pdf_file_name        = array_key_exists( 'pdf_file_name', $general_settings_arr ) ? $general_settings_arr['pdf_file_name'] : 'post_name';
		$body_add_watermark   = array_key_exists( 'body_add_watermark', $body_settings_arr ) ? $body_settings_arr['body_add_watermark'] : '#000000';
		$body_watermark_color = array_key_exists( 'body_watermark_color', $body_settings_arr ) ? $body_settings_arr['body_watermark_color'] : '';
		$body_watermark_text  = array_key_exists( 'body_watermark_text', $body_settings_arr ) ? $body_settings_arr['body_watermark_text'] : '';
		$body_page_size       = array_key_exists( 'body_page_size', $body_settings_arr ) ? $body_settings_arr['body_page_size'] : 'a4';
		$page_orientation     = array_key_exists( 'body_page_orientation', $body_settings_arr ) ? $body_settings_arr['body_page_orientation'] : 'portrait';
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
			$canvas->set_opacity( .2 );
			$x               = ( ( $w - $textwidth ) / 2 );
			$y               = ( ( $h - $textheight ) / 2 );
			$hex             = $body_watermark_color;
			list($r, $g, $b) = sscanf( $hex, '#%02x%02x%02x' );
			$canvas->text( $x, $y, $text, $font, 40, array( $r / 255, $g / 255, $b / 255 ), 0.0, 0.0, -20.0 );
		}
		if ( 'download_locally' === $pgfw_generate_mode ) {
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'Attachment' => 1,
				),
			);
		} elseif ( 'open_window' === $pgfw_generate_mode ) {
			$dompdf->stream(
				$document_name . '.pdf',
				array(
					'compress'   => 0,
					'Attachment' => 0,
				),
			);
		} elseif ( 'upload_on_server' === $pgfw_generate_mode ) {
			$output         = $dompdf->output();
			$upload_dir     = wp_upload_dir();
			$upload_basedir = $upload_dir['basedir'] . '/post_to_pdf/';
			if ( ! file_exists( $upload_basedir ) ) {
				wp_mkdir_p( $upload_basedir );
			}
			if ( 'continuous_on_same_page' === $mode ) {
				$path = $upload_basedir . 'bulk_post_to_pdf.pdf';
				if ( file_exists( $path ) ) {
					@unlink( $path ); // phpcs:ignore
				}
				@file_put_contents( $path, $output ); // phpcs:ignore
			} else {
				$path = $upload_basedir . $document_name . '.pdf';
				if ( ! file_exists( $path ) ) {
					@file_put_contents( $path, $output ); // phpcs:ignore
				}
				$this->zip->addFile( $path, $document_name . '.pdf' );
			}
		}

	}
}
