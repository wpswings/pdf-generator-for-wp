<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Widget_WPS_Reddit extends Widget_Base {

	public function get_name() {
		return 'wps_reddit_embed';
	}

	public function get_title() {
		return __('WPSwings Reddit Embed', 'textdomain');
	}

	public function get_icon() {
		return 'eicon-social-icons';
	}

	public function get_categories() {
		return ['wps_pdf_widgets']; // Custom category or use 'general'
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __('Reddit Embed Settings', 'textdomain'),
			]
		);

		$this->add_control(
			'reddit_url',
			[
				'label'       => __('Reddit Post URL', 'textdomain'),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __('Enter Reddit post URL', 'textdomain'),
				'default'     => 'https://www.reddit.com/r/Wordpress/comments/xxxxx/sample_post/',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings   = $this->get_settings_for_display();
		$reddit_url = esc_url( $settings['reddit_url'] );

		if ( $reddit_url ):
			?>
			<blockquote class="reddit-embed-bq" data-embed-height="316">
				<a href="<?php echo $reddit_url; ?>">View Reddit Post</a>
			</blockquote>
			<script async src="https://embed.reddit.com/widgets.js" charset="UTF-8"></script>
			<?php
		else:
			echo '<p>Please enter a valid Reddit post URL.</p>';
		endif;
	}
}
