<?php
/**
 * Elementor Widget: WPS Calendly Embed
 *
 * @package PDF_Generator_For_WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Elementor_Widget_WPS_Calendly
 *
 * Elementor widget for embedding a Calendly link via shortcode.
 */
class Elementor_Widget_WPS_Calendly extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wps_calendly_widget';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'WPSwings Calendly Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-social-icons';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' ); // Replace with 'general' if not using a custom category.
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Calendly Settings', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'calendly_url',
			array(
				'label'         => __( 'Calendly URL', 'pdf-generator-for-wp' ),
				'type'          => Controls_Manager::URL,
				'placeholder'   => __( 'https://calendly.com/your-link', 'pdf-generator-for-wp' ),
				'default'       => array(
					'url' => '',
				),
				'show_external' => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$url = isset( $settings['calendly_url']['url'] ) ? esc_url( $settings['calendly_url']['url'] ) : '';

		if ( ! empty( $url ) ) {
			echo do_shortcode( sprintf( '[wps_calendly url="%s"]', $url ) );
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please set a Calendly URL in the widget settings.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
