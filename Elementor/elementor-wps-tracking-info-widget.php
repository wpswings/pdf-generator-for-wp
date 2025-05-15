<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Tracking_Info extends Widget_Base {

    public function get_name() {
        return 'wps_tracking_info_pdf';
    }

    public function get_title() {
        return __('WPSwings Tracking Info', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-document';
    }

    public function get_categories() {
        return ['wps_pdf_widgets'];
    }

   protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Tracking Info Settings', 'textdomain'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'order_id',
            [
                'label'       => __('Order ID', 'textdomain'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('Enter Order ID', 'textdomain'),
                'default'     => '',
            ]
        );

        $this->add_control(
            'order_align',
            [
                'label'       => __('Order Alignment', 'textdomain'),
                'type'        => Controls_Manager::SELECT,
                'options'     => [
                    'left'   => __('Left', 'textdomain'),
                    'center' => __('Center', 'textdomain'),
                    'right'  => __('Right', 'textdomain'),
                ],
                'default'     => 'left',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id    = esc_attr( $settings['order_id'] );
        $alig = esc_attr( $settings['order_align'] );

        if ( ! empty( $id ) ) {
            echo do_shortcode( '[wps_tracking_info order_id="' . $id . '" align="' . $alig .']' );
        } else {
            echo '<p style="color:red;">Please provide an Order ID in the widget settings.</p>';
        }
    }
}
