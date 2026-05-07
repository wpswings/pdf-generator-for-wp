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
$docs_url             = 'https://docs.wpswings.com/pdf-generator-for-wp/?utm_source=wpswings-pdf-org&utm_medium=pdf-org-backend&utm_campaign=documentation';
$video_url            = 'https://www.youtube.com/watch?v=RljECeP3JJk';
$faq_url              = 'https://wpswings.com/contact-us/?utm_source=wpswings-pdf-org&utm_medium=pdf-org-backend&utm_campaign=contact-us';
$contact_url          = 'https://wpswings.com/contact-us/?utm_source=wpswings-pdf-org&utm_medium=pdf-org-backend&utm_campaign=contact-us';
$plugins_url          = 'https://wpswings.com/woocommerce-plugins/?utm_source=wpswings-pdf-org&utm_medium=pdf-org-backend&utm_campaign=shop-page';

$tabs_for_js = array();
if ( is_array( $pgfw_default_tabs ) ) {
	foreach ( $pgfw_default_tabs as $key => $dashboard_tab ) {
		$is_pro      = ( isset( $dashboard_tab['title'] ) && in_array( $dashboard_tab['title'], array( 'Taxonomy Settings', 'Layout Settings', 'PDF Logs', 'Invoice settings', 'Invoice page settings' ), true ) && ! $wps_wpg_is_pro_active );
		$tabs_for_js[] = array(
			'key'   => $key,
			'title' => $dashboard_tab['title'],
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

			<div class="pgfw-card pgfw-rail-card pgfw-services-card">
				<div class="pgfw-services-card__head">
					<div class="pgfw-services-card__intro">
						<h3><?php esc_html_e( 'Grow Your Store With WP Swings', 'pdf-generator-for-wp' ); ?></h3>
						<p><?php esc_html_e( "Expert solutions to boost your store's performance.", 'pdf-generator-for-wp' ); ?></p>
					</div>
					<span class="pgfw-services-card__spark" aria-hidden="true">
						<svg viewBox="0 0 24 24" focusable="false">
							<path d="M12 2.9l1.8 3.68 4.07.59-2.95 2.88.7 4.05L12 12.23 8.36 14.1l.7-4.05L6.1 7.17l4.08-.59L12 2.9z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"></path>
						</svg>
					</span>
				</div>

				<div class="pgfw-services-card__list">
					<a class="pgfw-services-card__item" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="pgfw-services-card__icon pgfw-services-card__icon--seo" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<circle cx="10.5" cy="10.5" r="4.5" fill="none" stroke="currentColor" stroke-width="1.8"></circle>
								<path d="M14 14l4 4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
							</svg>
						</span>
						<span class="pgfw-services-card__copy">
							<strong><?php esc_html_e( 'SEO Services', 'pdf-generator-for-wp' ); ?></strong>
							<small><?php esc_html_e( 'Improve rankings & organic traffic', 'pdf-generator-for-wp' ); ?></small>
						</span>
						<span class="pgfw-services-card__chevron" aria-hidden="true">&rsaquo;</span>
					</a>

					<a class="pgfw-services-card__item" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="pgfw-services-card__icon pgfw-services-card__icon--ads" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<path d="M5 16.8l3.56-6.16a1.2 1.2 0 0 1 2.08 0l3.66 6.35" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path>
								<path d="M15.4 7.2l3.25 5.63" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
								<circle cx="18.65" cy="14.2" r="1.5" fill="currentColor"></circle>
							</svg>
						</span>
						<span class="pgfw-services-card__copy">
							<strong><?php esc_html_e( 'Google Ads Setup And G4 Setup', 'pdf-generator-for-wp' ); ?></strong>
							<small><?php esc_html_e( 'Run profitable ad campaigns', 'pdf-generator-for-wp' ); ?></small>
						</span>
						<span class="pgfw-services-card__chevron" aria-hidden="true">&rsaquo;</span>
					</a>

					<a class="pgfw-services-card__item" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="pgfw-services-card__icon pgfw-services-card__icon--speed" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<path d="M6.5 15.5a5.5 5.5 0 1 1 11 0" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
								<path d="M12 15.5l3-4.2" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"></path>
								<circle cx="12" cy="15.5" r="1.2" fill="currentColor"></circle>
							</svg>
						</span>
						<span class="pgfw-services-card__copy">
							<strong><?php esc_html_e( 'Speed Optimization', 'pdf-generator-for-wp' ); ?></strong>
							<small><?php esc_html_e( 'Faster store, happier customers', 'pdf-generator-for-wp' ); ?></small>
						</span>
						<span class="pgfw-services-card__chevron" aria-hidden="true">&rsaquo;</span>
					</a>

					<a class="pgfw-services-card__item" href="<?php echo esc_url( $contact_url ); ?>" target="_blank" rel="noopener noreferrer">
						<span class="pgfw-services-card__icon pgfw-services-card__icon--speed" aria-hidden="true">
							<svg viewBox="0 0 24 24" focusable="false">
								<path d="M4.5 6.5h15a2.5 2.5 0 0 1 2.5 2.5v5.5a2.5 2.5 0 0 1-2.5 2.5h-7l-4 3v-3h-4A2.5 2.5 0 0 1 2 14.5V9a2.5 2.5 0 0 1 2.5-2.5Z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"  ></path>
								<path d="M5.7 10.1l1 3.7 1-3.7 1 3.7 1-3.7" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"  ></path>
								<circle cx="13.1" cy="12" r="1.4" fill="none" stroke="currentColor" stroke-width="1.4"></circle>
								<circle cx="17.2" cy="12" r="1.4" fill="none" stroke="currentColor" stroke-width="1.4"></circle>
							</svg>
						</span>
						<span class="pgfw-services-card__copy">
							<strong><?php esc_html_e( 'WooCommerce Development Services', 'pdf-generator-for-wp' ); ?></strong>
							<small><?php esc_html_e( 'Custom Solution For your store needs', 'pdf-generator-for-wp' ); ?></small>
						</span>
						<span class="pgfw-services-card__chevron" aria-hidden="true">&rsaquo;</span>
					</a>
				</div>

				<a class="pgfw-services-card__cta" href="#" data-pgfw-open-expert-modal="true"><?php esc_html_e( 'Talk to an Expert', 'pdf-generator-for-wp' ); ?></a>
				<p class="pgfw-services-card__meta">
					<?php esc_html_e( 'Services by WP Swings', 'pdf-generator-for-wp' ); ?>
					<span class="pgfw-services-card__meta-badge" aria-hidden="true">
						<svg viewBox="0 0 24 24" focusable="false">
							<path d="M12 3l6 2.7v5.5c0 3.9-2.4 7.5-6 9-3.6-1.5-6-5.1-6-9V5.7L12 3z" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linejoin="round"></path>
							<path d="M9.4 12.1l1.7 1.7 3.5-3.7" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"></path>
						</svg>
					</span>
				</p>
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
