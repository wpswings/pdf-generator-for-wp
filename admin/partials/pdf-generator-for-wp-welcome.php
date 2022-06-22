<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpswings.com/
 * @since 1.0.0
 *
 * @package    pdf_generator_for_wp
 * @subpackage pdf_generator_for_wp/admin/partials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}
global $pgfw_wps_pgfw_obj, $wps_pgfw_gen_flag, $pgfw_save_check_flag;
$pgfw_default_tabs = $pgfw_wps_pgfw_obj->wps_pgfw_plug_default_tabs();
$pgfw_tab_key = '';
?>
<header>
	<?php
	// desc - This hook is used for trial.
	do_action( 'wps_wpg_settings_saved_notice' );
	?>
	<div class="wps-header-container wps-bg-white wps-r-8">
		<h1 class="wps-header-title"><?php echo esc_attr( __( 'WP Swings' ) ); ?></h1>
	</div>
</header>
<main class="wps-main wps-bg-white wps-r-8">
	<section class="wps-section">
		<div>
			<?php
				// desc - This hook is used for trial.
			do_action( 'admin_init' );
				// if submenu is directly clicked on woocommerce.
			$pgfw_genaral_settings = apply_filters(
				'pgfw_home_settings_array',
				array(

					array(
						'title'       => __( 'Enable Tracking', 'pdf-generator-for-wp' ),
						'type'        => 'radio-switch',
						'description' => __( 'This is switch field demo follow same structure for further use.', 'pdf-generator-for-wp' ),
						'name'        => 'pgfw_enable_tracking',
						'id'          => 'pgfw_enable_tracking',
						'value'       => get_option( 'pgfw_enable_tracking' ),
						'class'       => 'pgfw-radio-switch-class',
						'options'     => array(
							'yes' => __( 'YES', 'pdf-generator-for-wp' ),
							'no'  => __( 'NO', 'pdf-generator-for-wp' ),
						),
					),
					array(
						'type'  => 'button',
						'id'    => 'pgfw_tracking_save_button',
						'button_text' => __( 'Save', 'pdf-generator-for-wp' ),
						'class' => 'pgfw-button-class',
					),
				)
			);
			?>
			<form action="" method="POST" class="wps-pgfw-gen-section-form">
				<div class="pgfw-secion-wrap">
					<?php
					$pgfw_general_html = $pgfw_wps_pgfw_obj->wps_pgfw_plug_generate_html( $pgfw_genaral_settings );
					echo esc_html( $pgfw_general_html );

					?>
					<input type="hidden" id="updatenonce" name="updatenonce" value="<?php echo esc_attr( wp_create_nonce() ); ?>" />
	
				</div>
			</form>
			<?php
			do_action( 'admin_init' );
			$all_plugins = get_plugins();
			?>
		</div>
	</section>
	<style type="text/css">
		.cards {
			   display: flex;
			   flex-wrap: wrap;
			   padding: 20px 40px;
		}
		.card {
			flex: 1 0 518px;
			box-sizing: border-box;
			margin: 1rem 3.25em;
			text-align: center;
		}

	</style>
	<div class="centered">
		<section class="cards">
			<?php foreach ( get_plugins() as $key => $value ) : ?>
				<?php if ( 'WP Swings' === $value['Author'] ) : ?>
					<article class="card">
						<div class="container">
							<h4><b><?php echo wp_kses_post( $value['Name'] ); ?></b></h4> 
							<p><?php echo wp_kses_post( $value['Version'] ); ?></p> 
							<p><?php echo wp_kses_post( $value['Description'] ); ?></p>
						</div>
					</article>
				<?php endif; ?>
			<?php endforeach; ?>
		</section>
	</div>
