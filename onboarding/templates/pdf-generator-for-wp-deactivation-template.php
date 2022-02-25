<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://wpswings.com
 * @since      1.0.0
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/admin/onboarding
 */

global $pagenow, $pgfw_wps_pgfw_obj;
if ( empty( $pagenow ) || 'plugins.php' != $pagenow ) {
	return false;
}

$pgfw_onboarding_form_deactivate = apply_filters( 'wps_pgfw_deactivation_form_fields', array() );
?>
<?php if ( ! empty( $pgfw_onboarding_form_deactivate ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable">
		<div class="wps-pgfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="wps-pgfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="wps-pgfw-on-boarding-close-btn">
						<a href="#">
							<span class="pgfw-close-form material-icons wps-pgfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span>
						</a>
					</div>

					<h3 class="wps-pgfw-on-boarding-heading mdc-dialog__title"></h3>
					<p class="wps-pgfw-on-boarding-desc"><?php esc_html_e( 'May we have a little info about why you are deactivating?', 'pdf-generator-for-wp' ); ?></p>
					<form action="#" method="post" class="wps-pgfw-on-boarding-form">
						<?php
						$pgfw_wps_pgfw_obj->wps_pgfw_plug_generate_html( $pgfw_onboarding_form_deactivate );
						?>
						<div class="wps-pgfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="wps-pgfw-on-boarding-form-submit wps-pgfw-on-boarding-form-verify ">
								<input type="submit" class="wps-pgfw-on-boarding-submit wps-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="wps-pgfw-on-boarding-form-no_thanks">
								<a href="#" class="wps-deactivation-no_thanks mdc-button"><?php esc_html_e( 'Skip and Deactivate Now', 'pdf-generator-for-wp' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
