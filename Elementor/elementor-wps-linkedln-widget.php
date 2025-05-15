<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Linkedln extends Widget_Base {

    public function get_name() {
        return 'wps_calendly_widget';
    }

    public function get_title() {
        return __('WPSwings Linkedln Embed', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-linkedln';
    }

    public function get_categories() {
        return ['wps_pdf_widgets'];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Linkedln Settings', 'textdomain'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'linkedln_url',
            [
                'label'       => __('Linkedln', 'textdomain'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://calendly.com/your-link', 'textdomain'),
                'default'     => [
                    'url' => '',
                ],
                'show_external' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $url = isset( $settings['linkedlm']['url'] ) ? esc_url( $settings['calendly_url']['url'] ) : '';

        if ( ! empty( $url ) ) {
            echo do_shortcode( '[wps_calendly url="' . $url . '"]' );
        } else {
            echo '<p style="color:red;">Please set a Calendly URL in the widget settings.</p>';
        }
    }
}
