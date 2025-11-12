<?php
/**
 * Elementor Widget for embedding Loom videos
 *
 * @package PDF Generator for WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Elementor_Widget_WPS_Loom
 */
class Elementor_Widget_WPS_Loom extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'wps_loom_embed';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return __( 'WPSwings Loom Video Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-video-camera';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' );
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Embed Settings', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'loom_url',
			array(
				'label'       => __( 'Loom Share URL', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'https://www.loom.com/share/your-video-id', 'pdf-generator-for-wp' ),
				'default'     => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on frontend.
	 */
	protected function render() {
		$settings   = $this->get_settings_for_display();
		$loom_url   = isset( $settings['loom_url'] ) ? trim( $settings['loom_url'] ) : '';
		$embed_url  = '';

		if ( preg_match( '/loom\.com\/share\/([a-zA-Z0-9]+)/', $loom_url, $matches ) ) {
			$embed_url = 'https://www.loom.com/embed/' . esc_attr( $matches[1] );
		}

		if ( $embed_url ) {
			echo '<iframe 
                src="' . esc_url( $embed_url ) . '" 
                width="640" 
                height="360" 
                style="border: 1px solid #ddd;" 
                allowfullscreen>
            </iframe>';
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please enter a valid Loom share URL.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
