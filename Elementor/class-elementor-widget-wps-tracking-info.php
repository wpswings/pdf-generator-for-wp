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
 * Elementor Widget: WPSwings Tracking Info
 *
 * Displays tracking information via shortcode for a given Order ID and alignment.
 *
 * @package PDF_Generator_For_WP
 */
class Elementor_Widget_WPS_Tracking_Info extends Widget_Base {

	/**
	 * Get widget unique name.
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'wps_tracking_info_pdf';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings Tracking Info', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon CSS class.
	 */
	public function get_icon() {
		return 'eicon-kit-upload-alt';
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
	 * Adds controls for Order ID and Order Alignment.
	 *
	 * @return void
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Tracking Info Settings', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'order_id',
			array(
				'label'       => __( 'Order ID', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Order ID', 'pdf-generator-for-wp' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'order_align',
			array(
				'label'   => __( 'Order Alignment', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'left'   => __( 'Left', 'pdf-generator-for-wp' ),
					'center' => __( 'Center', 'pdf-generator-for-wp' ),
					'right'  => __( 'Right', 'pdf-generator-for-wp' ),
				),
				'default' => 'left',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Outputs the [wps_tracking_info] shortcode with provided order ID and alignment.
	 * Displays an error message if no Order ID is provided.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$id       = esc_attr( $settings['order_id'] );
		$align    = esc_attr( $settings['order_align'] );

		if ( ! empty( $id ) ) {
			echo do_shortcode( '[wps_tracking_info order_id="' . $id . '" align="' . $align . '"]' );
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please provide an Order ID in the widget settings.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
