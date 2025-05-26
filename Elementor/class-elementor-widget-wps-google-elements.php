<?php
/**
 * Elementor Widget for embedding Google services
 *
 * @package PDF Generator for WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Elementor_Widget_WPS_Google_Elements
 */
class Elementor_Widget_WPS_Google_Elements extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'wps_google_embed';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return __( 'WPSwings Google Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-google-maps';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' );
	}

	/**
	 * Register controls for the widget.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Google Embed Settings', 'pdf-generator-for-wp' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'google_url',
			array(
				'label' => __( 'Google Service Embed URL', 'pdf-generator-for-wp' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => __( 'Paste Google Docs, Sheets, Slides, Forms, etc. URL', 'pdf-generator-for-wp' ),
				'default' => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Convert input URL to embed URL.
	 *
	 * @param string $url Google service URL.
	 * @return string
	 */
	private function get_embed_url( $url ) {
		if ( ! $url ) {
			return '';
		}

		if ( strpos( $url, 'docs.google.com/presentation' ) !== false ) {
			$url_parts  = wp_parse_url( $url );
			$embed_path = str_replace( '/pub', '/embed', $url_parts['path'] ?? '' );
			return 'https://docs.google.com' . $embed_path;
		}

		if ( strpos( $url, 'docs.google.com/document' ) !== false ) {
			return str_replace( '/edit', '/preview', $url );
		}

		if ( strpos( $url, 'docs.google.com/spreadsheets' ) !== false ) {
			return str_replace( '/edit', '/pubhtml', $url );
		}

		if ( strpos( $url, 'docs.google.com/forms' ) !== false ) {
			return str_replace( '/viewform', '/viewform?embedded=true', $url );
		}

		if ( strpos( $url, 'www.google.com/maps' ) !== false ) {
			preg_match( '/@([-.\d]+),([-.\d]+)/', $url, $match );
			$lat = $match[1] ?? '';
			$lng = $match[2] ?? '';

			preg_match( '/place\/([^/@]+)/', $url, $place_match );
			$place = isset( $place_match[1] ) ? str_replace( '+', ' ', $place_match[1] ) : '';

			return 'https://maps.google.com/maps?hl=en&ie=UTF8&ll=' . esc_attr( $lat ) . ',' . esc_attr( $lng ) .
				   '&spn=' . esc_attr( $lat ) . ',' . esc_attr( $lng ) . '&q=' . esc_attr( $place ) .
				   '&t=m&z=17&output=embed&iwloc';
		}

		if ( strpos( $url, 'calendar.google.com/calendar' ) !== false ) {
			return $url;
		}

		if ( strpos( $url, 'www.youtube.com/watch' ) !== false ) {
			return str_replace( 'watch?v=', 'embed/', $url );
		}

		return $url;
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$url        = $settings['google_url'];
		$embed_url  = esc_url( $this->get_embed_url( $url ) );

		if ( strpos( $url, 'docs.google.com/drawings' ) !== false ) {
			echo '<img src="' . esc_url( $embed_url ) . '" width="800" height="500" style="border:1px solid #ddd;" />';
		} elseif ( $embed_url ) {
			echo '<iframe src="' . esc_url( $embed_url ) . '" width="800" height="500" allowfullscreen style="border:1px solid #ddd;"></iframe>';
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Invalid or unsupported Google embed URL.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
