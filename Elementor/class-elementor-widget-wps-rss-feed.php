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
 * Elementor Widget: WPSwings RSS App Feed
 *
 * Displays an RSS feed widget using a shortcode with customizable options.
 *
 * @package PDF_Generator_For_WP
 */
class Elementor_Widget_WPS_Rss_Feed extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget unique name.
	 */
	public function get_name() {
		return 'wps_rssapp_feed';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings RSS App Feed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon class.
	 */
	public function get_icon() {
		return 'eicon-post-list';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' ); // Use 'general' if not registering custom categories.
	}

	/**
	 * Register widget controls.
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Feed Settings', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'url',
			array(
				'label'       => __( 'RSS Widget URL', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'https://widget.rss.app/v1/list.html?widget=xxxx', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'   => __( 'Header Title', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '📰 Latest News',
			)
		);

		$this->add_control(
			'height',
			array(
				'label'   => __( 'Iframe Height', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '600px',
			)
		);

		$this->add_control(
			'bg_color',
			array(
				'label'   => __( 'Background Color', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ffffff',
			)
		);

		$this->add_control(
			'text_color',
			array(
				'label'   => __( 'Text Color', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333333',
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'   => __( 'Border Color', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::COLOR,
				'default' => '#eeeeee',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Outputs the shortcode for the RSS feed widget with all parameters escaped.
	 *
	 * @return void
	 */
	protected function render() {
		$atts = array(
			'url'          => $this->get_settings_for_display( 'url' ),
			'title'        => $this->get_settings_for_display( 'title' ),
			'height'       => $this->get_settings_for_display( 'height' ),
			'bg_color'     => $this->get_settings_for_display( 'bg_color' ),
			'text_color'   => $this->get_settings_for_display( 'text_color' ),
			'border_color' => $this->get_settings_for_display( 'border_color' ),
		);

		// Render the RSS feed shortcode with escaped attributes.
		echo do_shortcode(
			sprintf(
				'[wps_rssapp_feed url="%s" title="%s" height="%s" bg_color="%s" text_color="%s" border_color="%s"]',
				esc_attr( $atts['url'] ),
				esc_attr( $atts['title'] ),
				esc_attr( $atts['height'] ),
				esc_attr( $atts['bg_color'] ),
				esc_attr( $atts['text_color'] ),
				esc_attr( $atts['border_color'] )
			)
		);
	}
}
