<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Strava extends Widget_Base
{

    public function get_name()
    {
        return 'wps_strava_widget';
    }

    public function get_title()
    {
        return __('WPSwings Strava Embed', 'pdf-generator-for-wp');
    }

    public function get_icon()
    {
        return 'eicon-calendar'; // You can change this icon if you prefer a different one
    }

    public function get_categories()
    {
        return ['wps_pdf_widgets']; // You can change this to a different category if needed
    }

    protected function _register_controls()
    {
        // Add widget controls here
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Strava Settings', 'pdf-generator-for-wp'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Control for Strava Activity ID input field
        $this->add_control(
            'strava_id',
            [
                'label'       => __('Strava Activity ID', 'pdf-generator-for-wp'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('e.g. 123456789', 'pdf-generator-for-wp'),
                'default'     => '',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        // Get the widget settings
        $settings = $this->get_settings_for_display();
        $id = isset($settings['strava_id']) ? sanitize_text_field($settings['strava_id']) : '';

        // Check if the ID is set and render the shortcode
        if (! empty($id)) {
            echo do_shortcode('[wps_strava id="' . $id . '"]');
        } else {
            echo '<p style="color:red;">Please enter a Strava Activity ID in the widget settings.</p>';
        }
    }
}
