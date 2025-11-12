<?php
/**
 * Elementor Widget: WPSwings Reddit Embed
 *
 * @package PDF_Generator_For_WP
 */

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Elementor Widget: WPSwings Twitch Stream with Chat.
 *
 * This widget allows embedding a Twitch video stream along with the optional live chat
 * inside Elementor pages. Users can configure the Twitch channel, dimensions for video
 * and chat, and toggle the chat display.
 *
 * @package PDF_Generator_For_WP
 * @since 1.0.0
 */
class Elementor_Widget_WPS_Twitch extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * @return string Widget slug/name.
	 */
	public function get_name() {
		return 'wps_twitch_stream_widget';
	}

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WPSwings Twitch Stream with Chat', 'pdf-generator-for-wp' );
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Icon class.
	 */
	public function get_icon() {
		return 'eicon-video-playlist';
	}

	/**
	 * Get widget categories.
	 *
	 * @return array List of categories.
	 */
	public function get_categories() {
		return array( 'wps_pdf_widgets' );
	}

	/**
	 * Register widget controls.
	 *
	 * Adds controls for Twitch channel, stream/chat dimensions, and chat toggle.
	 *
	 * @return void
	 */
	protected function _register_controls() {
		$this->start_controls_section(
			'content_section',
			array(
				'label' => __( 'Twitch Stream Settings', 'pdf-generator-for-wp' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			)
		);

		$this->add_control(
			'channel',
			array(
				'label'       => __( 'Twitch Channel', 'pdf-generator-for-wp' ),
				'type'        => Controls_Manager::TEXT,
				'placeholder' => __( 'Enter Twitch channel name', 'pdf-generator-for-wp' ),
				'default'     => '',
			)
		);

		$this->add_control(
			'width',
			array(
				'label'   => __( 'Stream Width', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '100%',
			)
		);

		$this->add_control(
			'height',
			array(
				'label'   => __( 'Stream Height', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '480',
			)
		);

		$this->add_control(
			'chat_width',
			array(
				'label'   => __( 'Chat Width', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '100%',
			)
		);

		$this->add_control(
			'chat_height',
			array(
				'label'   => __( 'Chat Height', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::TEXT,
				'default' => '480',
			)
		);

		$this->add_control(
			'show_chat',
			array(
				'label'   => __( 'Show Chat?', 'pdf-generator-for-wp' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'yes',
				'options' => array(
					'yes' => __( 'Yes', 'pdf-generator-for-wp' ),
					'no'  => __( 'No', 'pdf-generator-for-wp' ),
				),
			)
		);

		$this->end_controls_section();
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Outputs the Twitch player iframe and optional chat iframe with user settings.
	 *
	 * @return void
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$channel    = sanitize_text_field( $settings['channel'] ?? '' );
		$width      = sanitize_text_field( $settings['width'] ?? '100%' );
		$height     = sanitize_text_field( $settings['height'] ?? '480' );
		$chat_width = sanitize_text_field( $settings['chat_width'] ?? '100%' );
		$chat_height = sanitize_text_field( $settings['chat_height'] ?? '480' );
		$show_chat  = $settings['show_chat'] ?? 'yes';

		if ( '' === $channel ) {
			echo '<p style="color:red;">' . esc_html__( 'Please provide a Twitch channel name.', 'pdf-generator-for-wp' ) . '</p>';
			return;
		}

		$host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';
		$parent = esc_attr( $host );

		echo '<div class="wps-twitch-embed" style="display:flex;flex-wrap:wrap;gap:20px;">';

		echo '<iframe
            src="https://player.twitch.tv/?channel=' . esc_attr( $channel ) . '&parent=' . esc_attr( $parent ) . '"
            frameborder="0"
            allowfullscreen="true"
            scrolling="no"
            height="' . esc_attr( $height ) . '"
            width="' . esc_attr( $width ) . '">
        </iframe>';

		if ( 'yes' === $show_chat ) {
			echo '<iframe
                src="https://www.twitch.tv/embed/' . esc_attr( $channel ) . '/chat?parent=' . esc_attr( $parent ) . '"
                frameborder="0"
                scrolling="no"
                height="' . esc_attr( $chat_height ) . '"
                width="' . esc_attr( $chat_width ) . '">
            </iframe>';
		}

		echo '</div>';
	}
}
