<?php
/**
 * Elementor Widget for embedding LinkedIn posts
 *
 * @package PDF Generator for WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Elementor_Widget_WPS_LinkedIn
 */
class Elementor_Widget_WPS_LinkedIn extends Widget_Base {

	/**
	 * Get widget name.
	 */
	public function get_name() {
		return 'wps_linkedin_widget';
	}

	/**
	 * Get widget title.
	 */
	public function get_title() {
		return __( 'WPSwings LinkedIn Embed', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 */
	public function get_icon() {
		return 'eicon-social-icons';
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
			'content_section',
			array(
				'label' => __( 'LinkedIn Settings', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'linkedin_post_id',
			array(
				'label'       => __( 'LinkedIn Post ID', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => '7307328960277684224',
				'default'     => '',
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 */
	protected function render() {
		$settings  = $this->get_settings_for_display();
		$post_id   = isset( $settings['linkedin_post_id'] ) ? trim( $settings['linkedin_post_id'] ) : '';
		$help_url  = 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation';

		if ( ! empty( $post_id ) ) {
			$embed_url = 'https://www.linkedin.com/embed/feed/update/urn:li:share:' . rawurlencode( $post_id );

			echo '<iframe src="' . esc_url( $embed_url ) . '" width="600" height="400" allowfullscreen style="border: 1px solid #ddd;"></iframe>';
		} else {
			echo '<p style="color:red;">' . esc_html__( 'Please enter a LinkedIn Post ID in the widget settings.', 'pdf-generator-for-wp' ) . '</p>';
		}
	}
}
