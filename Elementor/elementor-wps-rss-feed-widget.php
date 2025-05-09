<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Widget_WPS_Rss_Feed extends Widget_Base {

	public function get_name() {
		return 'wps_rssapp_feed';
	}

	public function get_title() {
		return __('RSS App Feed', 'textdomain');
	}

	public function get_icon() {
		return 'eicon-post-list';
	}

	public function get_categories() {
		return ['wps_pdf_widgets']; // Use 'general' if you're not registering custom categories
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Feed Settings', 'textdomain'),
			]
		);

		$this->add_control(
			'url',
			[
				'label'       => __('RSS Widget URL', 'textdomain'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __('https://widget.rss.app/v1/list.html?widget=xxxx', 'textdomain'),
			]
		);

		$this->add_control(
			'title',
			[
				'label'   => __('Header Title', 'textdomain'),
				'type'    => Controls_Manager::TEXT,
				'default' => '📰 Latest News',
			]
		);

		$this->add_control(
			'height',
			[
				'label'   => __('Iframe Height', 'textdomain'),
				'type'    => Controls_Manager::TEXT,
				'default' => '600px',
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label'   => __('Background Color', 'textdomain'),
				'type'    => Controls_Manager::COLOR,
				'default' => '#ffffff',
			]
		);

		$this->add_control(
			'text_color',
			[
				'label'   => __('Text Color', 'textdomain'),
				'type'    => Controls_Manager::COLOR,
				'default' => '#333333',
			]
		);

		$this->add_control(
			'border_color',
			[
				'label'   => __('Border Color', 'textdomain'),
				'type'    => Controls_Manager::COLOR,
				'default' => '#eeeeee',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$atts = [
			'url'          => $this->get_settings_for_display('url'),
			'title'        => $this->get_settings_for_display('title'),
			'height'       => $this->get_settings_for_display('height'),
			'bg_color'     => $this->get_settings_for_display('bg_color'),
			'text_color'   => $this->get_settings_for_display('text_color'),
			'border_color' => $this->get_settings_for_display('border_color'),
		];

		// Render the shortcode
		echo do_shortcode( sprintf(
			'[wps_rssapp_feed url="%s" title="%s" height="%s" bg_color="%s" text_color="%s" border_color="%s"]',
			esc_attr( $atts['url'] ),
			esc_attr( $atts['title'] ),
			esc_attr( $atts['height'] ),
			esc_attr( $atts['bg_color'] ),
			esc_attr( $atts['text_color'] ),
			esc_attr( $atts['border_color'] )
		) );
	}
}
