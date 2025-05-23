<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Single_Image extends Widget_Base
{

    public function get_name()
    {
        return 'wps_single_image_widget';
    }

    public function get_title()
    {
        return __('WPSwings Single Image', 'pdf-generator-for-wp');
    }

    public function get_icon()
    {
        return 'eicon-image';
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
                'label' => __('Image Settings', 'pdf-generator-for-wp'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'image_id',
            [
                'label'       => __('Image ID', 'pdf-generator-for-wp'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('Enter Image ID', 'pdf-generator-for-wp'),
                'default'     => '',
            ]
        );

        $this->add_control(
            'image_width',
            [
                'label'       => __('Width (px)', 'pdf-generator-for-wp'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('50', 'pdf-generator-for-wp'),
                'default'     => '50',
            ]
        );

        $this->add_control(
            'image_height',
            [
                'label'       => __('Height (px)', 'pdf-generator-for-wp'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('50', 'pdf-generator-for-wp'),
                'default'     => '50',
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id    = esc_attr($settings['image_id']);
        $width = esc_attr($settings['image_width']);
        $height = esc_attr($settings['image_height']);

        if (! empty($id)) {
            echo do_shortcode('[WPS_SINGLE_IMAGE id="' . $id . '" width="' . $width . '" height="' . $height . '"]');
        } else {
            echo '<p style="color:red;">Please provide an Image ID in the widget settings.</p>';
        }
    }
}
