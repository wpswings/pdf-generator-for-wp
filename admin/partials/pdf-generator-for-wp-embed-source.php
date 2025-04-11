<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for overview tab.
 *
 * @link       https://wpswings.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
wps_embed_sources_page();
function wps_embed_sources_page() {
    $sources = ['linkedin', 'loom', 'twitch', 'ai_chatbot', 'canva', 'reddit' ,'google' , 'calendly' ,'strava' , 'rss_feed' , 'x' , 'view_pdf' ];
    ?>
    <div class="wrap">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
        <h1 style="margin:0;"><?php echo esc_html__('Embed Sources','pdf-generator-for-wp');?></h1>
        <div id="wps-toast-msg">✅ Setting saved!</div></div>
        <div class="wps-embed-grid">
            <?php foreach ($sources as $source): 
                $value = get_option("wps_embed_source_{$source}", 'off');
                $label = ucfirst(str_replace('_', ' ', $source));
            ?>
                <div class="wps-embed-item">
                <!-- <img width="30" height="30" src="https://img.icons8.com/fluency/48/linkedin.png" alt="linkedin"/> -->
                <span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px;">
                <?= esc_html($label) ?>
            </span>

            <div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
                <span 
                    title="Enable this to embed <?= esc_attr($label) ?> on your posts, pages, or custom post types" 
                    style="cursor: help; color: #888; font-size: 13px;"
                >?</span>

                <label class="wps-switch">
                    <input type="checkbox" data-source="<?= esc_attr($source) ?>" <?= $value === 'on' ? 'checked' : '' ?>>
                    <span class="slider"></span>
                </label>
            </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <style>
        #wps-toast-msg {
            background-color: #46b450;
    color: white;
    padding: 6px 12px;
    border-radius: 4px;
    display: none;
    font-size: 13px;
    font-weight: 500;
        }
        .wps-embed-grid {
            display: grid;
            grid-template-columns: repeat(6, 1fr);
            gap: 20px;
			margin-top: 13px;
        }

        .wps-embed-item {
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .wps-embed-item span {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            font-size: 14px;
        }

        .wps-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 33px;
        }
        .wps-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0;
            right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 24px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 4px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #2196F3;
        }
        input:checked + .slider:before {
            transform: translateX(26px);
        }
    </style>
    </script>
    <?php
}
