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
		return apply_filters( 'pgfw_pdf_download_button_filter', $html, $id );
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
		add_shortcode( 'WPS_SINGLE_IMAGE', array( $this, 'wps_display_uploaded_image_shortcode' ) );
		add_shortcode( 'WPS_POST_GALLERY', array( $this, 'wps_product_gallery_shortcode' ) );
	}

	/**
	 * Callback function for shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts post id to print PDF for.
	 * @return string
	 */
	public function wps_display_uploaded_image_shortcode( $atts ) {
		// Set default attributes for the shortcode.
		$atts = shortcode_atts(
			array(
				'id'  => $atts['id'],    // Attachment ID.
				'url' => '',    // Image URL if no ID is given.
				'alt' => 'Image', // Alt text for accessibility.
				'width'  => isset( $atts['width'] ) ? $atts['width'] : 10, // Width of the image.
				'height' => isset( $atts['height'] ) ? $atts['height'] : 10,  // Height of the image.
			),
			$atts,
			'wps_image'
		);

		// Get image URL from attachment ID if provided.
		if ( ! empty( $atts['id'] ) ) {
			$image_src = wp_get_attachment_image_url( $atts['id'], 'full' );
		} else {
			$image_src = esc_url( $atts['url'] );
		}
		// Check if image source exists.
		if ( empty( $image_src ) ) {
			return '<p>No image found.</p>';
		}

		// Return the image HTML.
		return '<img src="' . esc_url( $image_src ) . '" alt="' . esc_attr( $atts['alt'] ) . '" style="display:block;width: ' . esc_attr( $atts['width'] ) . '; height: ' . esc_attr( $atts['height'] ) . ';">';
	}



	/**
	 * Callback function for shortcode.
	 *
	 * @since 1.0.0
	 * @param array $atts post id to print PDF for.
	 * @return string
	 */
	public function wps_product_gallery_shortcode( $atts ) {
		// Set default attributes for the shortcode.
		$atts = shortcode_atts(
			array(
				'product_id' => $atts['product_id'],
				'columns'    => 3,
				'size'       => 'thumbnail',
			),
			$atts,
			'wps_product_gallery'
		);

		// Get the product ID (fallback to current product if empty and on a product page).
		$product_id = ! empty( $atts['product_id'] ) ? $atts['product_id'] : get_the_ID();

		if ( ! $product_id ) {
			return '<p>No product found.</p>';
		}

		// Get the gallery images.
		$product = wc_get_product( $atts['product_id'] );

		$gallery_image_ids = $product ? $product->get_gallery_image_ids() : array();

		// Check if gallery images exist.
		if ( empty( $gallery_image_ids ) ) {
			return '<p>No gallery images found for this product.</p>';
		}

		// Start the gallery output.
		$output = '<div class="wps-product-gallery-grid" style="display: grid; grid-template-columns: repeat(' . esc_attr( $atts['columns'] ) . ', 1fr); gap: 10px;">';

		// Loop through gallery images and generate HTML for each.
		foreach ( $gallery_image_ids as $image_id ) {
			$image_url = wp_get_attachment_image_url( $image_id, $atts['size'] );

			// Check if the image URL is valid.
			if ( ! $image_url ) {
				continue;
			}

			$output .= '<div class="wps-gallery-item">';
			$output .= '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ) . '" style="width: 100%; height: auto;">';
			$output .= '</div>';
		}

		$output .= '</div>';

		return $output;
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

	/**
	 * Adding shortcode to show flipbook on the page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function wps_pgfw_flipbook_shortcode_callback() {
		$general_settings_data     = get_option( 'pgfw_general_settings_save', array() );
		$pgfw_enable_plugin        = array_key_exists( 'pgfw_enable_plugin', $general_settings_data ) ? $general_settings_data['pgfw_enable_plugin'] : '';
		$pgfw_flipbook_enable = array_key_exists( 'pgfw_flipbook_enable', $general_settings_data ) ? $general_settings_data['pgfw_flipbook_enable'] : '';
		if ( 'yes' !== $pgfw_enable_plugin || 'yes' !== $pgfw_flipbook_enable ) {
			return;
		}
		add_shortcode( 'flipbook', array( $this, 'wps_pgfw_flipbook_shortcode_html' ) );
	}

	/**
	 * Flipbook shortcode HTML generator.
	 *
	 * @since 1.0.0
	 * @param array $atts Shortcode attributes.
	 * @return string HTML output for the flipbook.
	 */
	public function wps_pgfw_flipbook_shortcode_html( $atts ) {
		$atts = shortcode_atts( array( 'id' => 0 ), $atts );
		$post_id = (int) $atts['id'];

		if ( ! $post_id ) {
			return '<p>No flipbook ID provided.</p>';
		}

		$post     = get_post( $post_id );
		if ( ! $post || 'flipbook' !== $post->post_type || 'publish' !== get_post_status( $post->ID ) ) {
			return '<p>Invalid flipbook ID.</p>';
		}

		$width  = get_post_meta( $post_id, '_fb_width', true ) ? get_post_meta( $post_id, '_fb_width', true ) : 400;
		$height = get_post_meta( $post_id, '_fb_height', true ) ? get_post_meta( $post_id, '_fb_height', true ) : 300;

		$pdf_html = get_post_meta( $post_id, '_fb_pdf_html', true );
		$cover_image = get_post_meta( $post_id, '_fb_cover_image', true );
		$back_image  = get_post_meta( $post_id, '_fb_back_image', true );
		$wps_tool_btn = get_post_meta( $post->ID, '_fb_tool_btn', true );
		$wps_add_cover_page = get_post_meta( $post->ID, '_fb_show_cover', true );
		$flip_sound_url = get_post_meta( $post->ID, '_fb_flip_sound_url', true );
		$flip_sound_volume = get_post_meta( $post->ID, '_fb_flip_sound_volume', true );

		$mobile_scroll_support = get_post_meta( $post->ID, '_fb_mobileScrollSupport', true );
		$max_shadow_opacity    = get_post_meta( $post->ID, '_fb_maxShadowOpacity', true );
		$flipping_time        = get_post_meta( $post->ID, '_fb_flippingTime', true );
		$start_page           = get_post_meta( $post->ID, '_fb_startPage', true );
		$swipe_distance       = get_post_meta( $post->ID, '_fb_swipeDistance', true );
		$use_mouse_events      = get_post_meta( $post->ID, '_fb_useMouseEvents', true );
		$size                = get_post_meta( $post->ID, '_fb_size', true );
		$popup_enabled       = get_post_meta( $post->ID, '_fb_popup_enabled', true );
		$image_urls_json     = get_post_meta( $post->ID, '_fb_image_urls', true );

		$image_urls = array();
		$from_images = false;
		if ( $image_urls_json ) {
			$decoded = json_decode( $image_urls_json, true );
			if ( is_array( $decoded ) && count( $decoded ) > 0 ) {
				$image_urls = array_values( array_map( 'esc_url', $decoded ) );
				$from_images = true;
			}
		}
		if ( ! $from_images ) {
			preg_match_all( '/<img[^>]+src=["\']([^"\']+)["\']/i', $pdf_html, $m );
			$image_urls = $m[1] ?? array();
		}

		if ( 1 === (int) $wps_add_cover_page ) {

			if ( $cover_image ) {
				array_unshift( $image_urls, $cover_image );
			}
			if ( $back_image ) {
				$image_urls[] = $back_image;
			}
		}
		$uid = 'flipbook_' . wp_unique_id();

		ob_start();
		if ( 1 === (int) $popup_enabled ) {
			$modal_id = $uid . '__modal';
			?>
		<button type="button" class="flipbook-open-btn is-icon" data-target="#<?php echo esc_attr( $modal_id ); ?>" aria-haspopup="dialog" aria-controls="<?php echo esc_attr( $modal_id ); ?>" aria-label="Open Flipbook">
			<span class="fb-icon" aria-hidden="true">
				<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
					<path d="M12 6.5c-2.5-1.6-5.5-1.8-8-.8v12.1c2.5-1 5.5-.8 8 .8m0-12.1c2.5-1.6 5.5-1.8 8-.8v12.1c-2.5-1-5.5-.8-8 .8M12 6.5v12.1" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
				</svg>
			</span>
			<span class="sr-only">Open Flipbook</span>
		</button>
		<div class="flipbook-modal" id="<?php echo esc_attr( $modal_id ); ?>" aria-hidden="true" role="dialog" aria-modal="true">
			<div class="flipbook-modal-backdrop" data-close="true"></div>
			<div class="flipbook-modal-dialog" role="document">
				<button type="button" class="flipbook-modal-close" aria-label="Close" data-close="true">×</button>
				<div class="flipbook-wrap" id="<?php echo esc_attr( $uid ); ?>" data-init-on-open="1">
					<?php if ( '1' === $wps_tool_btn ) : ?>
					<div class="flipbook-toolbar">
						<button type="button" class="btn-prev">Prev</button>
						<span>Page <span class="page-current"><?php echo esc_attr( isset( $start_page ) && ! empty( $start_page ) ? $start_page : 1 ); ?></span> of <span class="page-total">-</span></span>
						<button type="button" class="btn-next">Next</button>
						<input type="number" class="page-jump" min="1" value="<?php echo esc_attr( isset( $start_page ) && ! empty( $start_page ) ? $start_page : 1 ); ?>" /> 
						<button type="button" class="btn-go">Go to page</button>
					</div>
					<?php endif; ?>
					<div class="flip-book"
						id="<?php echo esc_attr( $uid ); ?>__book"
						data-images='<?php echo esc_attr( wp_json_encode( $image_urls ) ); ?>'
						data-width="<?php echo (int) $width; ?>"
						data-height="<?php echo (int) $height; ?>"
						data-flip-sound="<?php echo esc_url( $flip_sound_url ); ?>"
						data-flip-sound-volume="<?php echo esc_attr( '' !== $flip_sound_volume ? $flip_sound_volume : 1 ); ?>"
						data-mobile-scroll="<?php echo esc_attr( $mobile_scroll_support ); ?>"
						data-max-shadow-opacity="<?php echo esc_attr( $max_shadow_opacity ); ?>"
						data-flipping-time="<?php echo esc_attr( $flipping_time ); ?>"
						data-start-page="<?php echo esc_attr( $start_page ); ?>"
						data-swipe-distance="<?php echo esc_attr( $swipe_distance ); ?>"
						data-use-mouse-events="<?php echo esc_attr( $use_mouse_events ); ?>"
						data-size="<?php echo esc_attr( $size ); ?>">
					</div>
				</div>
			</div>
		</div>
			<?php
		} else {
			?>
		<div class="flipbook-wrap" id="<?php echo esc_attr( $uid ); ?>">
				<?php if ( '1' === $wps_tool_btn ) : ?>
			<div class="flipbook-toolbar">
				<button type="button" class="btn-prev">Prev</button>
				<span>Page <span class="page-current"><?php echo esc_attr( isset( $start_page ) && ! empty( $start_page ) ? $start_page : 1 ); ?></span> of <span class="page-total">-</span></span>
				<button type="button" class="btn-next">Next</button>
				<input type="number" class="page-jump" min="1" value="<?php echo esc_attr( isset( $start_page ) && ! empty( $start_page ) ? $start_page : 1 ); ?>" /> 
				<button type="button" class="btn-go">Go to page</button>
			</div>
			<?php endif; ?>
			<div class="flip-book"
				id="<?php echo esc_attr( $uid ); ?>__book"
				data-images='<?php echo esc_attr( wp_json_encode( $image_urls ) ); ?>'
				data-width="<?php echo (int) $width; ?>"
				data-height="<?php echo (int) $height; ?>"
				data-flip-sound="<?php echo esc_url( $flip_sound_url ); ?>"
				data-flip-sound-volume="<?php echo esc_attr( '' !== $flip_sound_volume ? $flip_sound_volume : 1 ); ?>"
				data-mobile-scroll="<?php echo esc_attr( $mobile_scroll_support ); ?>"
				data-max-shadow-opacity="<?php echo esc_attr( $max_shadow_opacity ); ?>"
				data-flipping-time="<?php echo esc_attr( $flipping_time ); ?>"
				data-start-page="<?php echo esc_attr( $start_page ); ?>"
				data-swipe-distance="<?php echo esc_attr( $swipe_distance ); ?>"
				data-use-mouse-events="<?php echo esc_attr( $use_mouse_events ); ?>"
				data-size="<?php echo esc_attr( $size ); ?>">
			</div>
		</div>
			<?php
		}
		return ob_get_clean();
	}
}

