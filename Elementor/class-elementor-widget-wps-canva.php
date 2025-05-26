<?php
/**
 * Elementor Widget: WPS Canva Embed
 *
 * @package PDF_Generator_For_WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Elementor_Widget_WPS_Canva
 *
 * Elementor widget to embed Canva designs.
 */
class Elementor_Widget_WPS_Canva extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string
	 */
	public function get_name() {
		return 'wps_canva_embed';
	}

	/**
	 * Get widget title.
	 *
	 * @return string
	 */
	public function get_title() {
		return __( 'WPSwings Canva Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-posts-ticker';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' ); // Replace with 'general' if using default Elementor category.
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Canva Settings', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'canva_url',
			array(
				'label'       => __( 'Canva URL', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'https://www.canva.com/design/your-design-id/watch', 'pdf-generator-for-wp' ),
				'default'     => 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation',
				'label_block' => true,
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$url      = isset( $settings['canva_url'] ) ? esc_url( $settings['canva_url'] ) : '';

		if ( ! empty( $url ) ) {
			?>
			<div style="position:relative;width:100%;height:0;padding-top:56.25%;overflow:hidden;border-radius:8px;box-shadow:0 2px 8px rgba(63,69,81,0.16);margin-top:1.6em;margin-bottom:0.9em;">
				<iframe
					src="<?php echo esc_url( $url ) . '?embed'; ?>"
					style="position:absolute;width:100%;height:100%;top:0;left:0;border:none;"
					allowfullscreen>
				</iframe>
			</div>
			<p style="margin-top:10px;">
				<?php esc_html_e( 'Need help?', 'pdf-generator-for-wp' ); ?>
				<a href="https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation"
					target="_blank"
					rel="noopener noreferrer"
					style="color:#1DA1F2;">
					<?php esc_html_e( 'How to use this block?', 'pdf-generator-for-wp' ); ?>
				</a>
			</p>
			<?php
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please provide a Canva URL in the widget settings.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
