<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the html for the drag & drop PDF Builder tab.
 *
 * @link       https://wpswings.com/
 * @since      1.6.6
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$pgfw_plugin_admin = new Pdf_Generator_For_Wp_Admin( 'pdf-generator-for-wp', PDF_GENERATOR_FOR_WP_VERSION );
$pgfw_builder_data = $pgfw_plugin_admin->pgfw_admin_pdf_builder_data();
?>
<!-- template file for the drag & drop PDF Builder. -->
<div class="pgfw-secion-wrap">
	<div id="pgfw-pdf-builder-app" class="pgfw-pdf-builder">

		<div class="pgfw-pdf-builder-header">
			<div class="pgfw-pdf-builder-header__title">
				<h2><?php esc_html_e( 'PDF Builder', 'pdf-generator-for-wp' ); ?></h2>
				<p class="description"><?php esc_html_e( 'Design a layout by dragging Text, Image, Meta Field and Rectangle blocks onto the canvas, one layout per post type. Start from a ready-made template below, or build from scratch.', 'pdf-generator-for-wp' ); ?></p>
			</div>
			<label class="pgfw-pdf-builder-toggle">
				<input type="checkbox" id="pgfw-pdf-builder-enable" <?php checked( 'yes', $pgfw_builder_data['enable'] ); ?> />
				<span class="pgfw-pdf-builder-toggle__slider"></span>
				<span class="pgfw-pdf-builder-toggle__label"><?php esc_html_e( 'Enable PDF Builder', 'pdf-generator-for-wp' ); ?></span>
			</label>
		</div>

		<div class="pgfw-pdf-builder-toolbar">
			<label class="pgfw-pdf-builder-toolbar__item">
				<span><?php esc_html_e( 'Post Type', 'pdf-generator-for-wp' ); ?></span>
				<select id="pgfw-pdf-builder-post-type">
					<?php foreach ( $pgfw_builder_data['post_types'] as $pgfw_post_type_key => $pgfw_post_type_label ) { ?>
						<option value="<?php echo esc_attr( $pgfw_post_type_key ); ?>"><?php echo esc_html( $pgfw_post_type_label ); ?></option>
					<?php } ?>
				</select>
			</label>

			<button type="button" class="pgfw-btn pgfw-btn--ghost" id="pgfw-pdf-builder-open-templates">
				<span class="dashicons dashicons-layout"></span> <?php esc_html_e( 'Choose a Template', 'pdf-generator-for-wp' ); ?>
			</button>

			<button type="button" class="pgfw-btn pgfw-btn--primary" id="pgfw-pdf-builder-save">
				<span class="dashicons dashicons-yes"></span> <?php esc_html_e( 'Save Layout', 'pdf-generator-for-wp' ); ?>
			</button>
			<button type="button" class="pgfw-btn pgfw-btn--ghost" id="pgfw-pdf-builder-clear">
				<?php esc_html_e( 'Clear Page', 'pdf-generator-for-wp' ); ?>
			</button>
			<span id="pgfw-pdf-builder-status" class="pgfw-pdf-builder-status"></span>
		</div>

		<div class="pgfw-pdf-builder-workspace">

			<div class="pgfw-pdf-builder-palette">

				<div class="pgfw-pdf-builder-panel">
					<h4><?php esc_html_e( 'Blocks', 'pdf-generator-for-wp' ); ?></h4>
					<div class="pgfw-pdf-builder-block-grid">
						<button type="button" class="pgfw-block-btn pgfw-pdf-builder-add" data-type="text">
							<span class="dashicons dashicons-editor-textcolor"></span><?php esc_html_e( 'Text', 'pdf-generator-for-wp' ); ?>
						</button>
						<button type="button" class="pgfw-block-btn pgfw-pdf-builder-add" data-type="image">
							<span class="dashicons dashicons-format-image"></span><?php esc_html_e( 'Image', 'pdf-generator-for-wp' ); ?>
						</button>
						<button type="button" class="pgfw-block-btn pgfw-pdf-builder-add" data-type="meta">
							<span class="dashicons dashicons-list-view"></span><?php esc_html_e( 'Meta Field', 'pdf-generator-for-wp' ); ?>
						</button>
						<button type="button" class="pgfw-block-btn pgfw-pdf-builder-add" data-type="rectangle">
							<span class="dashicons dashicons-marker"></span><?php esc_html_e( 'Rectangle', 'pdf-generator-for-wp' ); ?>
						</button>
					</div>

					<div class="pgfw-pdf-builder-block-actions" id="pgfw-pdf-builder-block-actions" style="display:none;">
						<button type="button" class="pgfw-btn pgfw-btn--ghost pgfw-btn--sm" id="pgfw-pdf-builder-duplicate"><?php esc_html_e( 'Duplicate', 'pdf-generator-for-wp' ); ?></button>
						<button type="button" class="pgfw-btn pgfw-btn--ghost pgfw-btn--sm" id="pgfw-pdf-builder-front"><?php esc_html_e( 'To Front', 'pdf-generator-for-wp' ); ?></button>
						<button type="button" class="pgfw-btn pgfw-btn--ghost pgfw-btn--sm" id="pgfw-pdf-builder-back"><?php esc_html_e( 'To Back', 'pdf-generator-for-wp' ); ?></button>
					</div>
				</div>

				<div class="pgfw-pdf-builder-panel">
					<h4><?php esc_html_e( 'Page', 'pdf-generator-for-wp' ); ?></h4>
					<div class="pgfw-pdf-builder-field">
						<label><?php esc_html_e( 'Background Color', 'pdf-generator-for-wp' ); ?></label>
						<input type="text" id="pgfw-pdf-builder-bg-color" class="pgfw-color-field" placeholder="#ffffff" />
					</div>
					<div class="pgfw-pdf-builder-field">
						<label><input type="checkbox" id="pgfw-pdf-builder-watermark-enable" /> <?php esc_html_e( 'Enable Watermark', 'pdf-generator-for-wp' ); ?></label>
					</div>
					<div id="pgfw-pdf-builder-watermark-fields" style="display:none;">
						<div class="pgfw-pdf-builder-field">
							<label><?php esc_html_e( 'Watermark Text', 'pdf-generator-for-wp' ); ?></label>
							<input type="text" id="pgfw-pdf-builder-watermark-text" placeholder="CONFIDENTIAL" />
						</div>
						<div class="pgfw-pdf-builder-field pgfw-pdf-builder-field--row">
							<div><label><?php esc_html_e( 'Color', 'pdf-generator-for-wp' ); ?></label><input type="text" id="pgfw-pdf-builder-watermark-color" class="pgfw-color-field" /></div>
							<div><label><?php esc_html_e( 'Size', 'pdf-generator-for-wp' ); ?></label><input type="number" min="10" id="pgfw-pdf-builder-watermark-size" /></div>
						</div>
						<div class="pgfw-pdf-builder-field">
							<label><?php esc_html_e( 'Opacity', 'pdf-generator-for-wp' ); ?></label>
							<input type="range" min="0.05" max="1" step="0.05" id="pgfw-pdf-builder-watermark-opacity" />
						</div>
					</div>
					<label class="pgfw-pdf-builder-snap">
						<input type="checkbox" id="pgfw-pdf-builder-snap" checked />
						<?php esc_html_e( 'Snap to 10px grid', 'pdf-generator-for-wp' ); ?>
					</label>
				</div>

				<div class="pgfw-pdf-builder-panel">
					<h4><?php esc_html_e( 'Block Properties', 'pdf-generator-for-wp' ); ?></h4>
					<div id="pgfw-pdf-builder-properties" class="pgfw-pdf-builder-properties">
						<p class="description"><?php esc_html_e( 'Select a block on the canvas to edit its properties.', 'pdf-generator-for-wp' ); ?></p>
					</div>
				</div>
			</div>

			<div class="pgfw-pdf-builder-canvas-col">
				<div class="pgfw-pdf-builder-pages">
					<div class="pgfw-pdf-builder-pages__tabs" id="pgfw-pdf-builder-page-tabs"></div>
					<button type="button" class="pgfw-btn pgfw-btn--ghost pgfw-btn--sm" id="pgfw-pdf-builder-add-page"><?php esc_html_e( '+ Page', 'pdf-generator-for-wp' ); ?></button>
					<button type="button" class="pgfw-btn pgfw-btn--ghost pgfw-btn--sm" id="pgfw-pdf-builder-delete-page"><?php esc_html_e( 'Delete Page', 'pdf-generator-for-wp' ); ?></button>
				</div>
				<div class="pgfw-pdf-builder-canvas-wrap">
					<div id="pgfw-pdf-builder-canvas" class="pgfw-pdf-builder-canvas"></div>
				</div>
			</div>
		</div>
	</div>

	<div id="pgfw-pdf-builder-template-modal" class="pgfw-pdf-builder-modal" style="display:none;">
		<div class="pgfw-pdf-builder-modal__backdrop"></div>
		<div class="pgfw-pdf-builder-modal__dialog">
			<div class="pgfw-pdf-builder-modal__header">
				<h3><?php esc_html_e( 'Choose a Reference Template', 'pdf-generator-for-wp' ); ?></h3>
				<button type="button" class="pgfw-pdf-builder-modal__close" id="pgfw-pdf-builder-close-templates">&times;</button>
			</div>
			<p class="description"><?php esc_html_e( 'Loading a template replaces the blocks on the current page (and sets the page background color) - your other pages are untouched. You can still move, resize, restyle or delete anything afterward.', 'pdf-generator-for-wp' ); ?></p>
			<div class="pgfw-pdf-builder-template-grid" id="pgfw-pdf-builder-template-grid"></div>
		</div>
	</div>
</div>
