<?php
/**
 * Elementor Widget: WPSwings Reddit Embed
 *
 * @package PDF_Generator_For_WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Widget: WPSwings Strava Embed
 *
 * Widget to embed a Strava activity via shortcode using a Strava Activity ID.
 *
 * @package PDF_Generator_For_WP
 */
class Elementor_Widget_WPS_Strava extends Widget_Base {

	/**
	 * Get widget unique name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wps_strava_widget';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings Strava Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon CSS class.
	 */
	public function get_icon() {
		return 'eicon-calendar';
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
	 * Adds a control for entering the Strava Activity ID.
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Strava Settings', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'strava_id',
			array(
				'label'       => __( 'Strava Activity ID', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'e.g. 123456789', 'pdf-generator-for-wp' ),
				'default'     => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Outputs the [wps_strava] shortcode with the provided activity ID.
	 * Displays an error message if no ID is provided.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$id       = isset( $settings['strava_id'] ) ? sanitize_text_field( $settings['strava_id'] ) : '';

		if ( ! empty( $id ) ) {
			echo do_shortcode( '[wps_strava id="' . esc_attr( $id ) . '"]' );
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please enter a Strava Activity ID in the widget settings.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
