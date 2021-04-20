<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for PDF upload tab.
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
$pgfw_pdf_upload_settings = apply_filters( 'pgfw_pdf_upload_fields_settings_array', array() );
?>
<!--  template file for admin settings. -->
<form action="" method="POST" class="mwb-pgfw-gen-section-form">
	<div class="pgfw-section-wrap">
		<div class="pgfw-upload-poster-notification"><?php esc_html_e( 'Upload posters from here and you will get shortcode which you can use anywhere on your post or page to give access to download these posters.', 'pdf-generator-for-wordpress' ); ?></div>
		<?php
		wp_nonce_field( 'nonce_settings_save', 'pgfw_nonce_field' );
		$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_pdf_upload_settings );
		$pgfw_pdf_upload_settings = get_option( 'pgfw_pdf_upload_save_settings', array() );
		$pgfw_poster_doc          = array_key_exists( 'sub_pgfw_poster_image_upload', $pgfw_pdf_upload_settings ) ? $pgfw_pdf_upload_settings['sub_pgfw_poster_image_upload'] : '';
		// poster images names and shortcodes.
		if ( '' !== $pgfw_poster_doc ) {
			$pgfw_poster_image = json_decode( $pgfw_poster_doc, true );
			if ( is_array( $pgfw_poster_image ) && count( $pgfw_poster_image ) > 0 ) {
				?>
				<div><?php esc_html_e( 'You can add these shortcodes to download posters anywhere on the page/post.', 'pdf-generator-for-wordpress' ); ?></div>
				<br/>
				<table id="pgfw_poster_shortcode_listing_table">
					<thead>
						<tr>
							<th><?php esc_html_e( 'File Name', 'pdf-generator-for-wordpress' ); ?></th>
							<th><?php esc_html_e( 'ShortCode', 'pdf-generator-for-wordpress' ); ?></th>
							<th><?php esc_html_e( 'Action', 'pdf-generator-for-wordpress' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php
						foreach ( $pgfw_poster_image as $media_id ) {
							$media_title = get_the_title( $media_id );
							if ( $media_title ) {
								?>
								<tr>
									<td>
										<?php echo esc_html( $media_title ); ?>
									</td>
									<td>
										<?php echo esc_html( '[PGFW_DOWNLOAD_POSTER id=' . $media_id . ']' ); ?>
									</td>
									<td>
										<button data-media-id="<?php echo esc_html( $media_id ); ?>" class="pgfw-delete-poster-form-table"><?php esc_html_e( 'Delete', 'pdf-generator-for-wordpress' ); ?></button>
									</td>
								</tr>
								<?php
							}
						}
						?>
					</tbody>
				</table>
				<?php
			}
		}
		?>
	</div>
</form>
