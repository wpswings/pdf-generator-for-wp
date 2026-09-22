<?php
/**
 * Cloud storage integration (Google Drive, Dropbox, Amazon S3) for generated PDFs.
 *
 * @link       https://wpswings.com/
 * @since      1.6.6
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Uploads generated PDFs to Google Drive, Dropbox and Amazon S3, and handles
 * the OAuth connect/disconnect flow for Google Drive and Dropbox.
 *
 * Settings/credentials live in the `pgfw_cloud_storage_save_settings` option
 * (managed by the Cloud Storage settings tab). OAuth refresh tokens live in
 * the separate `pgfw_cloud_storage_tokens` option so that saving the settings
 * form never wipes out a live connection.
 *
 * @package    Pdf_Generator_For_Wp
 * @subpackage Pdf_Generator_For_Wp/includes
 * @author     WP Swings <webmaster@wpswings.com>
 */
class Pdf_Generator_For_Wp_Cloud_Storage {

	/**
	 * The ID of this plugin.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Option name that stores the cloud storage settings/credentials.
	 *
	 * @var string
	 */
	const SETTINGS_OPTION = 'pgfw_cloud_storage_save_settings';

	/**
	 * Option name that stores OAuth refresh tokens.
	 *
	 * @var string
	 */
	const TOKENS_OPTION = 'pgfw_cloud_storage_tokens';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name = '', $version = '' ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Get cloud storage settings/credentials.
	 *
	 * @return array
	 */
	public function get_settings() {
		return get_option( self::SETTINGS_OPTION, array() );
	}

	/**
	 * Get stored OAuth tokens.
	 *
	 * @return array
	 */
	private function get_tokens() {
		return get_option( self::TOKENS_OPTION, array() );
	}

	/**
	 * Whether the given provider currently has a stored refresh token.
	 *
	 * @param string $provider 'gdrive' or 'dropbox'.
	 * @return bool
	 */
	public function is_connected( $provider ) {
		$tokens = $this->get_tokens();
		if ( 'gdrive' === $provider ) {
			return ! empty( $tokens['pgfw_gdrive_refresh_token'] );
		}
		if ( 'dropbox' === $provider ) {
			return ! empty( $tokens['pgfw_dropbox_refresh_token'] );
		}
		return false;
	}

	/**
	 * OAuth redirect URI to register with Google/Dropbox for a given provider.
	 *
	 * @param string $provider 'gdrive' or 'dropbox'.
	 * @return string
	 */
	public function get_oauth_redirect_uri( $provider ) {
		return admin_url( 'admin-post.php?action=pgfw_cloud_storage_oauth_callback&provider=' . $provider );
	}

	/**
	 * Build the "Connect Google Drive" authorization URL.
	 *
	 * @param array $settings Cloud storage settings.
	 * @return string
	 */
	public function get_google_drive_auth_url( $settings ) {
		if ( empty( $settings['pgfw_gdrive_client_id'] ) ) {
			return '';
		}
		$params = array(
			'client_id'     => $settings['pgfw_gdrive_client_id'],
			'redirect_uri'  => $this->get_oauth_redirect_uri( 'gdrive' ),
			'response_type' => 'code',
			'access_type'   => 'offline',
			'prompt'        => 'consent',
			'scope'         => 'https://www.googleapis.com/auth/drive.file',
			'state'         => wp_create_nonce( 'pgfw_gdrive_oauth' ),
		);
		return 'https://accounts.google.com/o/oauth2/v2/auth?' . http_build_query( $params );
	}

	/**
	 * Build the "Connect Dropbox" authorization URL.
	 *
	 * @param array $settings Cloud storage settings.
	 * @return string
	 */
	public function get_dropbox_auth_url( $settings ) {
		if ( empty( $settings['pgfw_dropbox_app_key'] ) ) {
			return '';
		}
		$params = array(
			'client_id'         => $settings['pgfw_dropbox_app_key'],
			'redirect_uri'      => $this->get_oauth_redirect_uri( 'dropbox' ),
			'response_type'     => 'code',
			'token_access_type' => 'offline',
			'state'             => wp_create_nonce( 'pgfw_dropbox_oauth' ),
		);
		return 'https://www.dropbox.com/oauth2/authorize?' . http_build_query( $params );
	}

