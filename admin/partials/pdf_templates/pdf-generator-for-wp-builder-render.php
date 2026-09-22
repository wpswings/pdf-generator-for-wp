<?php
/**
 * Renders a post's PDF HTML from a saved drag & drop builder layout, when one
 * applies. See PDF-BUILDER.md for why this lives in its own file with a
 * uniquely-named function instead of a second file defining `return_ob_html()`.
 *
 * @link       https://wpswings.com/
 * @since      1.6.6
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials/pdf_templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'pgfw_builder_maybe_render' ) ) {

	/**
	 * Render a post's PDF HTML from its post type's saved builder layout.
	 *
	 * @param int $post_id Post ID to render.
	 * @return string|null Rendered HTML, or null when no builder layout applies
	 *                      (caller should fall back to the classic template).
	 */
	function pgfw_builder_maybe_render( $post_id ) {
		$pgfw_builder_settings = get_option( 'pgfw_pdf_builder_settings', array() );
		$pgfw_builder_enable   = array_key_exists( 'pgfw_pdf_builder_enable', $pgfw_builder_settings ) ? $pgfw_builder_settings['pgfw_pdf_builder_enable'] : '';

		if ( 'yes' !== $pgfw_builder_enable ) {
			return null;
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			return null;
		}

		$pgfw_layouts = array_key_exists( 'layouts', $pgfw_builder_settings ) ? $pgfw_builder_settings['layouts'] : array();
		$pgfw_layout  = array_key_exists( $post->post_type, $pgfw_layouts ) ? $pgfw_layouts[ $post->post_type ] : array();
		$pgfw_pages   = pgfw_builder_normalize_pages( $pgfw_layout );

		if ( empty( $pgfw_pages ) ) {
			return null;
		}

		$pgfw_size = function_exists( 'wps_pgfw_get_pdf_page_size_px' ) ? wps_pgfw_get_pdf_page_size_px() : array(
			'width'  => 794,
			'height' => 1123,
		);
		// Shave a couple of px off as a rounding safety margin: dompdf's own
		// pagination has a known quirk where absolutely-positioned content whose
		// container is even fractionally taller than the real printable area gets
		// re-painted onto extra (visually near-empty) trailing pages, so it's
		// safer to be very slightly under the page size than to risk being over.
		$pgfw_width  = max( 10, (int) $pgfw_size['width'] - 2 );
		$pgfw_height = max( 10, (int) $pgfw_size['height'] - 2 );

		$pgfw_bg        = ! empty( $pgfw_layout['background_color'] ) ? sanitize_hex_color( $pgfw_layout['background_color'] ) : '';
		$pgfw_watermark = ! empty( $pgfw_layout['watermark'] ) && is_array( $pgfw_layout['watermark'] ) ? $pgfw_layout['watermark'] : array();

		// Zero out dompdf's default page/body margins - the builder's blocks are
		// positioned in absolute px against the page's own content box, so any
		// leftover default margin both offsets every block and, worse, makes the
		// page-sized canvas div taller than the actual printable area, which
		// causes dompdf to spill the (empty) overflow onto extra blank pages.
		// Everything lives under a single root element (no stray top-level
		// siblings) to keep the HTML->DOMDocument round trip elsewhere in the
		// generation pipeline unambiguous.
		$pgfw_page_count = count( $pgfw_pages );
		$html            = '<div class="pgfw-pdf-builder-doc"><style>@page{margin:0;}html,body{margin:0;padding:0;}</style>';

		foreach ( $pgfw_pages as $pgfw_page_index => $pgfw_page ) {
			$pgfw_blocks = isset( $pgfw_page['blocks'] ) ? $pgfw_page['blocks'] : array();
			$pgfw_break  = ( $pgfw_page_index < $pgfw_page_count - 1 ) ? 'page-break-after:always;' : '';
			$pgfw_bg_css = $pgfw_bg ? ( 'background-color:' . $pgfw_bg . ';' ) : '';

			$html .= sprintf(
				'<div class="pgfw-pdf-builder-canvas" style="position:relative;width:%1$dpx;height:%2$dpx;max-height:%2$dpx;overflow:hidden;page-break-inside:avoid;%3$s%4$s">',
				$pgfw_width,
				$pgfw_height,
				esc_attr( $pgfw_break ),
				esc_attr( $pgfw_bg_css )
			);

			if ( ! empty( $pgfw_watermark['enable'] ) ) {
				$html .= pgfw_builder_render_watermark( $pgfw_watermark, $pgfw_width, $pgfw_height );
			}

			foreach ( $pgfw_blocks as $pgfw_block ) {
				$html .= pgfw_builder_render_block( $pgfw_block, $post );
			}

			$html .= '</div>';
		}

		// NOTE: deliberately no trailing page-break-after marker here. Verified
		// directly against the bundled dompdf (see PDF-BUILDER.md) that a
		// trailing `page-break-after: always` forces a genuine extra blank page
		// even with nothing following it - that was the actual cause of the
		// reported "extra page in the downloaded PDF" bug. Multi-page layouts
		// already get a correct break *between* their own pages above (every
		// page except the last one gets `page-break-after:always`), which is
		// sufficient on its own, as verified against the same dompdf build.
		$html .= '</div>';

		return $html;
	}

	/**
	 * Render a diagonal, semi-transparent text watermark spanning the page.
	 *
	 * @param array $watermark Watermark settings: text, color, opacity, font_size.
	 * @param int   $width     Page width in px.
	 * @param int   $height    Page height in px.
	 * @return string
	 */
	function pgfw_builder_render_watermark( $watermark, $width, $height ) {
		$text      = ! empty( $watermark['text'] ) ? sanitize_text_field( $watermark['text'] ) : '';
		if ( '' === $text ) {
			return '';
		}
		$color     = ! empty( $watermark['color'] ) ? sanitize_hex_color( $watermark['color'] ) : '#999999';
		$opacity   = isset( $watermark['opacity'] ) ? max( 0.05, min( 1, (float) $watermark['opacity'] ) ) : 0.2;
		$font_size = isset( $watermark['font_size'] ) ? max( 10, (int) $watermark['font_size'] ) : 60;

		return sprintf(
			'<div style="position:absolute;left:0;top:0;width:%1$dpx;height:%2$dpx;display:flex;align-items:center;justify-content:center;transform:rotate(-35deg);opacity:%3$s;color:%4$s;font-size:%5$dpx;font-weight:bold;text-align:center;overflow:hidden;">%6$s</div>',
			$width,
			$height,
			esc_attr( $opacity ),
			esc_attr( $color ? $color : '#999999' ),
			$font_size,
			esc_html( $text )
		);
	}

	/**
	 * Normalize a saved layout into a list of pages, each with a `blocks` array.
	 * Accepts both the current `{ pages: [ { blocks: [...] }, ... ] }` shape and
	 * the original single-page `{ blocks: [...] }` shape saved before multi-page
	 * support existed, so older saved layouts keep working unchanged.
	 *
	 * @param array $layout Saved layout for one post type.
	 * @return array List of pages, each `array( 'blocks' => array( ... ) )`.
	 */
	function pgfw_builder_normalize_pages( $layout ) {
		if ( ! is_array( $layout ) ) {
			return array();
		}

		if ( ! empty( $layout['pages'] ) && is_array( $layout['pages'] ) ) {
			return array_values(
				array_filter(
					$layout['pages'],
					function ( $page ) {
						return ! empty( $page['blocks'] );
					}
				)
			);
		}

		if ( ! empty( $layout['blocks'] ) && is_array( $layout['blocks'] ) ) {
			return array( array( 'blocks' => $layout['blocks'] ) );
		}

		return array();
	}

	/**
	 * Render a single builder block as absolutely-positioned HTML.
	 *
	 * @param array   $block Sanitized block definition (see PDF-BUILDER.md).
	 * @param WP_Post $post  Post this PDF is being generated for.
	 * @return string
	 */
	function pgfw_builder_render_block( $block, $post ) {
		$type = isset( $block['type'] ) ? $block['type'] : 'text';
		$x    = isset( $block['x'] ) ? (int) $block['x'] : 0;
		$y    = isset( $block['y'] ) ? (int) $block['y'] : 0;
		$w    = isset( $block['width'] ) ? (int) $block['width'] : 100;
		$h    = isset( $block['height'] ) ? (int) $block['height'] : 30;

		$bg     = ! empty( $block['background_color'] ) ? sanitize_hex_color( $block['background_color'] ) : '';
		$bw     = isset( $block['border_width'] ) ? (int) $block['border_width'] : 0;
		$bcolor = ! empty( $block['border_color'] ) ? sanitize_hex_color( $block['border_color'] ) : '#000000';
		$radius = isset( $block['border_radius'] ) ? max( 0, (int) $block['border_radius'] ) : 0;

		$box_style = sprintf( 'position:absolute;left:%dpx;top:%dpx;width:%dpx;height:%dpx;box-sizing:border-box;overflow:hidden;', $x, $y, $w, $h );
		if ( $bg ) {
			$box_style .= 'background-color:' . $bg . ';';
		}
		if ( $bw > 0 ) {
			$box_style .= sprintf( 'border:%dpx solid %s;', $bw, $bcolor ? $bcolor : '#000000' );
		}
		if ( $radius > 0 ) {
			$box_style .= sprintf( 'border-radius:%dpx;', $radius );
		}

		if ( 'rectangle' === $type ) {
			return sprintf( '<div style="%s"></div>', esc_attr( $box_style ) );
		}

		if ( 'image' === $type ) {
			$src = pgfw_builder_resolve_image_source( $block, $post );
			if ( '' === $src ) {
				return $bg || $bw ? sprintf( '<div style="%s"></div>', esc_attr( $box_style ) ) : '';
			}
			return sprintf(
				'<div style="%1$s"><img src="%2$s" style="width:100%%;height:100%%;object-fit:cover;" /></div>',
				esc_attr( $box_style ),
				esc_url( $src )
			);
		}

		$font_size = isset( $block['font_size'] ) ? (int) $block['font_size'] : 14;
		$color     = ! empty( $block['color'] ) ? sanitize_hex_color( $block['color'] ) : '#000000';
		$align     = in_array( ( isset( $block['align'] ) ? $block['align'] : '' ), array( 'left', 'center', 'right' ), true ) ? $block['align'] : 'left';
		$weight    = ! empty( $block['bold'] ) ? 'bold' : 'normal';
		$style_val = ! empty( $block['italic'] ) ? 'italic' : 'normal';

		$text_style = $box_style . sprintf(
			'font-size:%dpx;color:%s;text-align:%s;font-weight:%s;font-style:%s;',
			$font_size,
			esc_attr( $color ? $color : '#000000' ),
			esc_attr( $align ),
			$weight,
			$style_val
		);

		$label = ! empty( $block['label'] ) ? esc_html( $block['label'] ) : '';

		if ( 'meta' === $type ) {
			$meta_key = isset( $block['meta_key'] ) ? $block['meta_key'] : '';
			$value    = $meta_key ? get_post_meta( $post->ID, $meta_key, true ) : '';
			if ( is_array( $value ) || is_object( $value ) ) {
				$value = '';
			}
			if ( '' === $value && '' === $label && ! $bg && ! $bw ) {
				return '';
			}
			return sprintf( '<div style="%1$s">%2$s%3$s</div>', esc_attr( $text_style ), $label, esc_html( (string) $value ) );
		}

		// Text block.
		$value = pgfw_builder_resolve_text_source( $block, $post );
		if ( '' === $value && ! $bg && ! $bw ) {
			return '';
		}
		return sprintf( '<div style="%1$s">%2$s%3$s</div>', esc_attr( $text_style ), $label, $value );
	}

	/**
	 * Resolve a text block's display value based on its `source`.
	 *
	 * @param array   $block Block definition.
	 * @param WP_Post $post  Post this PDF is being generated for.
	 * @return string HTML-safe string ready to echo.
	 */
	function pgfw_builder_resolve_text_source( $block, $post ) {
		$source = isset( $block['source'] ) ? $block['source'] : 'static';

		switch ( $source ) {
			case 'post_title':
				return esc_html( get_the_title( $post ) );
			case 'post_date':
				return esc_html( get_the_date( '', $post ) );
			case 'post_author':
				return esc_html( get_the_author_meta( 'display_name', $post->post_author ) );
			case 'post_excerpt':
				return wp_kses_post( apply_filters( 'the_excerpt', get_the_excerpt( $post ) ) );
			case 'post_content':
				return wp_kses_post( apply_filters( 'the_content', $post->post_content ) );
			case 'static':
			default:
				return wp_kses_post( isset( $block['content'] ) ? $block['content'] : '' );
		}
	}

	/**
	 * Resolve an image block's URL based on its `source`.
	 *
	 * @param array   $block Block definition.
	 * @param WP_Post $post  Post this PDF is being generated for.
	 * @return string Image URL, or '' when unavailable.
	 */
	function pgfw_builder_resolve_image_source( $block, $post ) {
		$source = isset( $block['source'] ) ? $block['source'] : 'static';

		if ( 'featured_image' === $source ) {
			$url = get_the_post_thumbnail_url( $post, 'large' );
			return $url ? $url : '';
		}

		return isset( $block['content'] ) ? esc_url_raw( $block['content'] ) : '';
	}
}
