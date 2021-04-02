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
 * @param int $post_id post id.
 * @since 1.0.0
 *
 * @return string
 */
function return_ob_html( $post_id ) {
	$post                      = get_post( $post_id );
	$thumbnail_url             = get_the_post_thumbnail_url( $post );
	$pgfw_body_settings        = get_option( 'pgfw_body_save_settings', array() );
	$pgfw_body_page_font_style = array_key_exists( 'pgfw_body_page_font_style', $pgfw_body_settings ) ? $pgfw_body_settings['pgfw_body_page_font_style'] : '';

	$html  = '<style>
			div#font-family{
				font-family: ' . $pgfw_body_page_font_style . ';
			}
		</style>
	<div id="font-family">';
	$html .= '<h3>' . $post->post_title . '</h3>';
	$html .= '<div>' . $post->post_content . '</div>';
	$html .= '<br/><div><img src=' . $thumbnail_url . ' style="width:200px;height:200px;"></div>';
	if ( 'product' === $post->post_type ) {
		$prod_cat = get_the_terms( $post, 'product_cat' );
		if ( is_array( $prod_cat ) ) {
			$html .= '<h4>Product Categories</h4>';
			$html .= '<ul>';
			foreach ( $prod_cat as $category ) {
				$html .= '<li>' . $category->name . '</li>';
			}
			$html .= '</ul>';
		}
	}
	$sku   = get_post_meta( $post_id, '_sku', true );
	$price = get_post_meta( $post_id, '_price', true );
	$html .= '<h4>Product Meta Fields</h4>';
	if ( $sku ) {
		$html .= '<div><b>SKU : </b>' . $sku . '</div>';
	}
	if ( $price ) {
		$html .= '<div><b>Price : </b>' . $price . '</div>';
	}
	$html .= '</div>';
	return $html;
}
