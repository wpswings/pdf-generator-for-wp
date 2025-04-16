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
/**
 * Description: Embeds a source Meeting.
 */
function wps_embed_sources_page() {
	 $sources = array( 'linkedin', 'loom', 'twitch', 'ai_chatbot', 'canva', 'reddit', 'google_elements', 'calendly', 'strava', 'rss_feed', 'x', 'pdf_embed' );
	?>
	<div class="wrap">
		<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;">
			<h1 style="margin:0;"><?php echo esc_html__( 'Embed Sources', 'pdf-generator-for-wp' ); ?></h1>
			<div id="wps-toast-msg">✅ <?php echo esc_html( 'Setting saved!', 'pdf-generator-for-wp' ); ?></div>
		</div>
		<div class="wps-embed-grid">
			<?php
			foreach ( $sources as $source ) :
				$value = get_option( "wps_embed_source_{$source}", 'off' );
				$label = ucfirst( str_replace( '_', ' ', $source ) );
				$pro_sources = array( 'rss_feed', 'ai_chatbot' , 'pdf_embed');
				$is_pro = in_array( $source, $pro_sources, true ); // you can customize this condition.
				$is_disabled = $is_pro && ( ! is_plugin_active( 'wordpress-pdf-generator/wordpress-pdf-generator.php' ) );
				?>
				<div class="wps-embed-item <?php echo $is_disabled ? 'disabled-item' : ''; ?>">
					<?php if ( $is_disabled ) : ?>
						<div class="wps-pro-strip-embed">PRO</div>
					<?php endif; ?>
					<img width="30" height="30" src=<?php echo esc_url( PDF_GENERATOR_FOR_WP_DIR_URL . "admin/src/images/{$source}.png"); ?> alt="linkedin"/>
					<span style="display: block; margin-bottom: 10px; font-weight: bold; font-size: 14px;">
						<?php echo esc_html( $label ); ?>
					</span>

					<div style="display: flex; justify-content: center; align-items: center; gap: 8px;">
						<?php if('pdf_embed' === $source){ ?>
							<span
							title="<?php echo esc_html__("Enable this to " .$label. " on your posts, pages, or custom post types", 'pdf-generator-for-wp') ?>"
							style="cursor: help; color: #888; font-size: 13px;">?</span>
<?php } else {?>
	<span
							title="<?php echo esc_html__("Enable this to embed " .$label. " on your posts, pages, or custom post types", 'pdf-generator-for-wp') ?>"
							style="cursor: help; color: #888; font-size: 13px;">?</span>
<?php } ?>


						<label class="wps-switch <?php echo $is_disabled ? 'wps-switch-disable' : ''; ?>">
							<input type="checkbox" data-source="<?php echo esc_attr( $source ); ?>" <?php echo 'on' === $value ? 'checked' : ''; ?>>
							<span class="slider"></span>
						</label>
					</div>
				</div>
			<?php endforeach; ?>
		</div>
		<p style="margin-top: 15px; font-style: italic; color: #666;">
			🔹<?php echo esc_html__( 'Prefer a quick guide ? ', 'pdf-generator-for-wp' ); ?><a href="https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-doc&utm_medium=referral&utm_campaign=documentation" target="_blank"><?php echo esc_html__( 'Check out our documentation to understand how these settings work.', 'pdf-generator-for-wp' ); ?></a>
		</p>
	</div>

	<style>
		.disabled-item {
			opacity: 0.5;

		}

		.wps-switch-disable {
			pointer-events: none;
			user-select: none;
		}

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
			position: relative;
			background: #fff;
			border: 1px solid #ddd;
			padding: 20px;
			border-radius: 8px;
			overflow: hidden;
		}

		.wps-pro-strip-embed {
			position: absolute;
			top: 8px;
			right: -40px;
			background: #0aa000;
			color: #fff;
			font-size: 12px;
			font-weight: bold;
			padding: 4px 40px;
			transform: rotate(45deg);
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
			z-index: 2;
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
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
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

		input:checked+.slider {
			background-color: #2196F3;
		}

		input:checked+.slider:before {
			transform: translateX(26px);
		}
	</style>
	</script>
	<?php
}
