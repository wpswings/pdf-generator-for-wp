<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {

	exit(); // Exit if accessed directly.
}
/**
 * Function contains html for template 1;
 *
 * @param int    $post_id post id.
 * @param string $template_name template name to be used.
 * @since 1.0.0
 *
 * @return string
 */
function return_ob_html( $post_id, $template_name = '' ) {
	// advanced settings.
	$pgfw_advanced_settings = get_option( 'pgfw_advanced_save_settings', array() );
	$pgfw_ttf_font_upload   = array_key_exists( 'pgfw_ttf_font_upload', $pgfw_advanced_settings ) ? $pgfw_advanced_settings['pgfw_ttf_font_upload'] : '';

	// header customisation settings.
	$pgfw_header_settings   = get_option( 'pgfw_header_setting_submit', array() );
	$pgfw_header_use_in_pdf = array_key_exists( 'pgfw_header_use_in_pdf', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_use_in_pdf'] : '';
	$pgfw_header_logo       = array_key_exists( 'sub_pgfw_header_image_upload', $pgfw_header_settings ) ? $pgfw_header_settings['sub_pgfw_header_image_upload'] : '';
	$pgfw_header_comp_name  = array_key_exists( 'pgfw_header_company_name', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_company_name'] : '';
	$pgfw_header_tagline    = array_key_exists( 'pgfw_header_tagline', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_tagline'] : '';
	$pgfw_header_color      = array_key_exists( 'pgfw_header_color', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_color'] : '';
	$pgfw_header_width      = array_key_exists( 'pgfw_header_width', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_width'] : '';
	$pgfw_header_font_style = array_key_exists( 'pgfw_header_font_style', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_font_style'] : '';
	$pgfw_header_font_style = ( 'custom' === $pgfw_header_font_style ) ? 'My_font' : $pgfw_header_font_style;
	$pgfw_header_font_size  = array_key_exists( 'pgfw_header_font_size', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_font_size'] : '';
	$pgfw_header_top        = array_key_exists( 'pgfw_header_top', $pgfw_header_settings ) ? $pgfw_header_settings['pgfw_header_top'] : '';
	// body customisation settings.
	$pgfw_body_settings              = get_option( 'pgfw_body_save_settings', array() );
	$pgfw_body_title_font_style      = array_key_exists( 'pgfw_body_title_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_style'] : '';
	$pgfw_body_title_font_style      = ( 'custom' === $pgfw_body_title_font_style ) ? 'My_font' : $pgfw_body_title_font_style;
	$pgfw_body_title_font_size       = array_key_exists( 'pgfw_body_title_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_size'] : '';
	$pgfw_body_title_font_color      = array_key_exists( 'pgfw_body_title_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_title_font_color'] : '';
	$pgfw_body_page_font_style       = array_key_exists( 'pgfw_body_page_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_font_style'] : '';
	$pgfw_body_page_font_style       = ( 'custom' === $pgfw_body_page_font_style ) ? 'My_font' : $pgfw_body_page_font_style;
	$pgfw_body_page_font_size        = array_key_exists( 'pgfw_content_font_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_content_font_size'] : '';
	$pgfw_body_page_font_color       = array_key_exists( 'pgfw_body_font_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_font_color'] : '';
	$pgfw_body_border_size           = array_key_exists( 'pgfw_body_border_size', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_border_size'] : '';
	$pgfw_body_border_color          = array_key_exists( 'pgfw_body_border_color', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_border_color'] : '';
	$pgfw_body_margin_top            = array_key_exists( 'pgfw_body_margin_top', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_top'] : '';
	$pgfw_body_margin_left           = array_key_exists( 'pgfw_body_margin_left', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_left'] : '';
	$pgfw_body_margin_right          = array_key_exists( 'pgfw_body_margin_right', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_right'] : '';
	$pgfw_body_margin_bottom         = array_key_exists( 'pgfw_body_margin_bottom', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_margin_bottom'] : '';
	$pgfw_body_rtl_support           = array_key_exists( 'pgfw_body_rtl_support', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_rtl_support'] : '';
	$pgfw_watermark_image_use_in_pdf = array_key_exists( 'pgfw_watermark_image_use_in_pdf', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_watermark_image_use_in_pdf'] : '';
	$pgfw_border_position_top        = array_key_exists( 'pgfw_border_position_top', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_top'] : '';
	$pgfw_border_position_bottom     = array_key_exists( 'pgfw_border_position_bottom', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_bottom'] : '';
	$pgfw_border_position_left       = array_key_exists( 'pgfw_border_position_left', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_left'] : '';
	$pgfw_border_position_right      = array_key_exists( 'pgfw_border_position_right', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_border_position_right'] : '';
	$pgfw_body_custom_css            = array_key_exists( 'pgfw_body_custom_css', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_custom_css'] : '';
	// general settings.
	$general_settings_data     = get_option( 'pgfw_general_settings_save', array() );
	$pgfw_show_post_categories = array_key_exists( 'pgfw_general_pdf_show_categories', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_categories'] : '';
	$pgfw_show_post_tags       = array_key_exists( 'pgfw_general_pdf_show_tags', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_tags'] : '';
	$pgfw_show_post_taxonomy   = array_key_exists( 'pgfw_general_pdf_show_taxonomy', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_taxonomy'] : '';
	$pgfw_show_post_date       = array_key_exists( 'pgfw_general_pdf_show_post_date', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_post_date'] : '';
	$pgfw_show_post_author     = array_key_exists( 'pgfw_general_pdf_show_author_name', $general_settings_data ) ? $general_settings_data['pgfw_general_pdf_show_author_name'] : '';
	// meta fields settings.
	$pgfw_meta_settings = get_option( 'pgfw_meta_fields_save_settings', array() );
	// footer settings.
	$pgfw_footer_settings   = get_option( 'pgfw_footer_setting_submit', array() );
	$pgfw_footer_use_in_pdf = array_key_exists( 'pgfw_footer_use_in_pdf', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_use_in_pdf'] : '';
	$pgfw_footer_tagline    = array_key_exists( 'pgfw_footer_tagline', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_tagline'] : '';
	$pgfw_footer_color      = array_key_exists( 'pgfw_footer_color', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_color'] : '';
	$pgfw_footer_width      = array_key_exists( 'pgfw_footer_width', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_width'] : '';
	$pgfw_footer_font_style = array_key_exists( 'pgfw_footer_font_style', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_font_style'] : '';
	$pgfw_footer_font_style = ( 'custom' === $pgfw_footer_font_style ) ? 'My_font' : $pgfw_footer_font_style;
	$pgfw_footer_font_size  = array_key_exists( 'pgfw_footer_font_size', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_font_size'] : '';
	$pgfw_footer_bottom     = array_key_exists( 'pgfw_footer_bottom', $pgfw_footer_settings ) ? $pgfw_footer_settings['pgfw_footer_bottom'] : '';
	if ( 'yes' === $pgfw_body_rtl_support ) {
		$pgfw_header_font_style     = 'DejaVu Sans, sans-serif';
		$pgfw_body_page_font_style  = 'DejaVu Sans, sans-serif';
		$pgfw_body_title_font_style = 'DejaVu Sans, sans-serif';
		$pgfw_footer_font_style     = 'DejaVu Sans, sans-serif';
	}

	$html = '';
	if ( '' !== $pgfw_ttf_font_upload && ( $pgfw_ttf_font_upload ) ) {
		$upload_dir     = wp_upload_dir();
		$upload_baseurl = $upload_dir['baseurl'] . '/pgfw_ttf_font/';
		$upload_basedir = $upload_dir['basedir'] . '/pgfw_ttf_font/';
		$font_url       = $upload_baseurl . $pgfw_ttf_font_upload;
		$font_path      = $upload_basedir . $pgfw_ttf_font_upload;
		if ( file_exists( $font_path ) ) {
			$html = '<style>
					@font-face{
						font-family : My_font;
						src         : url("' . $font_url . '")  format("truetype");
					}
				</style>';
		}
	}
	$html .= '<style>
			@page {
				margin-top    : ' . $pgfw_body_margin_top . ';
				margin-left   : ' . $pgfw_body_margin_left . ';
				margin-right  : ' . $pgfw_body_margin_right . ';
				margin-bottom : ' . $pgfw_body_margin_bottom . ';
			}
		</style>';
	if ( $pgfw_body_border_size > 0 ) {
		$html .= '<style>
			.pgfw-border-page {
				position      : fixed;
				margin-bottom : ' . $pgfw_border_position_bottom . ';
				margin-left   : ' . $pgfw_border_position_left . ';
				margin-top    : ' . $pgfw_border_position_top . ';
				margin-right  : ' . $pgfw_border_position_right . ';
				border        : ' . $pgfw_body_border_size . 'px solid ' . $pgfw_body_border_color . ';
			}
		</style>
		<div class="pgfw-border-page" ></div>';
	}
	// Header for pdf.
	if ( 'yes' === $pgfw_header_use_in_pdf ) {
		$html .= '<style>
					.pgfw-pdf-header-each-page{
						position : fixed;
						left     : 0px;
						height   : 100px;
						top      : ' . $pgfw_header_top . ';
					}
					.pgfw-pdf-header{
						border-bottom  : 2px solid gray;
						padding        : ' . $pgfw_header_width . 'px;
						font-family    : ' . $pgfw_header_font_style . ';
						font-size      : ' . $pgfw_header_font_size . ';
						padding-bottom : 25px;
					}
					.pgfw-header-logo{
						width  : 50px;
						height : 50px;
						float  : left;
					}
					.pgfw-header-tagline{
						text-align : right;
						color      : ' . $pgfw_header_color . ';
					}
				</style>
				<div class="pgfw-pdf-header-each-page">
					<div class="pgfw-pdf-header">
						<img src="' . esc_url( $pgfw_header_logo ) . '" alt="' . esc_html__( 'No image found' ) . '" class="pgfw-header-logo">
						<div class="pgfw-header-tagline" >
							<span><b>' . esc_html( strtoupper( $pgfw_header_comp_name ) ) . '</b></span><br/>
							<span>' . esc_html( $pgfw_header_tagline ) . '</span>
						</div>
					</div>
				</div>';
	}
	// footer for pdf.
	if ( 'yes' === $pgfw_footer_use_in_pdf ) {
		$html .= '<style>
			.pgfw-pdf-footer{
				position    : fixed;
				left        : 0px;
				bottom      : ' . $pgfw_footer_bottom . ';
				height      : 150px;
				border-top  : 2px solid gray;
				padding     : ' . $pgfw_footer_width . 'px;
				font-family : ' . $pgfw_footer_font_style . ';
				font-size   : ' . $pgfw_footer_font_size . ';
			}
			.pgfw-footer-tagline{
				color      : ' . $pgfw_footer_color . ';
				text-align : center;
				overflow   : hidden;
			}
			.pgfw-footer-pageno:after {
				content : "Page " counter(page);
			}
		</style>';
		$html .= '<div class="pgfw-pdf-footer">
					<span class="pgfw-footer-pageno"></span>
					<div class="pgfw-footer-tagline" >
						<span>' . esc_html( $pgfw_footer_tagline ) . '</span>
					</div>
				</div>';
	}
	// body for pdf.
	if ( 'yes' === $pgfw_watermark_image_use_in_pdf ) {
		$html = apply_filters( 'pgfw_customize_body_watermark_image_pdf', $html );
	}
	if ( '' !== $pgfw_body_custom_css ) {
		$html .= '<style>
					' . $pgfw_body_custom_css . '
				</style>';
	}
	$post = get_post( $post_id );
	if ( is_object( $post ) ) {
		$html .= '<style>
					.pgfw-pdf-body-title{
						font-family : ' . $pgfw_body_title_font_style . ';
						font-size   : ' . $pgfw_body_title_font_size . 'px;
						color       : ' . $pgfw_body_title_font_color . ';
						padding     : 10px 0px;
					}
					.pgfw-pdf-body-title-image{
						margin-top : 10px;
					}
					.pgfw-pdf-body-title-image img{
						width  : 200px;
						height : 200px;
					}
					.pgfw-pdf-body-content{
						font-family : ' . $pgfw_body_page_font_style . ';
						font-size   : ' . $pgfw_body_page_font_size . ';
						color       : ' . $pgfw_body_page_font_color . ';
					}
				</style>
				<div class="pgfw-pdf-body">
					<div class="pgfw-pdf-body-title-image">
						<img src="' . get_the_post_thumbnail_url( $post ) . '">
					</div>
					<div class="pgfw-pdf-body-title">
						' . do_shortcode( $post->post_title ) . '
					</div>
					<div class="pgfw-pdf-body-content">
					<h3>' . esc_html__( 'Short Description/Excerpt', 'pdf-generator-for-wp' ) . '</h3>
					<div>
						' . do_shortcode( $post->post_excerpt ) . '
					</div>
					<h3>' . esc_html__( 'Description', 'pdf-generator-for-wp' ) . '</h3>
					<div>
						' . do_shortcode( $post->post_content ) . '
					</div>';
		// taxonomies for posts.
		$html1 = '';
		if ( 'yes' === $pgfw_show_post_taxonomy ) {
			$taxonomies = get_object_taxonomies( $post );
			if ( is_array( $taxonomies ) ) {
				foreach ( $taxonomies as $taxonomy ) {
					$prod_cat = get_the_terms( $post, $taxonomy );
					if ( is_array( $prod_cat ) ) {
						$html1 .= '<div><b>' . strtoupper( str_replace( '_', ' ', $taxonomy ) ) . '</b></div>';
						$html1 .= '<ul>';
						foreach ( $prod_cat as $category ) {
							$html1 .= '<li>' . $category->name . '</li>';
						}
						$html1 .= '</ul>';
					}
				}
			}
		}
		$html .= apply_filters( 'mwb_pgfw_product_taxonomy_in_pdf_filter_hook', $html1, $post );
		// category for posts.
		if ( 'yes' === $pgfw_show_post_categories ) {
			$categories = get_the_category( $post->ID );
			if ( is_array( $categories ) && ! empty( $categories ) ) {
				$html .= '<div><b>' . esc_html__( 'Category', 'pdf-generator-for-wp' ) . '</b></div>';
				$html .= '<ul>';
				foreach ( $categories as $category ) {
					$html .= '<li>' . $category->name . '</li>';
				}
				$html .= '</ul>';
			}
		}
		// tags for posts.
		if ( 'yes' === $pgfw_show_post_tags ) {
			$tags = get_the_tags( $post );
			if ( is_array( $tags ) ) {
				$html .= '<div><b>' . __( 'Tags', 'pdf-generator-for-wp' ) . '</b></div>';
				$html .= '<ul>';
				foreach ( $tags as $tag ) {
					$html .= '<li>' . $tag->name . '</li> ';
				}
				$html .= '</ul>';
			}
		}
		// post created date.
		if ( 'yes' === $pgfw_show_post_date ) {
			$created_date = get_the_date( 'F Y', $post );
			$html        .= '<div><b>' . __( 'Date Created', 'pdf-generator-for-wp' ) . '</b></div>';
			$html        .= '<div>' . $created_date . '</div>';
		}
		// post author.
		if ( 'yes' === $pgfw_show_post_author ) {
			$author_id   = $post->post_author;
			$author_name = get_the_author_meta( 'user_nicename', $author_id );
			$html       .= '<div><b>' . __( 'Author', 'pdf-generator-for-wp' ) . '</b></div>';
			$html       .= '<div>' . $author_name . '</div>';
		}
		// meta fields.
		$post_type               = $post->post_type;
		$pgfw_show_type_meta_val = array_key_exists( 'pgfw_meta_fields_' . $post_type . '_show', $pgfw_meta_settings ) ? $pgfw_meta_settings[ 'pgfw_meta_fields_' . $post_type . '_show' ] : '';
		$pgfw_show_type_meta_arr = array_key_exists( 'pgfw_meta_fields_' . $post_type . '_list', $pgfw_meta_settings ) ? $pgfw_meta_settings[ 'pgfw_meta_fields_' . $post_type . '_list' ] : array();
		$html2                   = '';
		if ( 'yes' === $pgfw_show_type_meta_val ) {
			if ( is_array( $pgfw_show_type_meta_arr ) ) {
				$html2 .= '<div><b>' . __( 'Meta Fields', 'pdf-generator-for-wp' ) . '</b></div>';
				foreach ( $pgfw_show_type_meta_arr as $meta_key ) {
					$meta_val          = get_post_meta( $post->ID, $meta_key, true );
					$wpg_meta_key_name = strtoupper( str_replace( '_', ' ', $meta_key ) );
					if ( $meta_val ) {
						$html2 .= '<div><b>' . $wpg_meta_key_name . ' : </b> ' . $meta_val . '</div>';
					}
				}
			}
		}
		$html .= apply_filters( 'mwb_pgfw_product_post_meta_in_pdf_filter_hook', $html2, $post );
		$html .= '</div></div><span style="page-break-after: always;overflow:hidden;"></span>';
	}
	return $html;
}
