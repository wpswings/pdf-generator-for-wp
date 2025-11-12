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
 * Class Elementor_Widget_WPS_Reddit
 *
 * Elementor widget to embed Reddit posts.
 */
class Elementor_Widget_WPS_Reddit extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget unique name.
	 */
	public function get_name() {
		return 'wps_reddit_embed';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings Reddit Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon class.
	 */
	public function get_icon() {
		return 'eicon-social-icons';
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
			'section_content',
			array(
				'label' => __( 'Reddit Embed Settings', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'reddit_url',
			array(
				'label'       => __( 'Reddit Post URL', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Reddit post URL', 'pdf-generator-for-wp' ),
				'default'     => 'https://www.reddit.com/r/Wordpress/comments/xxxxx/sample_post/',
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
		$settings   = $this->get_settings_for_display();
		$reddit_url = isset( $settings['reddit_url'] ) ? trim( $settings['reddit_url'] ) : '';

		if ( empty( $reddit_url ) ) {
			echo '<p style="color:red;">' . esc_html__( 'Please enter a valid Reddit post URL.', 'pdf-generator-for-wp' ) . '</p>';
			return;
		}
		?>
		<blockquote class="reddit-embed-bq" data-embed-height="316">
			<a href="<?php echo esc_url( $reddit_url ); ?>">
				<?php echo esc_html__( 'View Reddit Post', 'pdf-generator-for-wp' ); ?>
			</a>
		</blockquote>
		<?php

		// Enqueue Reddit embed script in the footer.
		add_action( 'wp_footer', array( $this, 'enqueue_reddit_embed_script' ) );
	}

	/**
	 * Enqueue Reddit embed script.
	 *
	 * @return void
	 */
	public function enqueue_reddit_embed_script() {
		wp_enqueue_script(
			'wps-reddit-embed',
			'https://embed.reddit.com/widgets.js',
			array(),
			'1.0.0',
			true
		);
	}
}
