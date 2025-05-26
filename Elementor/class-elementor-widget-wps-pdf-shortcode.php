<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Elementor Widget: WPSwings PDF Shortcode
 */
class Elementor_Widget_WPS_PDF_Shortcode extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget unique name.
	 */
	public function get_name() {
		return 'wps_pdf_shortcode_widget';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings PDF Shortcode', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon class.
	 */
	public function get_icon() {
		return 'eicon-document-file';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' );
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'PDF Shortcode', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'pdf_shortcode',
			array(
				'label'       => __( 'Enter Shortcode', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '[WORDPRESS_PDF]',
				'default'     => '[WORDPRESS_PDF]',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * @return void
	 */
	protected function render() {
		$settings  = $this->get_settings_for_display();
		$shortcode = trim( $settings['pdf_shortcode'] ?? '' );

		if ( ! empty( $shortcode ) ) {
			echo do_shortcode( wp_kses_post( $shortcode ) );
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please enter a valid shortcode.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
