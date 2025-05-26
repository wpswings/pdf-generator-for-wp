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
 * Elementor Widget: WPSwings Single Image
 *
 * Widget to display a single image via shortcode with customizable ID, width, and height.
 *
 * @package PDF_Generator_For_WP
 */
class Elementor_Widget_WPS_Single_Image extends Widget_Base {

	/**
	 * Get widget unique name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wps_single_image_widget';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings Single Image', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon CSS class.
	 */
	public function get_icon() {
		return 'eicon-image';
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
	 * Defines input fields for image ID, width, and height.
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Image Settings', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'image_id',
			array(
				'label'       => __( 'Image ID', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Image ID', 'pdf-generator-for-wp' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'image_width',
			array(
				'label'       => __( 'Width (px)', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( '50', 'pdf-generator-for-wp' ),
				'default'     => '50',
			)
		);

		$this->add_control(
			'image_height',
			array(
				'label'       => __( 'Height (px)', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( '50', 'pdf-generator-for-wp' ),
				'default'     => '50',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Outputs the [WPS_SINGLE_IMAGE] shortcode with provided attributes if Image ID is set.
	 * Otherwise, outputs an error message.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$id     = esc_attr( $settings['image_id'] );
		$width  = esc_attr( $settings['image_width'] );
		$height = esc_attr( $settings['image_height'] );

		if ( ! empty( $id ) ) {
			echo do_shortcode( '[WPS_SINGLE_IMAGE id="' . $id . '" width="' . $width . '" height="' . $height . '"]' );
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please provide an Image ID in the widget settings.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