	/**
	 * admin-post handler: OAuth callback shared by Google Drive & Dropbox.
	 *
	 * @return void
	 */
	public function handle_oauth_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'pdf-generator-for-wp' ) );
		}

		$provider = isset( $_GET['provider'] ) ? sanitize_key( $_GET['provider'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$code     = isset( $_GET['code'] ) ? sanitize_text_field( wp_unslash( $_GET['code'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$state    = isset( $_GET['state'] ) ? sanitize_text_field( wp_unslash( $_GET['state'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$settings = $this->get_settings();
		$status   = 'error';

		if ( $code ) {
			if ( 'gdrive' === $provider && wp_verify_nonce( $state, 'pgfw_gdrive_oauth' ) ) {
				$status = $this->exchange_google_drive_code( $code, $settings );
			} elseif ( 'dropbox' === $provider && wp_verify_nonce( $state, 'pgfw_dropbox_oauth' ) ) {
				$status = $this->exchange_dropbox_code( $code, $settings );
			}
		}

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'              => 'pdf_generator_for_wp_menu',
					'pgfw_tab'          => 'pdf-generator-for-wp-cloud-storage',
					'pgfw_cloud_status' => $status,
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	/**
	 * admin-post handler: disconnect a provider (drop its stored refresh token).
	 *
	 * @return void
	 */
	public function handle_disconnect() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to do this.', 'pdf-generator-for-wp' ) );
		}
		check_admin_referer( 'pgfw_cloud_storage_disconnect' );

		$provider = isset( $_GET['provider'] ) ? sanitize_key( $_GET['provider'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		$tokens   = $this->get_tokens();

		if ( 'gdrive' === $provider ) {
			unset( $tokens['pgfw_gdrive_refresh_token'] );
			delete_transient( 'pgfw_gdrive_access_token' );
		} elseif ( 'dropbox' === $provider ) {
			unset( $tokens['pgfw_dropbox_refresh_token'] );
			delete_transient( 'pgfw_dropbox_access_token' );
		}
		update_option( self::TOKENS_OPTION, $tokens );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'     => 'pdf_generator_for_wp_menu',
					'pgfw_tab' => 'pdf-generator-for-wp-cloud-storage',
				),
				admin_url( 'admin.php' )
			)
		);
		exit;
	}

	/**
	 * Exchange a Google OAuth authorization code for a refresh token and store it.
	 *
	 * @param string $code     Authorization code from Google.
	 * @param array  $settings Cloud storage settings.
	 * @return string Status slug for the redirect notice.
	 */
	private function exchange_google_drive_code( $code, $settings ) {
		if ( empty( $settings['pgfw_gdrive_client_id'] ) || empty( $settings['pgfw_gdrive_client_secret'] ) ) {
			return 'error';
		}

		$response = wp_remote_post(
			'https://oauth2.googleapis.com/token',
			array(
				'timeout' => 30,
				'body'    => array(
					'code'          => $code,
					'client_id'     => $settings['pgfw_gdrive_client_id'],
					'client_secret' => $settings['pgfw_gdrive_client_secret'],
					'redirect_uri'  => $this->get_oauth_redirect_uri( 'gdrive' ),
					'grant_type'    => 'authorization_code',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return 'error';
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['refresh_token'] ) ) {
			return 'error';
		}

		$tokens                              = $this->get_tokens();
		$tokens['pgfw_gdrive_refresh_token'] = $body['refresh_token'];
		update_option( self::TOKENS_OPTION, $tokens );
		delete_transient( 'pgfw_gdrive_access_token' );

		return 'gdrive_connected';
	}

	/**
	 * Exchange a Dropbox OAuth authorization code for a refresh token and store it.
	 *
	 * @param string $code     Authorization code from Dropbox.
	 * @param array  $settings Cloud storage settings.
	 * @return string Status slug for the redirect notice.
	 */
	private function exchange_dropbox_code( $code, $settings ) {
		if ( empty( $settings['pgfw_dropbox_app_key'] ) || empty( $settings['pgfw_dropbox_app_secret'] ) ) {
			return 'error';
		}

		$response = wp_remote_post(
			'https://api.dropboxapi.com/oauth2/token',
			array(
				'timeout' => 30,
				'body'    => array(
					'code'          => $code,
					'grant_type'    => 'authorization_code',
					'redirect_uri'  => $this->get_oauth_redirect_uri( 'dropbox' ),
					'client_id'     => $settings['pgfw_dropbox_app_key'],
					'client_secret' => $settings['pgfw_dropbox_app_secret'],
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return 'error';
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['refresh_token'] ) ) {
			return 'error';
		}

		$tokens                               = $this->get_tokens();
		$tokens['pgfw_dropbox_refresh_token'] = $body['refresh_token'];
		update_option( self::TOKENS_OPTION, $tokens );
		delete_transient( 'pgfw_dropbox_access_token' );

		return 'dropbox_connected';
	}

	/**
	 * Get a short-lived Google Drive access token, refreshing (and caching) as needed.
	 *
	 * @param array $settings Cloud storage settings.
	 * @return string|WP_Error
	 */
	private function get_google_drive_access_token( $settings ) {
		$cached = get_transient( 'pgfw_gdrive_access_token' );
		if ( $cached ) {
			return $cached;
		}

		$tokens = $this->get_tokens();
		if ( empty( $tokens['pgfw_gdrive_refresh_token'] ) ) {
			return new WP_Error( 'pgfw_gdrive_no_token', __( 'Google Drive is not connected.', 'pdf-generator-for-wp' ) );
		}

		$response = wp_remote_post(
			'https://oauth2.googleapis.com/token',
			array(
				'timeout' => 30,
				'body'    => array(
					'client_id'     => $settings['pgfw_gdrive_client_id'],
					'client_secret' => $settings['pgfw_gdrive_client_secret'],
					'refresh_token' => $tokens['pgfw_gdrive_refresh_token'],
					'grant_type'    => 'refresh_token',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['access_token'] ) ) {
			return new WP_Error(
				'pgfw_gdrive_token_error',
				! empty( $body['error_description'] ) ? $body['error_description'] : __( 'Unable to refresh Google Drive access token.', 'pdf-generator-for-wp' )
			);
		}

		$expires_in = ! empty( $body['expires_in'] ) ? (int) $body['expires_in'] : 3600;
		set_transient( 'pgfw_gdrive_access_token', $body['access_token'], max( 60, $expires_in - 60 ) );

		return $body['access_token'];
	}

	/**
	 * Get a short-lived Dropbox access token, refreshing (and caching) as needed.
	 *
	 * @param array $settings Cloud storage settings.
	 * @return string|WP_Error
	 */
	private function get_dropbox_access_token( $settings ) {
		$cached = get_transient( 'pgfw_dropbox_access_token' );
		if ( $cached ) {
			return $cached;
		}

		$tokens = $this->get_tokens();
		if ( empty( $tokens['pgfw_dropbox_refresh_token'] ) ) {
			return new WP_Error( 'pgfw_dropbox_no_token', __( 'Dropbox is not connected.', 'pdf-generator-for-wp' ) );
		}

		$response = wp_remote_post(
			'https://api.dropboxapi.com/oauth2/token',
			array(
				'timeout' => 30,
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $settings['pgfw_dropbox_app_key'] . ':' . $settings['pgfw_dropbox_app_secret'] ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
				),
				'body'    => array(
					'grant_type'    => 'refresh_token',
					'refresh_token' => $tokens['pgfw_dropbox_refresh_token'],
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( empty( $body['access_token'] ) ) {
			return new WP_Error(
				'pgfw_dropbox_token_error',
				! empty( $body['error_summary'] ) ? $body['error_summary'] : __( 'Unable to refresh Dropbox access token.', 'pdf-generator-for-wp' )
			);
		}

		$expires_in = ! empty( $body['expires_in'] ) ? (int) $body['expires_in'] : 3600;
		set_transient( 'pgfw_dropbox_access_token', $body['access_token'], max( 60, $expires_in - 60 ) );

		return $body['access_token'];
	}

	/**
	 * Determine which providers are enabled AND fully configured.
	 *
	 * @param array $settings Cloud storage settings.
	 * @return array List of provider slugs: google_drive, dropbox, s3.
	 */
	private function get_configured_providers( $settings ) {
		$providers = array();
		$tokens    = $this->get_tokens();

		if ( 'yes' === ( isset( $settings['pgfw_gdrive_enable'] ) ? $settings['pgfw_gdrive_enable'] : '' )
			&& ! empty( $settings['pgfw_gdrive_client_id'] )
			&& ! empty( $settings['pgfw_gdrive_client_secret'] )
			&& ! empty( $tokens['pgfw_gdrive_refresh_token'] ) ) {
			$providers[] = 'google_drive';
		}

		if ( 'yes' === ( isset( $settings['pgfw_dropbox_enable'] ) ? $settings['pgfw_dropbox_enable'] : '' )
			&& ! empty( $settings['pgfw_dropbox_app_key'] )
			&& ! empty( $settings['pgfw_dropbox_app_secret'] )
			&& ! empty( $tokens['pgfw_dropbox_refresh_token'] ) ) {
			$providers[] = 'dropbox';
		}

		if ( 'yes' === ( isset( $settings['pgfw_s3_enable'] ) ? $settings['pgfw_s3_enable'] : '' )
			&& ! empty( $settings['pgfw_s3_access_key'] )
			&& ! empty( $settings['pgfw_s3_secret_key'] )
			&& ! empty( $settings['pgfw_s3_bucket'] )
			&& ! empty( $settings['pgfw_s3_region'] ) ) {
			$providers[] = 's3';
		}

		return $providers;
	}

	/**
	 * Upload a rendered Dompdf document to every enabled & configured cloud provider.
	 *
	 * Call this after $dompdf->render() (and after wps_pgfw_apply_pdf_security(),
	 * if used, so password protection is included in the uploaded copy too).
	 * No-ops quickly (without touching $dompdf->output()) when cloud storage is
	 * disabled or nothing is configured, so it's safe to call unconditionally.
	 *
	 * @param \Dompdf\Dompdf $dompdf    Rendered Dompdf instance.
	 * @param string         $file_name Destination file name, e.g. "invoice-12.pdf".
	 * @return void
	 */
	public function upload_dompdf_output( $dompdf, $file_name = 'document.pdf' ) {
		if ( ! is_object( $dompdf ) ) {
			return;
		}

		$settings = $this->get_settings();
		if ( 'yes' !== ( isset( $settings['pgfw_cloud_storage_enable'] ) ? $settings['pgfw_cloud_storage_enable'] : '' ) ) {
			return;
		}

		$providers = $this->get_configured_providers( $settings );
		if ( empty( $providers ) ) {
			return;
		}

		$file_name = sanitize_file_name( $file_name );
		if ( '' === $file_name ) {
			$file_name = 'document.pdf';
		}
		if ( '.pdf' !== strtolower( substr( $file_name, -4 ) ) ) {
			$file_name .= '.pdf';
		}

		$pdf_content = $dompdf->output();
		if ( empty( $pdf_content ) ) {
			return;
		}

		foreach ( $providers as $provider ) {
			$method = 'upload_to_' . $provider;
			if ( ! method_exists( $this, $method ) ) {
				continue;
			}
			$result = $this->{$method}( $pdf_content, $file_name, $settings );

			/**
			 * Fires after an upload attempt to a cloud storage provider.
			 *
			 * @param string        $provider  Provider slug: google_drive|dropbox|s3.
			 * @param bool|WP_Error $result    True on success, WP_Error on failure.
			 * @param string        $file_name Uploaded file name.
			 */
			do_action( 'wps_pgfw_cloud_storage_upload_result', $provider, $result, $file_name );

			if ( is_wp_error( $result ) ) {
				error_log( sprintf( '[PDF Generator For WP] Cloud storage upload to %1$s failed for %2$s: %3$s', $provider, $file_name, $result->get_error_message() ) ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
			}
		}
	}

	/**
	 * Normalize a WP HTTP API response into a simple true/WP_Error result.
	 *
	 * @param array|WP_Error $response HTTP API response.
	 * @param array          $ok_codes Response codes considered a success.
	 * @return true|WP_Error
	 */
	private function handle_response( $response, $ok_codes = array( 200 ) ) {
		if ( is_wp_error( $response ) ) {
			return $response;
		}
		$code = (int) wp_remote_retrieve_response_code( $response );
		if ( ! in_array( $code, $ok_codes, true ) ) {
			return new WP_Error( 'pgfw_cloud_upload_failed', wp_remote_retrieve_body( $response ) );
		}
		return true;
	}

	/**
	 * Upload the PDF to Google Drive.
	 *
	 * @param string $pdf_content Raw PDF bytes.
	 * @param string $file_name   Destination file name.
	 * @param array  $settings    Cloud storage settings.
	 * @return true|WP_Error
	 */
	private function upload_to_google_drive( $pdf_content, $file_name, $settings ) {
		$access_token = $this->get_google_drive_access_token( $settings );
		if ( is_wp_error( $access_token ) ) {
			return $access_token;
		}

		$metadata = array( 'name' => $file_name );
		if ( ! empty( $settings['pgfw_gdrive_folder_id'] ) ) {
			$metadata['parents'] = array( sanitize_text_field( $settings['pgfw_gdrive_folder_id'] ) );
		}

		$boundary = wp_generate_password( 24, false );
		$body     = "--{$boundary}\r\n";
		$body    .= "Content-Type: application/json; charset=UTF-8\r\n\r\n";
		$body    .= wp_json_encode( $metadata ) . "\r\n";
		$body    .= "--{$boundary}\r\n";
		$body    .= "Content-Type: application/pdf\r\n\r\n";
		$body    .= $pdf_content . "\r\n";
		$body    .= "--{$boundary}--";

		$response = wp_remote_post(
			'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart',
			array(
				'timeout' => 60,
				'headers' => array(
					'Authorization' => 'Bearer ' . $access_token,
					'Content-Type'  => 'multipart/related; boundary=' . $boundary,
				),
				'body'    => $body,
			)
		);

		return $this->handle_response( $response, array( 200, 201 ) );
	}

	/**
	 * Upload the PDF to Dropbox.
	 *
	 * @param string $pdf_content Raw PDF bytes.
	 * @param string $file_name   Destination file name.
	 * @param array  $settings    Cloud storage settings.
	 * @return true|WP_Error
	 */
	private function upload_to_dropbox( $pdf_content, $file_name, $settings ) {
		$access_token = $this->get_dropbox_access_token( $settings );
		if ( is_wp_error( $access_token ) ) {
			return $access_token;
		}

		$folder = ! empty( $settings['pgfw_dropbox_folder_path'] ) ? '/' . trim( $settings['pgfw_dropbox_folder_path'], '/' ) : '';
		$path   = $folder . '/' . $file_name;

		$response = wp_remote_post(
			'https://content.dropboxapi.com/2/files/upload',
			array(
				'timeout' => 60,
				'headers' => array(
					'Authorization'   => 'Bearer ' . $access_token,
					'Dropbox-API-Arg' => wp_json_encode(
						array(
							'path'       => $path,
							'mode'       => 'overwrite',
							'autorename' => true,
							'mute'       => true,
						)
					),
					'Content-Type'    => 'application/octet-stream',
				),
				'body'    => $pdf_content,
			)
		);

		return $this->handle_response( $response, array( 200 ) );
	}

	/**
	 * Upload the PDF to Amazon S3 using a hand-rolled AWS Signature Version 4 PUT
	 * request (no AWS SDK dependency).
	 *
	 * @param string $pdf_content Raw PDF bytes.
	 * @param string $file_name   Destination file name.
	 * @param array  $settings    Cloud storage settings.
	 * @return true|WP_Error
	 */
	private function upload_to_s3( $pdf_content, $file_name, $settings ) {
		$region     = trim( $settings['pgfw_s3_region'] );
		$bucket     = trim( $settings['pgfw_s3_bucket'] );
		$access_key = trim( $settings['pgfw_s3_access_key'] );
		$secret_key = trim( $settings['pgfw_s3_secret_key'] );
		$prefix     = ! empty( $settings['pgfw_s3_folder'] ) ? trim( $settings['pgfw_s3_folder'], '/' ) . '/' : '';
		$object_key = $prefix . $file_name;

		$host             = "{$bucket}.s3.{$region}.amazonaws.com";
		$encoded_key_path = implode( '/', array_map( 'rawurlencode', explode( '/', $object_key ) ) );
		$url              = "https://{$host}/{$encoded_key_path}";

		$amz_date     = gmdate( 'Ymd\THis\Z' );
		$date_stamp   = gmdate( 'Ymd' );
		$payload_hash = hash( 'sha256', $pdf_content );

		$canonical_headers = "host:{$host}\nx-amz-content-sha256:{$payload_hash}\nx-amz-date:{$amz_date}\n";
		$signed_headers    = 'host;x-amz-content-sha256;x-amz-date';

		$canonical_request = implode(
			"\n",
			array(
				'PUT',
				"/{$encoded_key_path}",
				'',
				$canonical_headers,
				$signed_headers,
				$payload_hash,
			)
		);

		$credential_scope = "{$date_stamp}/{$region}/s3/aws4_request";
		$string_to_sign   = implode(
			"\n",
			array(
				'AWS4-HMAC-SHA256',
				$amz_date,
				$credential_scope,
				hash( 'sha256', $canonical_request ),
			)
		);

		$signing_key = $this->s3_signing_key( $secret_key, $date_stamp, $region );
		$signature   = hash_hmac( 'sha256', $string_to_sign, $signing_key );

		$authorization = "AWS4-HMAC-SHA256 Credential={$access_key}/{$credential_scope}, SignedHeaders={$signed_headers}, Signature={$signature}";

		$response = wp_remote_request(
			$url,
			array(
				'method'  => 'PUT',
				'timeout' => 60,
				'headers' => array(
					'Host'                 => $host,
					'x-amz-date'           => $amz_date,
					'x-amz-content-sha256' => $payload_hash,
					'Authorization'        => $authorization,
					'Content-Type'         => 'application/pdf',
				),
				'body'    => $pdf_content,
			)
		);

		return $this->handle_response( $response, array( 200 ) );
	}

	/**
	 * Derive the AWS Signature V4 signing key.
	 *
	 * @param string $secret_key AWS secret access key.
	 * @param string $date_stamp Date in YYYYMMDD format.
	 * @param string $region     AWS region, e.g. us-east-1.
	 * @return string Binary signing key.
	 */
	private function s3_signing_key( $secret_key, $date_stamp, $region ) {
		$k_date    = hash_hmac( 'sha256', $date_stamp, 'AWS4' . $secret_key, true );
		$k_region  = hash_hmac( 'sha256', $region, $k_date, true );
		$k_service = hash_hmac( 'sha256', 's3', $k_region, true );
		return hash_hmac( 'sha256', 'aws4_request', $k_service, true );
	}
}
