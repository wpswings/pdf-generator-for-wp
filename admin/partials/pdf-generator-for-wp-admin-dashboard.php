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
do_action( 'pgfw_license_activation_notice_on_dashboard' );
$pgfw_default_tabs = $pgfw_wps_pgfw_obj->wps_pgfw_plug_default_tabs();

$wps_wpg_plugin_list  = get_option( 'active_plugins' );
$wps_wpg_plugin       = 'wordpress-pdf-generator/wordpress-pdf-generator.php';
$wps_wpg_is_pro_active = in_array( $wps_wpg_plugin, $wps_wpg_plugin_list, true );
$upgrade_url          = 'https://wpswings.com/product/pdf-generator-for-wp-pro/?utm_source=wpswings-pdf-pro&utm_medium=pdf-org-backend&utm_campaign=go-pro';
$docs_url             = 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-docs&utm_medium=wpswings-org-backend&utm_campaign=documentation';
$video_url            = 'https://www.youtube.com/watch?v=RljECeP3JJk';
$faq_url              = 'https://wpswings.com/submit-query/?utm_source=wpswings-pdf-support&utm_medium=pdf-org-backend&utm_campaign=submit-query';
$contact_url          = 'https://wpswings.com/contact-us/';

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

$pgfw_settings_data = array(
	'restUrl'   => esc_url_raw( rest_url( 'pgfw-route/v1/tab-content' ) ),
	'nonce'     => wp_create_nonce( 'wp_rest' ),
	'pageUrl'   => admin_url( 'admin.php?page=pdf_generator_for_wp_menu' ),
	'activeTab' => $pgfw_active_tab,
	'tabs'      => $tabs_for_js,
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

<div class="pgfw-flashbar">
	<div class="pgfw-flashbar__text"><?php esc_html_e( 'Flash Sale is live: Get up to 45% OFF on WP Swings Plugins', 'pdf-generator-for-wp' ); ?></div>
	<div class="pgfw-flashbar__code"><?php esc_html_e( 'Use Code', 'pdf-generator-for-wp' ); ?> <strong>GRAB10</strong></div>
	<a class="pgfw-flashbar__cta" href="https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-pdf-shop&utm_medium=pdf-org-backend&utm_campaign=shop-page" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Grab Now', 'pdf-generator-for-wp' ); ?></a>
</div>

<main class="pgfw-shell pgfw-skin-v2">
	<?php
	$tab_is_active = function( $tab_key ) use ( $pgfw_active_tab ) {
		if ( $pgfw_active_tab === $tab_key ) {
			return true;
		}
		if ( in_array( $pgfw_active_tab, array( 'pdf-generator-for-wp-header', 'pdf-generator-for-wp-body', 'pdf-generator-for-wp-footer', 'pdf-generator-for-wp-pdf-icon-setting' ), true ) && 'pdf-generator-for-wp-pdf-setting' === $tab_key ) {
			return true;
		}
		if ( in_array( $pgfw_active_tab, array( 'pdf-generator-for-wp-cover-page-setting', 'pdf-generator-for-wp-internal-page-setting' ), true ) && 'pdf-generator-for-wp-layout-settings' === $tab_key ) {
			return true;
		}
		return false;
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
		<div class="pgfw-brandbar__pill"><?php echo $wps_wpg_is_pro_active ? esc_html__( 'Pro Active', 'pdf-generator-for-wp' ) : esc_html__( 'PDF Generator', 'pdf-generator-for-wp' ); ?></div>
		<div class="pgfw-brandbar__title"><?php esc_html_e( 'PDF Generator for WP', 'pdf-generator-for-wp' ); ?></div>
		<a class="pgfw-btn pgfw-btn-success pgfw-brandbar__cta" href="<?php echo esc_url( $upgrade_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Upgrade to pro', 'pdf-generator-for-wp' ); ?></a>
	</div>

	<div class="pgfw-tabbar">
		<div class="pgfw-tabbar__version">
			v<?php echo esc_html( PDF_GENERATOR_FOR_WP_VERSION ); ?> <?php echo $wps_wpg_is_pro_active ? esc_html__( 'Pro', 'pdf-generator-for-wp' ) : ''; ?>
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

	<div class="pgfw-hero-card">
		<div class="pgfw-hero-card__icon">
			<img src="<?php echo esc_url( PDF_GENERATOR_FOR_WP_DIR_URL . 'admin/src/images/document-management-big.png' ); ?>" alt="<?php esc_attr_e( 'PDF Generator icon', 'pdf-generator-for-wp' ); ?>" loading="lazy" />
		</div>
		<div class="pgfw-hero-card__content">
			<p class="pgfw-hero-card__eyebrow"><?php esc_html_e( 'PDF Generator for WP', 'pdf-generator-for-wp' ); ?></p>
			<h1><?php esc_html_e( 'Control every PDF touchpoint from one screen', 'pdf-generator-for-wp' ); ?></h1>
			<p class="pgfw-hero-card__sub"><?php esc_html_e( 'Configure icons, templates, watermarks and emails in a single, streamlined workspace.', 'pdf-generator-for-wp' ); ?></p>
			<div class="pgfw-hero-card__actions">
				<a class="pgfw-btn pgfw-btn-secondary" href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'View Docs', 'pdf-generator-for-wp' ); ?></a>
				<a class="pgfw-btn pgfw-btn-dark" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Get Help', 'pdf-generator-for-wp' ); ?></a>
			</div>
		</div>
		<div class="pgfw-hero-card__badge">
			<span class="pgfw-badge pgfw-badge--pill"><?php printf( esc_html__( 'v%s', 'pdf-generator-for-wp' ), esc_html( PDF_GENERATOR_FOR_WP_VERSION ) ); ?></span>
			<span class="pgfw-hero-card__tag"><?php esc_html_e( 'UI Refresh', 'pdf-generator-for-wp' ); ?></span>
		</div>
	</div>

	<div class="pgfw-body-grid">
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

		<aside class="pgfw-rail" aria-label="<?php esc_attr_e( 'Helpful links', 'pdf-generator-for-wp' ); ?>">
			<a class="pgfw-setup-link" href="<?php echo esc_url( admin_url( 'admin.php?page=pdf_generator_for_wp_menu&pgfw_tab=pdf-generator-for-wp-general' ) ); ?>">
				<span class="dashicons dashicons-migrate" aria-hidden="true"></span>
				<?php esc_html_e( 'Let’s Start the Setup', 'pdf-generator-for-wp' ); ?>
			</a>

			<div class="pgfw-card pgfw-help-card">
				<h3><?php esc_html_e( 'Don’t know how this plugin works?', 'pdf-generator-for-wp' ); ?></h3>
				<ul>
					<li><a href="<?php echo esc_url( $video_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Watch Video', 'pdf-generator-for-wp' ); ?> <span class="dashicons dashicons-video-alt3" aria-hidden="true"></span></a></li>
					<li><a href="<?php echo esc_url( $docs_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Documentation', 'pdf-generator-for-wp' ); ?> <span class="dashicons dashicons-media-default" aria-hidden="true"></span></a></li>
					<li><a href="<?php echo esc_url( $faq_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'FAQs', 'pdf-generator-for-wp' ); ?> <span class="dashicons dashicons-editor-help" aria-hidden="true"></span></a></li>
				</ul>
			</div>

			<div class="pgfw-card pgfw-help-card pgfw-contact-card">
				<h3><?php esc_html_e( 'Contact us', 'pdf-generator-for-wp' ); ?></h3>
				<p><?php esc_html_e( 'If we are able to help you grow your business, let us know.', 'pdf-generator-for-wp' ); ?></p>
				<div class="pgfw-socials">
					<a class="dashicons dashicons-facebook" href="https://www.facebook.com/wpswings" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text">Facebook</span></a>
					<a class="dashicons dashicons-twitter" href="https://x.com/wpswings" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text">X</span></a>
					<a class="dashicons dashicons-instagram" href="https://www.instagram.com/wpswings" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text">Instagram</span></a>
					<a class="dashicons dashicons-admin-links" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer"><span class="screen-reader-text">Contact</span></a>
				</div>
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
