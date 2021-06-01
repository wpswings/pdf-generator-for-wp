<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://makewebbetter.com
 * @since      1.0.0
 *
 * @package    Makewebbetter_Onboarding
 * @subpackage Makewebbetter_Onboarding/admin/onboarding
 */

global $pgfw_mwb_pgfw_obj;
$pgfw_onboarding_form_fields = apply_filters( 'mwb_pgfw_on_boarding_form_fields', array() );
?>

<?php if ( ! empty( $pgfw_onboarding_form_fields ) ) : ?>
	<div class="mdc-dialog mdc-dialog--scrollable">
		<div class="mwb-pgfw-on-boarding-wrapper-background mdc-dialog__container">
			<div class="mwb-pgfw-on-boarding-wrapper mdc-dialog__surface" role="alertdialog" aria-modal="true" aria-labelledby="my-dialog-title" aria-describedby="my-dialog-content">
				<div class="mdc-dialog__content">
					<div class="mwb-pgfw-on-boarding-close-btn">
						<a href="#"><span class="pgfw-close-form material-icons mwb-pgfw-close-icon mdc-dialog__button" data-mdc-dialog-action="close">clear</span></a>
					</div>

					<h3 class="mwb-pgfw-on-boarding-heading mdc-dialog__title"><?php esc_html_e( 'Welcome to MakeWebBetter', 'pdf-generator-for-wp' ); ?> </h3>
					<p class="mwb-pgfw-on-boarding-desc"><?php esc_html_e( 'We love making new friends! Subscribe below and we promise to keep you up-to-date with our latest new plugins, updates, awesome deals and a few special offers.', 'pdf-generator-for-wp' ); ?></p>

					<form action="#" method="post" class="mwb-pgfw-on-boarding-form">
						<?php
						$pgfw_mwb_pgfw_obj->mwb_pgfw_plug_generate_html( $pgfw_onboarding_form_fields );
						?>
						<div class="mwb-pgfw-on-boarding-form-btn__wrapper mdc-dialog__actions">
							<div class="mwb-pgfw-on-boarding-form-submit mwb-pgfw-on-boarding-form-verify ">
								<input type="submit" class="mwb-pgfw-on-boarding-submit mwb-on-boarding-verify mdc-button mdc-button--raised" value="Send Us">
							</div>
							<div class="mwb-pgfw-on-boarding-form-no_thanks">
								<a href="#" class="mwb-pgfw-on-boarding-no_thanks mdc-button" data-mdc-dialog-action="discard"><?php esc_html_e( 'Skip For Now', 'pdf-generator-for-wp' ); ?></a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="mdc-dialog__scrim"></div>
	</div>
<?php endif; ?>
