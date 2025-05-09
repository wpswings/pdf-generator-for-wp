<?php
use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Elementor_Widget_WPS_Canva extends Widget_Base {

    public function get_name() {
        return 'wps_canva_embed';
    }

    public function get_title() {
        return __('Canva Embed', 'textdomain');
    }

    public function get_icon() {
        return 'eicon-posts-ticker';
    }

    public function get_categories() {
        return ['wps_pdf_widgets'];
    }

    protected function _register_controls() {

        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Canva Settings', 'textdomain'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'canva_url',
            [
                'label'       => __('Canva URL', 'textdomain'),
                'type'        => Controls_Manager::TEXT,
                'placeholder' => __('https://www.canva.com/design/your-design-id/watch', 'textdomain'),
                'default'     => 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation',
                'label_block' => true,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $url = isset( $settings['canva_url'] ) ? esc_url( $settings['canva_url'] ) : '';

        if ( ! empty( $url ) ) {
            ?>
            <div style="position:relative;width:100%;height:0;padding-top:56.25%;overflow:hidden;border-radius:8px;box-shadow:0 2px 8px rgba(63,69,81,0.16);margin-top:1.6em;margin-bottom:0.9em;">
                <iframe
                    src="<?php echo esc_url($url); ?>?embed"
                    style="position:absolute;width:100%;height:100%;top:0;left:0;border:none;"
                    allowfullscreen>
                </iframe>
            </div>
            <p style="margin-top:10px;">
                Need help? 
                <a href="https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation"
                   target="_blank"
                   rel="noopener noreferrer"
                   style="color:#1DA1F2;">
                    How to use this block?
                </a>
            </p>
            <?php
        } else {
            echo '<p style="color:red;">Please provide a Canva URL in the widget settings.</p>';
        }
    }
}
