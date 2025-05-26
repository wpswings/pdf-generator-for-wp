<?php
/**
 * Elementor Widget: WPS AI Chatbot
 *
 * @package PDF_Generator_For_WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Elementor_Widget_WPS_Ai_Chatbot
 *
 * Elementor widget for displaying a shortcode-based AI chatbot.
 */
class Elementor_Widget_WPS_Ai_Chatbot extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wps_shortcoded_chatbot';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'WPSwings AI Chatbot', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-comments';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' ); // Or 'general' if you haven't registered a custom category.
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Chatbot Settings', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'url',
			array(
				'label'       => __( 'Chatbot URL', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __( 'https://your-chatbot.com', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'height',
			array(
				'label'   => __( 'Height', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '700px',
			)
		);

		$this->add_control(
			'header_color',
			array(
				'label'   => __( 'Header Color', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#4e54c8',
			)
		);

		$this->add_control(
			'header_title',
			array(
				'label'   => __( 'Header Title', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => 'AI Chat Assistant',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output.
	 */
	protected function render() {
		$atts = array(
			'url'          => $this->get_settings_for_display( 'url' ),
			'height'       => $this->get_settings_for_display( 'height' ),
			'header_color' => $this->get_settings_for_display( 'header_color' ),
			'header_title' => $this->get_settings_for_display( 'header_title' ),
		);

		echo do_shortcode(
			sprintf(
				'[wps_ai_chatbot url="%s" height="%s" header_color="%s" header_title="%s"]',
				esc_attr( $atts['url'] ),
				esc_attr( $atts['height'] ),
				esc_attr( $atts['header_color'] ),
				esc_attr( $atts['header_title'] )
			)
		);
	}
}
