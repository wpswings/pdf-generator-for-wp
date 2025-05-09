<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_Widget_WPS_Google_Elements extends Widget_Base {

    public function get_name() {
        return 'wps_google_embed';
    }

    public function get_title() {
        return __('WPSwings Google Embed', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-google-maps';
    }

    public function get_categories() {
        return ['wps_pdf_widgets'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Google Embed Settings', 'textdomain'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'google_url',
            [
                'label' => __('Google Service Embed URL', 'textdomain'),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __('Paste Google Docs, Sheets, Slides, Forms, etc. URL', 'textdomain'),
                'default' => '',
            ]
        );

        $this->end_controls_section();
    }

    private function get_embed_url($url) {
        if (!$url) return '';

        if (strpos($url, 'docs.google.com/presentation') !== false) {
            $embedUrl = new \WP_Http();
            $urlParts = wp_parse_url($url);
            $embedPath = str_replace('/pub', '/embed', $urlParts['path'] ?? '');
            return 'https://docs.google.com' . $embedPath;
        }

        if (strpos($url, 'docs.google.com/document') !== false) {
            return str_replace('/edit', '/preview', $url);
        }

        if (strpos($url, 'docs.google.com/spreadsheets') !== false) {
            return str_replace('/edit', '/pubhtml', $url);
        }

        if (strpos($url, 'docs.google.com/forms') !== false) {
            return str_replace('/viewform', '/viewform?embedded=true', $url);
        }

        if (strpos($url, 'www.google.com/maps') !== false) {
            preg_match('/@([-.\d]+),([-.\d]+)/', $url, $match);
            $lat = $match[1] ?? '';
            $lng = $match[2] ?? '';

            preg_match('/place\/([^/@]+)/', $url, $placeMatch);
            $place = isset($placeMatch[1]) ? str_replace('+', ' ', $placeMatch[1]) : '';

            return "https://maps.google.com/maps?hl=en&ie=UTF8&ll={$lat},{$lng}&spn={$lat},{$lng}&q={$place}&t=m&z=17&output=embed&iwloc";
        }

        if (strpos($url, 'calendar.google.com/calendar') !== false) {
            return $url;
        }

        if (strpos($url, 'www.youtube.com/watch') !== false) {
            return str_replace('watch?v=', 'embed/', $url);
        }

        return $url;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $url = $settings['google_url'];
        $embedUrl = esc_url($this->get_embed_url($url));

        if (strpos($url, 'docs.google.com/drawings') !== false) {
            echo "<img src='{$embedUrl}' width='800' height='500' style='border:1px solid #ddd;' />";
        } elseif ($embedUrl) {
            echo "<iframe src='{$embedUrl}' width='800' height='500' allowfullscreen style='border:1px solid #ddd;'></iframe>";
        } else {
            echo "<p style='color:red;'>Invalid or unsupported Google embed URL.</p>";
        }
    }
}
