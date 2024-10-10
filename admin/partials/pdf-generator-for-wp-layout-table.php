<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com/
 * @since      3.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}
?>
<div class = 'wps-wpg-card__wrapper'>
	<ul class = 'wps-wpg-card__wrapper-list'>
	<?php $pgfw_use_template = get_option( 'wpg_use_cover_page_template' ); ?>
	<?php for ( $i = 1; $i < 6; $i++ ) { ?>
		<li class = 'wps-wpg-card__wrapper-list-item<?php echo ( $i === (int) $pgfw_use_template ) ? esc_attr( ' wpg-active' ) : ''; ?>'>
			<div class = 'wps-wpg-card__item-img-wrapper'>
				<img height="100" width="100" src ="<?php echo esc_attr( PDF_GENERATOR_FOR_WP_DIR_URL ); ?>admin/src/images/temp<?php echo esc_attr( $i ); ?>_screenshot.png" alt = ''>
			</div>
			<div class = 'wps-wpg-card__item-content'>
				<div class = 'wps-wpg-card__item-content-header'>
					<h3 class="wps-wpg-card__item-heading">
						<?php
						/* translators: %s: template number */
						printf( esc_html__( 'Template %s', 'pdf-generator-for-wp' ), esc_attr( $i ) );
						?>
					</h3>
				</div>
				<div class="wps-wpg-card__item-content-button">
					<a href="#TB_inline?width=400&inlineId=wpg-preview-template-modal<?php echo esc_attr( $i ); ?>" class="wps-wpg-card-btn wps-wpg-card-preview-btn wpg-preview-coverpage-template thickbox">preview</a>
					<a href="javascript:void(0)" class="wps-wpg-card-btn wps-wpg-card-activate-btn wpg-activate-coverpage-template" data-template-id ="<?php echo esc_attr( $i ); ?>"><?php ( $i === (int) $pgfw_use_template ) ? esc_html_e( 'Activated', 'pdf-generator-for-wp' ) : esc_html_e( 'Activate', 'pdf-generator-for-wp' ); ?></a>
					<div id="wpg-preview-template-modal<?php echo esc_attr( $i ); ?>" style="display:none;"><p><img src="<?php echo esc_attr( PDF_GENERATOR_FOR_WP_DIR_URL ); ?>admin/src/images/temp<?php echo esc_attr( $i ); ?>_screenshot.png" alt="" class="wps-wpg-card__img"></p></div>
				</div>	
			</div>
		</li>
	<?php } ?>
	</ul>
</div>
