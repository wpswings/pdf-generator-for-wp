<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html field for general tab.
 *
 * @link       https://wpswings.com/
 * @since      3.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
global $pgfw_wps_pgfw_obj, $pgfw_wps_wpg_obj;
$pgfw_active_tab           = isset( $_GET['pgfw_tab'] ) ? sanitize_key( $_GET['pgfw_tab'] ) : 'pdf-generator-for-wp-layout-settings'; // phpcs:ignore WordPress.Security.NonceVerification
$pgfw_default_tabs      = $pgfw_wps_pgfw_obj->wps_pgfw_plug_layout_setting_sub_tabs_dummy();

$pgfw_template_settings_arr = apply_filters( 'wpg_tamplates_settings_array', array() );
?>
<main class="wps-main wps-bg-white wps-r-8 wps_pgfw_pro_tag">
	<nav class="wps-navbar">
		<ul class="wps-navbar__items">
			<?php
			if ( is_array( $pgfw_default_tabs ) && ! empty( $pgfw_default_tabs ) ) {

				foreach ( $pgfw_default_tabs as $pgfw_tab_key => $pgfw_default_tabs ) {

					$pgfw_tab_classes = 'wps-link ';

					if ( ! empty( $pgfw_active_tab ) && $pgfw_active_tab === $pgfw_tab_key ) {
						$pgfw_tab_classes .= 'active';
					}
					?>
					<li>
						<a id="<?php echo esc_attr( $pgfw_tab_key ); ?>" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ) . '&pgfw_tab=' . esc_attr( $pgfw_tab_key ) ); ?>" class="<?php echo esc_attr( $pgfw_tab_classes ); ?>"><?php echo esc_html( $pgfw_default_tabs['title'] ); ?></a>
					</li>
					<?php
				}
			}
			?>
		</ul>
	</nav>

