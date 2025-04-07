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
?>

<div class="wrap">
		<h1 style="margin-bottom: 20px;">📌<?php echo esc_html__( 'Available Shortcodes', 'pdf-generator-for-wp' ); ?></h1>

		<table class="widefat striped" style="background: #fff; border-radius: 8px; box-shadow: 0px 2px 8px rgba(0,0,0,0.1);">
			<thead>
				<tr>
					<th style="width: 25%;"><?php echo esc_html__( 'Shortcode', 'pdf-generator-for-wp' ); ?></th>
					<th style="width: 50%;"><?php echo esc_html__( 'Description', 'pdf-generator-for-wp' ); ?></th>
					<th style="width: 25%;"><?php echo esc_html__( 'Example Usage', 'pdf-generator-for-wp' ); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[WORDPRESS_PDF]"><code>[WORDPRESS_PDF] </code></span></td>
					<td><?php echo esc_html__( 'Add [WORDPRESS_PDF] shortcode anywhere on your page or posts to display the PDF-generating icon', 'pdf-generator-for-wp' ); ?></td>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[WORDPRESS_PDF]"><code>[WORDPRESS_PDF] </code></span></td>
				</tr>
				<tr>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[WPS_POST_METAVALUE key='wps_field' {shortcode-postid}]"><code>[WPS_POST_METAVALUE key='wps_field' {shortcode-postid}]</code></span></td>
					<td><?php echo esc_html__( 'The selected meta fields will be added to the PDF, but all at the same place ( one after the other ), If you want the individual meta fields to be added at your desired place you can use this shortcode just replace the wps_field with your desired meta key and place at your desired place in the custom template it will fetch the respective meta field data. (PRO)', 'pdf-generator-for-wp' ); ?></td>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[WPS_POST_METAVALUE key='post_content' {shortcode-postid}]"><code>[WPS_POST_METAVALUE key='post_content' {shortcode-postid}]</code></span></td>
				</tr>

				<tr>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[QR_CODE]"><code>[QR_CODE]</code></span></td>
					<td><?php echo esc_html__( 'Add [QR_CODE] shortcode anywhere on your Custom template to display QR Code on pdf. (PRO)', 'pdf-generator-for-wp' ); ?></td>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[QR_CODE]"><code>[QR_CODE]</code></span></td>
				</tr>


				<tr>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[WPS_SINGLE_IMAGE id = “any image id” Height = 100 widths = 200 ]"><code>[WPS_SINGLE_IMAGE id = “any image id” Height = 100 widths = 200 ]</code></span></td>
					<td><?php echo esc_html__( 'Use [WPS_SINGLE_IMAGE id = “any image id” Height = 100 widths = 200 ] To add an image to the pdf via image id. (PRO)', 'pdf-generator-for-wp' ); ?></td>
					<td><span class="wps-pgfw-shortcodes-copy-shortcode" data-shortcode="[WPS_SINGLE_IMAGE id = “21” Height = 100 widths = 200 ]"><code>[WPS_SINGLE_IMAGE id = “21” Height = 100 widths = 200 ]</code></span></td>
				</tr>
			</tbody>
		</table>

		<p style="margin-top: 15px; font-style: italic; color: #666;">
			🔹<?php echo esc_html__( 'Click any shortcode to copy it.', 'pdf-generator-for-wp' ); ?>
		</p>
	</div>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			const shortcodes = document.querySelectorAll(".wps-pgfw-shortcodes-copy-shortcode");

			shortcodes.forEach(shortcode => {
				shortcode.style.cursor = "pointer";
				shortcode.style.position = "relative";

				shortcode.addEventListener("click", function() {
					const text = this.getAttribute("data-shortcode");
					navigator.clipboard.writeText(text).then(() => {
						let tooltip = document.createElement("span");
						tooltip.textContent = "Copied!";
						tooltip.style.position = "absolute";
						tooltip.style.top = "-25px";
						tooltip.style.left = "50%";
						tooltip.style.transform = "translateX(-50%)";
						tooltip.style.background = "#28a745";
						tooltip.style.color = "#fff";
						tooltip.style.padding = "5px 10px";
						tooltip.style.borderRadius = "5px";
						tooltip.style.fontSize = "12px";
						tooltip.style.boxShadow = "0px 2px 5px rgba(0,0,0,0.2)";
						tooltip.style.opacity = "0.9";
						tooltip.style.transition = "opacity 0.5s";
						this.appendChild(tooltip);

						setTimeout(() => {
							tooltip.style.opacity = "0";
							setTimeout(() => tooltip.remove(), 500);
						}, 1000);
					});
				});
			});
		});
	</script>
