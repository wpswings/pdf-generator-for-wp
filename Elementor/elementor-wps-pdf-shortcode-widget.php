<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_Widget_WPS_PDF_Shortcode extends Widget_Base {

    public function get_name() {
        return 'wps_pdf_shortcode_widget';
    }

    public function get_title() {
        return __('WPSwings PDF Shortcode', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-document-file';
    }

    public function get_categories() {
        return ['wps_pdf_widgets'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('PDF Shortcode', 'textdomain'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'pdf_shortcode',
            [
                'label'       => __('Enter Shortcode', 'textdomain'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '[WORDPRESS_PDF]',
                'default'     => '[WORDPRESS_PDF]',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings  = $this->get_settings_for_display();
        $shortcode = $settings['pdf_shortcode'];

        if ( ! empty( $shortcode ) ) {
            echo do_shortcode( $shortcode );
        } else {
            echo '<p style="color:red;">Please enter a valid shortcode.</p>';
        }
    }
}
