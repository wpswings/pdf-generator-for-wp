<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://makewebbetter.com/
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wordpress
 * @subpackage Pdf_Generator_For_Wordpress/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pgfw_mwb_pgfw_obj;
$pgfw_advanced_settings = apply_filters( 'pgfw_advanced_settings_array', array() );
?>
<!--  advanced file for admin settings. -->
<form action="" method="POST" class="mwb-pgfw-gen-section-form">
	<div class="pgfw-section-wrap">
		<?php
		wp_nonce_field( 'nonce_settings_save', 'pgfw_nonce_field' );
		$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_advanced_settings );
		$pgfw_advanced_settings = get_option( 'pgfw_advanced_save_settings', array() );
		$poster_images          = array_key_exists( 'sub_pgfw_poster_image_upload', $pgfw_advanced_settings ) ? $pgfw_advanced_settings['sub_pgfw_poster_image_upload'] : '';
		// poster images names and shortcodes.
		if ( '' !== $poster_images ) {
			?>
			<div><?php esc_html_e( 'You can add these shortcodes to download posters anywhere on the page/post.', 'pdf-generator-for-wordpress' ); ?></div>
			<br/>
			<table style="border-collapse: collapse;">
				<thead>
					<tr>
						<th style="border:1px solid gray;padding:5px 10px;"><?php esc_html_e( 'File Name', 'pdf-generator-for-wordpress' ); ?></th>
						<th style="border:1px solid gray;padding:5px 10px;"><?php esc_html_e( 'ShortCode', 'pdf-generator-for-wordpress' ); ?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<?php
						$pgfw_poster_image = json_decode( $poster_images, true );
						$i                 = 0;
						foreach ( $pgfw_poster_image as $file_name => $file_link ) {
							?>
							<tr>
								<td style="border:1px solid gray;padding:5px 10px;">
									<?php echo esc_html( $file_name ); ?>
								</td>
								<td style="border:1px solid gray;padding:5px 10px;">
									<?php echo esc_html( '[PGFW_DOWNLOAD_POSTER id=' . $i . ']' ); ?>
								</td>
							</tr>
							<?php
							$i++;
						}
						?>
					</tr>
				</tbody>
			</table>
			<?php
		}
		?>
	</div>
</form>
