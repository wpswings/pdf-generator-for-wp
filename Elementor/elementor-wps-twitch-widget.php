<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if (! defined('ABSPATH')) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Twitch extends Widget_Base
{

    public function get_name()
    {
        return 'wps_twitch_stream_widget';
    }

    public function get_title()
    {
        return __('WPSwings Twitch Stream with Chat', 'pdf-generator-for-wp');
    }

    public function get_icon()
    {
        return 'eicon-video-playlist'; // You can change this to another icon
    }

    public function get_categories()
    {
        return ['wps_pdf_widgets']; // You can change this to a different category if needed
    }

    protected function _register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Twitch Stream Settings', 'pdf-generator-for-wp'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Control for Twitch channel name
        $this->add_control(
            'channel',
            [
                'label'       => __('Twitch Channel', 'pdf-generator-for-wp'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('Enter Twitch channel name', 'pdf-generator-for-wp'),
                'default'     => '',
            ]
        );

        // Control for video stream width
        $this->add_control(
            'width',
            [
                'label'   => __('Stream Width', 'pdf-generator-for-wp'),
                'type'    => Controls_Manager::TEXT,
                'default' => '100%',
            ]
        );

        // Control for video stream height
        $this->add_control(
            'height',
            [
                'label'   => __('Stream Height', 'pdf-generator-for-wp'),
                'type'    => Controls_Manager::TEXT,
                'default' => '480',
            ]
        );

        // Control for chat width
        $this->add_control(
            'chat_width',
            [
                'label'   => __('Chat Width', 'pdf-generator-for-wp'),
                'type'    => Controls_Manager::TEXT,
                'default' => '100%',
            ]
        );

        // Control for chat height
        $this->add_control(
            'chat_height',
            [
                'label'   => __('Chat Height', 'pdf-generator-for-wp'),
                'type'    => Controls_Manager::TEXT,
                'default' => '480',
            ]
        );

        // Control for showing chat
        $this->add_control(
            'show_chat',
            [
                'label'   => __('Show Chat?', 'pdf-generator-for-wp'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'yes',
                'options' => [
                    'yes' => __('Yes', 'pdf-generator-for-wp'),
                    'no'  => __('No', 'pdf-generator-for-wp'),
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();

        $channel = isset($settings['channel']) ? sanitize_text_field($settings['channel']) : '';
        $width = isset($settings['width']) ? sanitize_text_field($settings['width']) : '100%';
        $height = isset($settings['height']) ? sanitize_text_field($settings['height']) : '480';
        $chat_width = isset($settings['chat_width']) ? sanitize_text_field($settings['chat_width']) : '100%';
        $chat_height = isset($settings['chat_height']) ? sanitize_text_field($settings['chat_height']) : '480';
        $show_chat = isset($settings['show_chat']) ? $settings['show_chat'] : 'yes';

        // Return error if no channel provided
        if (empty($channel)) {
            echo '<p>Please provide a Twitch channel name.</p>';
            return;
        }

        // Set the parent domain (required by Twitch embed policy)
        $host = isset($_SERVER['HTTP_HOST']) ? sanitize_text_field(wp_unslash($_SERVER['HTTP_HOST'])) : '';
        $parent = esc_attr($host);

        // Start building the output
        $output = '<div class="wps-twitch-embed" style="display: flex; flex-wrap: wrap; gap: 20px;">';

        // Twitch video stream
        $output .= '<iframe
            src="https://player.twitch.tv/?channel=' . esc_attr($channel) . '&parent=' . $parent . '"
            frameborder="0"
            allowfullscreen="true"
            scrolling="no"
            height="' . esc_attr($height) . '"
            width="' . esc_attr($width) . '">
        </iframe>';

        // Twitch live chat (optional)
        if ('yes' === $show_chat) {
            $output .= '<iframe
                src="https://www.twitch.tv/embed/' . esc_attr($channel) . '/chat?parent=' . $parent . '"
                frameborder="0"
                scrolling="no"
                height="' . esc_attr($chat_height) . '"
                width="' . esc_attr($chat_width) . '">
            </iframe>';
        }

        $output .= '</div>';

        echo $output;
    }
}
