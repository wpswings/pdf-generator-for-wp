<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) exit;

class Elementor_Widget_WPS_Ai_Chatbot extends Widget_Base
{

	public function get_name()
	{
		return 'wps_shortcoded_chatbot';
	}

	public function get_title()
	{
		return __('WPSwings AI Chatbot', 'pdf-generator-for-wp');
	}

	public function get_icon()
	{
		return 'eicon-comments';
	}

	public function get_categories()
	{
		return ['wps_pdf_widgets']; // Or 'general' if you haven't registered a custom category
	}

	protected function _register_controls()
	{

		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Chatbot Settings', 'pdf-generator-for-wp'),
			]
		);

		$this->add_control(
			'url',
			[
				'label'       => __('Chatbot URL', 'pdf-generator-for-wp'),
				'type'        => Controls_Manager::TEXT,
				'default'     => '',
				'placeholder' => __('https://your-chatbot.com', 'pdf-generator-for-wp'),
			]
		);

		$this->add_control(
			'height',
			[
				'label'   => __('Height', 'pdf-generator-for-wp'),
				'type'    => Controls_Manager::TEXT,
				'default' => '700px',
			]
		);

		$this->add_control(
			'header_color',
			[
				'label'   => __('Header Color', 'pdf-generator-for-wp'),
				'type'    => Controls_Manager::COLOR,
				'default' => '#4e54c8',
			]
		);

		$this->add_control(
			'header_title',
			[
				'label'   => __('Header Title', 'pdf-generator-for-wp'),
				'type'    => Controls_Manager::TEXT,
				'default' => 'AI Chat Assistant',
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$atts = [
			'url'          => $this->get_settings_for_display('url'),
			'height'       => $this->get_settings_for_display('height'),
			'header_color' => $this->get_settings_for_display('header_color'),
			'header_title' => $this->get_settings_for_display('header_title'),
		];

		echo do_shortcode(sprintf(
			'[wps_ai_chatbot url="%s" height="%s" header_color="%s" header_title="%s"]',
			esc_attr($atts['url']),
			esc_attr($atts['height']),
			esc_attr($atts['header_color']),
			esc_attr($atts['header_title'])
		));
	}
}
