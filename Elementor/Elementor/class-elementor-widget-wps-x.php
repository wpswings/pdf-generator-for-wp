<?php
/**
 * Elementor Widget: WPSwings X (Twitter) Post Embed
 *
 * @package PDF_Generator_For_WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Widget: WPSwings X (Twitter) Post Embed.
 *
 * This widget embeds an X (formerly Twitter) post using the post URL.
 * Users can specify the URL of the tweet to embed.
 *
 * @package PDF_Generator_For_WP
 * @since 1.0.0
 */
class Elementor_Widget_WPS_X extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget slug/name.
	 */
	public function get_name() {
		return 'wps_x_embed';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings X (Twitter) Post Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Icon class.
	 */
	public function get_icon() {
		return 'eicon-twitter';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array List of categories the widget belongs to.
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' ); // Or 'general'.
	}

	/**
	 * Register widget controls.
	 *
	 * Adds a text control to accept the URL of the X (Twitter) post.
	 *
	 * @return void
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'Embed Settings', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'post_url',
			array(
				'label'       => __( 'Post URL', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'https://twitter.com/user/status/123456789', 'pdf-generator-for-wp' ),
				'default'     => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Enqueue necessary scripts for the widget.
	 *
	 * Enqueues Twitter widgets.js script needed for embedding tweets.
	 *
	 * @return void
	 */
	public function enqueue_scripts() {
		wp_enqueue_script(
			'twitter-widgets',
			'https://platform.twitter.com/widgets.js',
			array(),
			time(),
			true
		);
	}

	/**
	 * Render widget output on frontend.
	 *
	 * Outputs the blockquote for the tweet embed if a valid URL is provided.
	 * Otherwise, outputs an error message.
	 *
	 * @return void
	 */
	protected function render() {
		$post_url = esc_url( $this->get_settings_for_display( 'post_url' ) );

		if ( $post_url ) :
			// Enqueue Twitter embed script.
			$this->enqueue_scripts();
			?>
			<blockquote class="twitter-tweet">
				<a href="<?php echo esc_url( $post_url ); ?>">View Tweet</a>
			</blockquote>
			<?php
		else :
			echo '<p>' . esc_html__( 'Please enter a valid X (Twitter) post URL.', 'pdf-generator-for-wp' ) . '</p>';
		endif;
	}
}
