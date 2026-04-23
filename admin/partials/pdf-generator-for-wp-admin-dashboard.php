<?php
/**
 * Admin dashboard shell (all tabs) with redesigned layout.
 *
 * @package Pdf_Generator_For_Wp
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit(); // Exit if accessed directly.
}

global $pgfw_wps_pgfw_obj, $wps_pgfw_gen_flag, $pgfw_save_check_flag;

$pgfw_active_tab   = isset( $_GET['pgfw_tab'] ) ? sanitize_key( $_GET['pgfw_tab'] ) : 'pdf-generator-for-wp-overview'; // phpcs:ignore
$pgfw_active_tab   = $pgfw_wps_pgfw_obj->wps_pgfw_normalize_dashboard_tab( $pgfw_active_tab );
$_GET['pgfw_tab']  = $pgfw_active_tab; // phpcs:ignore WordPress.Security.NonceVerification
$pgfw_default_tabs = $pgfw_wps_pgfw_obj->wps_pgfw_plug_default_tabs();

$wps_wpg_is_pro_active = $pgfw_wps_pgfw_obj->wps_pgfw_is_pro_plugin_active();
$pgfw_version_label    = $pgfw_wps_pgfw_obj->wps_pgfw_get_dashboard_version_label();
$docs_url             = 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation';
$video_url            = 'https://www.youtube.com/watch?v=RljECeP3JJk';
$faq_url              = 'https://wpswings.com/submit-query/?utm_source=wpswings-pdf-support&utm_medium=pdf-org-backend&utm_campaign=submit-query';
$contact_url          = 'https://wpswings.com/contact-us/';
$plugins_url          = 'https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-pdf-sidebar&utm_medium=pdf-org-backend&utm_campaign=shop-page';

$tabs_for_js = array();
if ( is_array( $pgfw_default_tabs ) ) {
	foreach ( $pgfw_default_tabs as $key => $tab ) {
		$is_pro      = ( isset( $tab['title'] ) && in_array( $tab['title'], array( 'Taxonomy Settings', 'Layout Settings', 'PDF Logs', 'Invoice settings', 'Invoice page settings' ), true ) && ! $wps_wpg_is_pro_active );
		$tabs_for_js[] = array(
			'key'   => $key,
			'title' => $tab['title'],
			'url'   => admin_url( 'admin.php?page=pdf_generator_for_wp_menu&pgfw_tab=' . $key ),
			'isPro' => $is_pro,
		);
	}
}

$pgfw_header_content = $pgfw_wps_pgfw_obj->wps_pgfw_get_dashboard_header_content( $pgfw_active_tab );

$pgfw_settings_data = array(
	'restUrl'    => esc_url_raw( rest_url( 'pgfw-route/v1/tab-content' ) ),
	'nonce'      => wp_create_nonce( 'wp_rest' ),
	'pageUrl'    => admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ),
	'activeTab'  => $pgfw_active_tab,
	'tabs'       => $tabs_for_js,
	'header'     => $pgfw_header_content,
	'parentTabs' => $pgfw_wps_pgfw_obj->wps_pgfw_get_dashboard_parent_tab_map(),
);

// Save/migrate notice handling.
if ( $pgfw_save_check_flag ) {
	if ( ! $wps_pgfw_gen_flag ) {
		$pgfw_wps_pgfw_obj->wps_pgfw_plug_admin_notice( esc_html__( 'Settings saved successfully !', 'pdf-generator-for-wp' ), 'success' );
	} else {
		$pgfw_wps_pgfw_obj->wps_pgfw_plug_admin_notice( esc_html__( 'There might be some error, Please reload the page and try again.', 'pdf-generator-for-wp' ), 'error' );
	}
}

do_action( 'wps_wpg_settings_saved_notice' );
?>

<script type="application/json" id="pgfw-tabs-data"><?php echo wp_json_encode( $pgfw_settings_data ); ?></script>

<div class="pgfw-flashbar" role="region" aria-label="<?php esc_attr_e( 'Flash sale notice', 'pdf-generator-for-wp' ); ?>">
	<div class="pgfw-flashbar__inner">
		<span class="pgfw-flashbar__pill"><?php esc_html_e( 'Flash Sale', 'pdf-generator-for-wp' ); ?></span>
		<div class="pgfw-flashbar__content">
			<div class="pgfw-flashbar__text">
				<span class="pgfw-flashbar__lead"><?php esc_html_e( 'Flash Sale is live', 'pdf-generator-for-wp' ); ?></span>
				<span class="pgfw-flashbar__offer"><?php esc_html_e( 'Get up to 45% OFF on WP Swings Plugins', 'pdf-generator-for-wp' ); ?></span>
			</div>
			<div class="pgfw-flashbar__code">
				<span class="pgfw-flashbar__code-label"><?php esc_html_e( 'Use Code', 'pdf-generator-for-wp' ); ?></span>
				<strong>GRAB10</strong>
			</div>
		</div>
		<a class="pgfw-flashbar__cta" href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-pdf-shop&utm_medium=pdf-org-backend&utm_campaign=shop-page" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Grab Now', 'pdf-generator-for-wp' ); ?></a>
	</div>
</div>

<main class="pgfw-shell pgfw-skin-v2">
	<?php
	$tab_is_active = function( $tab_key ) use ( $pgfw_active_tab, $pgfw_wps_pgfw_obj ) {
		return $pgfw_wps_pgfw_obj->wps_pgfw_get_dashboard_parent_tab( $pgfw_active_tab ) === $tab_key;
	};
	$primary_tabs  = array();
	$overflow_tabs = array();
	if ( is_array( $pgfw_default_tabs ) ) {
		$primary_tabs  = array_slice( $pgfw_default_tabs, 0, 6, true );
		$overflow_tabs = array_slice( $pgfw_default_tabs, 6, null, true );
	}
	$more_active = false;
	foreach ( $overflow_tabs as $overflow_key => $overflow_tab ) {
		if ( $tab_is_active( $overflow_key ) ) {
			$more_active = true;
			break;
		}
	}
	?>

		<div class="pgfw-brandbar">
			<div class="pgfw-brandbar__pill"><?php echo $wps_wpg_is_pro_active ? esc_html__( 'Pro Active', 'pdf-generator-for-wp' ) : esc_html__( 'Free Active', 'pdf-generator-for-wp' ); ?></div>
			<div class="pgfw-brandbar__title"><?php esc_html_e( 'PDF Generator for WP', 'pdf-generator-for-wp' ); ?></div>
		</div>

		<?php do_action( 'pgfw_license_activation_notice_on_dashboard' ); ?>

		<div class="pgfw-tabbar">
		<div class="pgfw-tabbar__version">
			<?php echo esc_html( $pgfw_version_label ); ?>
		</div>
		<nav class="pgfw-nav pgfw-legacy-nav" aria-label="<?php esc_attr_e( 'PDF Generator tabs', 'pdf-generator-for-wp' ); ?>">
			<ul>
				<?php foreach ( $primary_tabs as $pgfw_tab_key => $pgfw_default_tab ) :
					$active_class = $tab_is_active( $pgfw_tab_key ) ? 'is-active' : '';
					$is_pro       = ( isset( $pgfw_default_tab['title'] ) && in_array( $pgfw_default_tab['title'], array( 'Taxonomy Settings', 'Layout Settings', 'PDF Logs', 'Invoice settings', 'Invoice page settings' ), true ) && ! $wps_wpg_is_pro_active );
					?>
					<li class="<?php echo esc_attr( $active_class ); ?>">
						<a href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu&pgfw_tab=' . $pgfw_tab_key ) ); ?>" data-tab="<?php echo esc_attr( $pgfw_tab_key ); ?>">
							<?php echo esc_html( $pgfw_default_tab['title'] ); ?>
							<?php if ( $is_pro ) : ?><span class="pgfw-pill">PRO</span><?php endif; ?>
						</a>
					</li>
				<?php endforeach; ?>

				<?php if ( ! empty( $overflow_tabs ) ) : ?>
					<li class="pgfw-nav-more <?php echo esc_attr( $more_active ? 'is-active' : '' ); ?>">
						<button type="button">
							<?php esc_html_e( 'More', 'pdf-generator-for-wp' ); ?>
							<span class="dashicons dashicons-arrow-down-alt2" aria-hidden="true"></span>
						</button>
						<ul class="pgfw-nav__dropdown">
							<?php foreach ( $overflow_tabs as $pgfw_tab_key => $pgfw_default_tab ) :
								$active_class = $tab_is_active( $pgfw_tab_key ) ? 'is-active' : '';
								$is_pro       = ( isset( $pgfw_default_tab['title'] ) && in_array( $pgfw_default_tab['title'], array( 'Taxonomy Settings', 'Layout Settings', 'PDF Logs', 'Invoice settings', 'Invoice page settings' ), true ) && ! $wps_wpg_is_pro_active );
								?>
								<li class="<?php echo esc_attr( $active_class ); ?>">
									<a href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu&pgfw_tab=' . $pgfw_tab_key ) ); ?>" data-tab="<?php echo esc_attr( $pgfw_tab_key ); ?>">
										<?php echo esc_html( $pgfw_default_tab['title'] ); ?>
										<?php if ( $is_pro ) : ?><span class="pgfw-pill">PRO</span><?php endif; ?>
									</a>
								</li>
							<?php endforeach; ?>
						</ul>
					</li>
				<?php endif; ?>
			</ul>
		</nav>
	</div>

		<div class="pgfw-body-grid <?php echo ( 'pdf-generator-for-wp-overview' === $pgfw_active_tab ) ? 'pgfw-body-grid--overview' : ''; ?>" id="pgfw-body-grid">
			<div class="pgfw-main-column">
				<div class="pgfw-hero-card <?php echo ( 'pdf-generator-for-wp-overview' === $pgfw_active_tab ) ? 'pgfw-hidden' : ''; ?>" id="pgfw-hero-card">
					<div class="pgfw-hero-card__content">
						<p class="pgfw-hero-card__eyebrow" id="pgfw-hero-eyebrow"><?php echo esc_html( $pgfw_header_content['eyebrow'] ); ?></p>
						<h1 id="pgfw-hero-title"><?php echo esc_html( $pgfw_header_content['title'] ); ?></h1>
						<p class="pgfw-hero-card__sub" id="pgfw-hero-sub"><?php echo esc_html( $pgfw_header_content['description'] ); ?></p>
					</div>
					<a class="pgfw-btn pgfw-btn-dark pgfw-hero-card__cta" href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Read Documentation', 'pdf-generator-for-wp' ); ?></a>
				</div>

				<section class="pgfw-content" id="pgfw-tab-content" aria-live="polite">
				<?php
				do_action( 'wps_pgfw_before_general_settings_form' );
				if ( empty( $pgfw_active_tab ) ) {
					$pgfw_active_tab = 'pdf-generator-for-wp-overview';
				}

				$pgfw_tab_content_path = 'admin/partials/' . $pgfw_active_tab . '.php';
				echo '<div class="pgfw-secion-wrap">';
					$pgfw_wps_pgfw_obj->wps_pgfw_plug_load_template( $pgfw_tab_content_path );
				echo '</div>';

				do_action( 'wps_pgfw_after_general_settings_form' );
				?>
			</section>
		</div>

		<aside class="pgfw-rail" aria-label="<?php esc_attr_e( 'Helpful links', 'pdf-generator-for-wp' ); ?>">
			<div class="pgfw-card pgfw-rail-card pgfw-help-card">
				<h3><?php esc_html_e( 'Need help with this plugin?', 'pdf-generator-for-wp' ); ?></h3>
				<ul>
					<li><a class="pgfw-rail-link" href="<?php echo esc_url( $video_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Watch Video', 'pdf-generator-for-wp' ); ?></a></li>
					<li><a class="pgfw-rail-link" href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Documentation', 'pdf-generator-for-wp' ); ?></a></li>
					<li><a class="pgfw-rail-link" href="<?php echo esc_url( $faq_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Support', 'pdf-generator-for-wp' ); ?></a></li>
				</ul>
			</div>

			<div class="pgfw-card pgfw-rail-card pgfw-contact-card">
				<h3><?php esc_html_e( 'Still facing problems?', 'pdf-generator-for-wp' ); ?></h3>
				<p><?php esc_html_e( 'We are ready to resolve workflow, styling, and integration issues across your store setup.', 'pdf-generator-for-wp' ); ?></p>
				<a class="pgfw-rail-action pgfw-rail-action--dark" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Contact Us', 'pdf-generator-for-wp' ); ?></a>
			</div>

			<div class="pgfw-card pgfw-rail-card pgfw-plugin-card">
				<h3><?php esc_html_e( 'Explore more plugins', 'pdf-generator-for-wp' ); ?></h3>
				<p><?php esc_html_e( 'Discover additional commerce and automation plugins from the same product family.', 'pdf-generator-for-wp' ); ?></p>
				<a class="pgfw-rail-action pgfw-rail-action--light" href="<?php echo esc_url( $plugins_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View More Plugins', 'pdf-generator-for-wp' ); ?></a>
			</div>
		</aside>
	</div>
</main>

<?php
// Migration reminder (kept from legacy UI).
$plugin_admin = new Pdf_Generator_For_Wp_Admin( 'pdf-generator-for-wp', '1.0.7' );
$count        = $plugin_admin->wps_wpg_get_count( 'settings' );
$key3         = get_option( 'wps_wpg_activated_timestamp' );
if ( ! empty( $count ) && empty( $key3 ) ) {
	$global_custom_js = 'const triggerPGFWMigration = () => { swal({ title: "Attention Required!", text: "Please migrate your database keys first by clicking the button below, then you can access the dashboard page.", icon: "error", button: "Click to Import", closeOnClickOutside: false }).then(function(){ jQuery(".treat-button").click(); }); }; triggerPGFWMigration();';
	wp_add_inline_script( 'wps-pgfw-admin-custom-setting-js', $global_custom_js );
}
?>
