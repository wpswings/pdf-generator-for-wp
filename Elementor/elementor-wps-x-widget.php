<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) exit;

class Elementor_Widget_WPS_X extends Widget_Base
{

	public function get_name()
	{
		return 'wps_x_embed';
	}

	public function get_title()
	{
		return __('WPSwings X (Twitter) Post Embed', 'pdf-generator-for-wp');
	}

	public function get_icon()
	{
		return 'eicon-twitter';
	}

	public function get_categories()
	{
		return ['wps_pdf_widgets']; // Or 'general'
	}

	protected function _register_controls()
	{
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Embed Settings', 'pdf-generator-for-wp'),
			]
		);

		$this->add_control(
			'post_url',
			[
				'label'       => __('Post URL', 'pdf-generator-for-wp'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __('https://twitter.com/user/status/123456789', 'pdf-generator-for-wp'),
				'default'     => '',
			]
		);

		$this->end_controls_section();
	}

	protected function render()
	{
		$post_url = esc_url($this->get_settings_for_display('post_url'));

		if ($post_url):
?>
			<blockquote class="twitter-tweet">
				<a href="<?php echo $post_url; ?>">View Tweet</a>
			</blockquote>
			<script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>
<?php
		else:
			echo '<p>Please enter a valid X (Twitter) post URL.</p>';
		endif;
	}
}