<!--  template file for admin settings. -->
<section class="wps-section">
	<div>
		<form action="" method="POST" class="wps-pgfw-gen-section-form">
			<div class="wpg-admin-notice-custom"></div>
			<div class="pgfw-secion-wrap">
				<?php
				$custom_template_data             = get_option( 'wpg_custom_templates_list', array() );
				$pgfw_use_template_to_generate_pdf = get_option( 'wpg_use_template_to_generate_pdf' , array());
				$preview_output_href              = add_query_arg(
					array(
						'action'   => 'previewpdf',
						'template' => 'template1',
					),
					get_site_url()
				);
				?>
				<div class="wpg-adding-notice-for-custom-template">
					<?php
					esc_html_e(
						'To add custom template just click on the Create Templates icon, this will add three page each for header, body and footer with default layouts you can edit them by clicking on the edit link, it will redirect you to the editor, you can add snippets from PDF Snippets block, which is provided in the block section of the gutenberg editor and choose accordingly.',
						'pdf-generator-for-wp'
					);
					?>
				</div>
				<div class="wpg-adding-notice-for-custom-template">
					<?php
					esc_html_e(
						'To add post thumbnail on the PDF, just click on the body editing link, it will redirect you to editor, there you can add any image of desired size, that image will be replaced by the post thumbnail of the size you have just selected image.',
						'pdf-generator-for-wp'
					);
					?>
				</div>
				<div class="wpg-adding-notice-for-custom-template">
					<?php
					esc_html_e(
						'To set the content on the page, If you see any Issue with top placement of header, just visit PDF Settings/Header tab, from that setting page change the value of Header top placement, If you see any issue with the footer placement just visit PDF Settings/Footer tab change the value of Footer bottom placement, Also these changes need to be done in synchronisation with the setting at the PDF setting/ Body page for Page Margin, These will set the content on the PDF.',
						'pdf-generator-for-wp'
					);
					?>
				</div>
				<span class="wpg-add-custom-page-insertion"><?php esc_html_e( 'Create Templates', 'pdf-generator-for-wp' ); ?></span>
				<table class="wps-layout-table-bottom">
					<thead>
						<tr>
							<th><?php esc_html_e( 'Name', 'pdf-generator-for-wp' ); ?></th>
							<th><?php esc_html_e( 'For', 'pdf-generator-for-wp' ); ?></th>
							<th><?php esc_html_e( 'Status', 'pdf-generator-for-wp' ); ?></th>
							<th><?php esc_html_e( 'Updated', 'pdf-generator-for-wp' ); ?></th>
							<th><?php esc_html_e( 'Preview', 'pdf-generator-for-wp' ); ?></th>
							<th><?php esc_html_e( 'Template Editing Link', 'pdf-generator-for-wp' ); ?></th>
							<th><?php esc_html_e( 'Delete', 'pdf-generator-for-wp' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php esc_html_e( 'Template 1', 'pdf-generator-for-wp' ); ?></td>
							<td><?php esc_html_e( 'All Posts', 'pdf-generator-for-wp' ); ?></td>
							<td>
						<input type="checkbox" name="wpg_use_template_current_status[]" class="wpg_use_template_current_status" value="template1" <?php checked( in_array( 'template1', (array) $pgfw_use_template_to_generate_pdf ), true ); ?>>
								<span><?php esc_html_e( 'Activate', 'pdf-generator-for-wp' ); ?></span>
							</td>
							<td>
								<?php echo esc_html( get_option( 'date_format' ) ); ?>
							</td>
							<td>
								<a href="<?php echo esc_attr( $preview_output_href ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'pdf-generator-for-wp' ); ?></a>
							</td>
							<td>
								<span><?php esc_html_e( 'Not Found', 'pdf-generator-for-wp' ); ?><span>
							</td>
							<td>
								<span><?php esc_html_e( 'not allowed', 'pdf-generator-for-wp' ); ?></span>
							</td>
						</tr>
						<?php
						$translated_pdf_parts = array(
							'header' => __( 'Header', 'pdf-generator-for-wp' ),
							'body'   => __( 'Body', 'pdf-generator-for-wp' ),
							'footer' => __( 'Footer', 'pdf-generator-for-wp' ),
						);
						foreach ( $custom_template_data as $template => $template_description ) {
							$preview_output_href = add_query_arg(
								array(
									'action'   => 'previewpdf',
									'template' => $template,
								),
								get_site_url()
							);
							$i                   = 0;
							foreach ( $template_description as $template_for => $template_id ) {
								$availability = get_post( $template_id );
								$edit_link    = get_edit_post_link( $availability );
								?>
								<tr>
									<?php if ( 0 === $i ) { ?>
										<td rowspan="3"><?php echo esc_html( str_replace( 'customtemplate', __( 'Custom Template ', 'pdf-generator-for-wp' ), $template ) ); ?></td>
										<td rowspan="3">
										<span><select name="wpg_template_items[<?php echo esc_attr( $template ); ?>][]" class="wpg-select2" multiple style="width: 300px;">
										<?php
										$selected_items = get_option( 'wpg_template_items_' . $template, array() ); //need to get the selected items for this template.
										$post_types = get_post_types( array( 'public' => true ), 'objects' );

										foreach ( $post_types as $post_type ) {
											$posts = get_posts( array(
												'post_type'      => $post_type->name,
												'posts_per_page' => -1,
												'post_status'    => 'publish',
											) );

											if ( ! empty( $posts ) ) {
												echo '<optgroup label="' . esc_html( $post_type->labels->singular_name ) . '">';
												foreach ( $posts as $post ) {
													$selected = in_array( $post->ID, $selected_items ) ? 'selected' : '';
													echo '<option value="' . esc_attr( $post->ID ) . '" ' . $selected . '>' . esc_html( $post->post_title ) . '</option>';
												}
												echo '</optgroup>';
											}
										}
										?>
									</select>
									</span>
										</td>
										<td rowspan="3">


											<input type="checkbox" name="wpg_use_template_current_status" class="wpg_use_template_current_status" value="<?php echo esc_html( $template ); ?>" <?php checked( $pgfw_use_template_to_generate_pdf, $template ); ?>>




											<span><?php esc_html_e( 'Activate', 'pdf-generator-for-wp' ); ?></span>
										</td>
										<td rowspan="3"><?php echo esc_html( ( $availability ) ? get_the_modified_date( '', $template_id ) : __( 'Not Found', 'pdf-generator-for-wp' ) ); ?></td>
									<?php } ?>
									<?php if ( 0 === $i ) { ?>
										<td rowspan="3">
											<a href="<?php echo esc_attr( $preview_output_href ); ?>" target="_blank"><?php esc_html_e( 'Preview', 'pdf-generator-for-wp' ); ?></a>
										</td>
									<?php } ?>
									<?php if ( $edit_link ) { ?>
										<td class="<?php echo ( 2 !== $i ) ? esc_attr( 'wpg-no-border-table' ) : 'wpg-no-border-top-table'; ?>"><a href="<?php echo esc_attr( $edit_link ); ?>" target="_blank"><?php echo esc_html( isset( $translated_pdf_parts[ $template_for ] ) ? $translated_pdf_parts[ $template_for ] : $template_for ); ?></a></td>
									<?php } else { ?>
										<td>
											<span><?php esc_html_e( 'Not Found', 'pdf-generator-for-wp' ); ?><span>
										</td>
										<?php
									}
									if ( 0 === $i ) {
										?>
										<td rowspan="3">
											<button data-template-name="<?php echo esc_html( $template ); ?>" class="wpg-delete-template"><?php esc_html_e( 'Delete', 'pdf-generator-for-wp' ); ?></button>
										</td>
									<?php } ?>
								</tr>
								<?php
								$i++;
							}
						}
						?>
					</tbody>
				</table>
				<div class="mpg-submit-btn-wrap">
					<button class="wpg-submit-internal-page-setting"><?php esc_html_e( 'Save Setting', 'pdf-generator-for-wp' ); ?></button>
				</div>
			</div>
		</form>
	</div>
</section>
<?php
echo esc_html__( 'Add [QR_CODE] shortcode anywhere on your Custom template to display QR Code on pdf.', 'pdf-generator-for-wp' );
?>
