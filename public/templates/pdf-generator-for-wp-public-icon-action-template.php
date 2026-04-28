<?php
/**
 * Shared frontend icon action renderer.
 *
 * @package Pdf_Generator_For_Wp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit();
}

if ( ! function_exists( 'pgfw_render_icon_action_button' ) ) {
	/**
	 * Render a shared icon action button.
	 *
	 * @param array $args Button arguments.
	 * @return string
	 */
	function pgfw_render_icon_action_button( $args = array() ) {
		$args = wp_parse_args(
			$args,
			array(
				'action_type'      => 'download',
				'display_template' => 'default',
				'href'             => '#',
				'title'            => '',
				'label'            => null,
				'icon_src'         => '',
				'id'               => '',
				'classes'          => array(),
				'attributes'       => array(),
				'image_only'       => false,
				'style_attribute'  => '',
			)
		);

		$classes = array_merge(
			array(
				'pgfw-single-pdf-download-button',
				'pgfw-single-pdf-download-button--' . sanitize_html_class( $args['display_template'] ),
				'pgfw-action-button',
				'pgfw-action-button--' . sanitize_html_class( $args['action_type'] ),
			),
			(array) $args['classes']
		);

		if ( 'download' === $args['action_type'] || 'email' === $args['action_type'] ) {
			$classes = array_diff( $classes, array( 'pgfw-action-button', 'pgfw-action-button--download', 'pgfw-action-button--email' ) );
		}

		if ( ! empty( $args['image_only'] ) ) {
			$classes[] = 'pgfw-single-pdf-download-button--image-only';
		}

		$classes[] = 'pgfw-single-pdf-download-button--icon-only';

		$attribute_markup = '';

		if ( ! empty( $args['id'] ) ) {
			$attribute_markup .= ' id="' . esc_attr( $args['id'] ) . '"';
		}

		if ( ! empty( $args['title'] ) ) {
			$attribute_markup .= ' title="' . esc_attr( $args['title'] ) . '"';
		}

		if ( ! empty( $args['style_attribute'] ) ) {
			$attribute_markup .= ' style="' . esc_attr( $args['style_attribute'] ) . '"';
		}

		foreach ( (array) $args['attributes'] as $attribute_name => $attribute_value ) {
			if ( '' === $attribute_value || null === $attribute_value ) {
				continue;
			}

			$attribute_markup .= ' ' . sanitize_key( $attribute_name ) . '="' . esc_attr( $attribute_value ) . '"';
		}

		$label            = null !== $args['label'] ? (string) $args['label'] : __( 'Download PDF', 'pdf-generator-for-wp' );
		$accessible_label = '';

		if ( '' !== $label ) {
			$accessible_label = $label;
		} elseif ( ! empty( $args['title'] ) ) {
			$accessible_label = (string) $args['title'];
		}

		if ( '' !== $accessible_label ) {
			$attribute_markup .= ' aria-label="' . esc_attr( $accessible_label ) . '"';
		}

		return '<a href="' . esc_url( $args['href'] ) . '" class="' . esc_attr( implode( ' ', array_filter( $classes ) ) ) . '"' . $attribute_markup . '><span class="pgfw-single-pdf-download-button__media" aria-hidden="true"><img src="' . esc_url( $args['icon_src'] ) . '" alt="" decoding="async"></span></a>';
	}
}
