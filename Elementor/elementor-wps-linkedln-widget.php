<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Linkedln extends Widget_Base {

    public function get_name() {
        return 'wps_linkedin_widget';
    }

    public function get_title() {
        return __('WPSwings LinkedIn Embed', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-social-icons';
    }

    public function get_categories() {
        return ['wps_pdf_widgets']; // Keep your custom category
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('LinkedIn Settings', 'textdomain'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'linkedin_post_id',
            [
                'label'       => __('LinkedIn Post ID', 'textdomain'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => '7307328960277684224',
                'default'     => '',
            ]
        );
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $post_id = $settings['linkedin_post_id'];
        $help_url = 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation';

        if ( ! empty( $post_id ) ) {
            $embed_url = esc_url( "https://www.linkedin.com/embed/feed/update/urn:li:share:$post_id" );

            echo '<iframe src="' . $embed_url . '" width="600" height="400" allowfullscreen style="border: 1px solid #ddd;"></iframe>';
        } else {
            echo '<p style="color:red;">Please enter a LinkedIn Post ID in the widget settings.</p>';
        }

    }
}
