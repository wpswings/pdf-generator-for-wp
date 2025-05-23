<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Calendly extends Widget_Base
{

    public function get_name()
    {
        return 'wps_calendly_widget';
    }

    public function get_title()
    {
        return __('WPSwings Calendly Embed', 'pdf-generator-for-wp');
    }

    public function get_icon()
    {
        return 'eicon-social-icons';
    }

    public function get_categories()
    {
        return ['wps_pdf_widgets'];
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Calendly Settings', 'pdf-generator-for-wp'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'calendly_url',
            [
                'label'       => __('Calendly', 'pdf-generator-for-wp'),
                'type'        => Controls_Manager::URL,
                'placeholder' => __('https://calendly.com/your-link', 'pdf-generator-for-wp'),
                'default'     => [
                    'url' => '',
                ],
                'show_external' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $url = isset($settings['calendly_url']['url']) ? esc_url($settings['calendly_url']['url']) : '';

        if (! empty($url)) {
            echo do_shortcode('[wps_calendly url="' . $url . '"]');
        } else {
            echo '<p style="color:red;">Please set a Calendly URL in the widget settings.</p>';
        }
    }
}
