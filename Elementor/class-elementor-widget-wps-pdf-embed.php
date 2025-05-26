<?php
/**
 * Elementor Widget for embedding PDF files
 *
 * @package PDF Generator for WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Elementor_Widget_WPS_PDF_Embed
 */
class Elementor_Widget_WPS_PDF_Embed extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'wps_pdf_embed';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return __( 'WPSwings PDF Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-file-download';
	}

	/**
	 * Get widget categories.
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' );
	}

	/**
	 * Register widget controls.
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			array(
				'label' => __( 'PDF Settings', 'pdf-generator-for-wp' ),
			)
		);

		$this->add_control(
			'pdf_url',
			array(
				'label'       => __( 'PDF File', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::MEDIA,
				'media_type'  => 'application/pdf',
				'description' => __( 'Upload a PDF file to embed.', 'pdf-generator-for-wp' ),
				'default'     => array(
					'url' => 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation',
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on frontend.
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
		$pdf_url  = isset( $settings['pdf_url']['url'] ) ? esc_url( $settings['pdf_url']['url'] ) : '';

		if ( ! empty( $pdf_url ) ) {
			echo '<iframe src="' . esc_url( $pdf_url ) . '" width="100%" height="500px" style="border: 1px solid #ddd;"></iframe>';
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please upload a valid PDF file.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
