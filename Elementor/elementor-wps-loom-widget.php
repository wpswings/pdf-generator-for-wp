<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Widget_WPS_Loom extends Widget_Base {

	public function get_name() {
		return 'wps_loom_embed';
	}

	public function get_title() {
		return __('WPSwings Loom Video Embed', 'textdomain');
	}

	public function get_icon() {
		return 'eicon-video-camera';
	}

	public function get_categories() {
		return ['wps_pdf_widgets']; // Use 'general' or your custom category
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Embed Settings', 'textdomain'),
			]
		);

		$this->add_control(
			'loom_url',
			[
				'label'       => __('Loom Share URL', 'textdomain'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __('https://www.loom.com/share/your-video-id', 'textdomain'),
				'default'     => 'https://www.loom.com/share/your-video-id',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$loom_url = $this->get_settings_for_display('loom_url');
		$embed_url = '';

		// Parse Loom video ID
		if (preg_match('/loom\.com\/share\/([a-zA-Z0-9]+)/', $loom_url, $matches)) {
			$embed_url = 'https://www.loom.com/embed/' . esc_attr($matches[1]);
		}

		if ($embed_url):
			echo '<iframe 
				src="' . esc_url($embed_url) . '" 
				width="640" 
				height="360" 
				style="border: 1px solid #ddd;" 
				allowfullscreen>
			</iframe>';
		else:
			echo '<p>Please enter a valid Loom share URL.</p>';
		endif;
	}
}
