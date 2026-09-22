# Cloud Storage Integration — Implementation Notes

## Goal

Auto-save every generated PDF to Google Drive, Dropbox and/or Amazon S3, so
documents aren't limited to living only on the local WordPress server.

## Design decisions

- **Additive, not destructive.** The plugin still behaves exactly as before
  (streams to the browser, saves locally, emails attachments, zips for bulk
  download, etc.). Cloud storage is bolted on as an *additional* destination
  for the same generated PDF bytes — nothing about existing local-save/stream
  code paths was changed or removed. This avoids risking the several existing
  features (WooCommerce invoice storage hook, bulk ZIP downloads, email
  attachments) that depend on the local file still being written.
- **No AWS/Google/Dropbox SDKs.** This plugin vendors its own dependencies
  manually under `package/lib/` (no Composer at the plugin root), and the
  official SDKs for these three providers are large. Instead, all three
  integrations are implemented directly on top of `wp_remote_request()` /
  `wp_remote_post()` — standard REST calls, OAuth2 token exchange, and (for
  S3) a self-contained AWS Signature Version 4 signer. No new files are
  added to `package/lib/`.
- **One shared hook point.** Every place in the plugin that renders a PDF
  (`$dompdf->render()`) already gets a single follow-up call —
  `wps_pgfw_apply_pdf_security( $dompdf )` (see `PDF-PASSWORD-PROTECTION.md`)
  — so the same pattern is reused here: `wps_pgfw_auto_save_pdf_to_cloud(
  $dompdf, $file_name )` is called right after it, at the same 5 render
  sites. Because it reads `$dompdf->output()` *after* password protection is
  applied, an encrypted PDF is what ends up in the cloud, matching what the
  visitor/admin receives locally.
- **Settings vs. tokens live in separate options.** The plugin's generic
  settings-tab save handler does `update_option( $key, $settings_from_form )`
  — a full replace, not a merge — on every save. OAuth refresh tokens are
  therefore **not** part of the settings form; they're written directly by
  the OAuth callback/disconnect handlers into their own option
  (`pgfw_cloud_storage_tokens`), so clicking "Save Settings" on the Cloud
  Storage tab can never wipe out a live connection.

## New tab: "Cloud Storage"

Added like every other settings tab in this plugin (matching the
`General`/`PDF Settings`/`Advanced` pattern):

- Tab entry in `wps_pgfw_plug_default_tabs()`
  (`includes/class-pdf-generator-for-wp.php`) → loads
  `admin/partials/pdf-generator-for-wp-cloud-storage.php` (tab key must equal
  the partial's filename, per `wps_pgfw_plug_load_template()`).
- Fields filter `pgfw_cloud_storage_settings_array` →
  `Pdf_Generator_For_Wp_Admin::pgfw_admin_cloud_storage_settings_page()`.
- Save handled for free by the existing generic
  `pgfw_admin_save_tab_settings()` switch (new
  `elseif ( isset( $_POST['pgfw_cloud_storage_save_settings'] ) )` branch),
  saving into option `pgfw_cloud_storage_save_settings` — no new AJAX/save
  code needed.

### Fields

- `pgfw_cloud_storage_enable` — master radio-switch (YES/NO).
- Per provider (`pgfw_gdrive_*`, `pgfw_dropbox_*`, `pgfw_s3_*`): an
  "Enable ___" checkbox + its credential fields, so any combination of
  providers can run simultaneously ("and/or" from the ask).
- Google Drive: Client ID, Client Secret, optional destination Folder ID,
  and a Connect/Disconnect button (OAuth).
- Dropbox: App Key, App Secret, optional Folder Path, and a
  Connect/Disconnect button (OAuth).
- Amazon S3: Access Key ID, Secret Access Key, Region, Bucket, optional
  Folder Prefix — no OAuth needed, uploads sign directly with these static
  keys.

A new `link-button` field type was added to the shared field renderer
(`Pdf_Generator_For_Wp::wps_pgfw_plug_generate_html()`) to render the
Connect/Disconnect `<a>` buttons plus a Connected/Not Connected status
badge — the existing `button`/`reset-button` types only render `<button
type="submit">` inside the settings form, which isn't right for an
OAuth redirect.

## Google Drive & Dropbox: OAuth setup

Both use the standard "bring your own app" OAuth2 pattern (same approach
used by e.g. WP All Import's Google Drive add-on): the site admin registers
an app once in Google Cloud Console / the Dropbox App Console, pastes the
Client ID/Secret (App Key/Secret) into the settings, then clicks Connect.

- **Redirect URI** to register with the provider is shown in the field
  description and is always:
  `wp-admin/admin-post.php?action=pgfw_cloud_storage_oauth_callback&provider=gdrive|dropbox`
- **Connect** — the settings page builds the provider's OAuth authorize URL
  directly (`get_google_drive_auth_url()` / `get_dropbox_auth_url()`) and
  renders it as the button's `href`; no admin-post round trip needed to
  *start* the flow.
  - Google: `access_type=offline&prompt=consent`, scope
    `drive.file` (least privilege — the app can only see files it
    creates, not the user's whole Drive).
  - Dropbox: `token_access_type=offline`.
- **Callback** — a single shared `admin_post_pgfw_cloud_storage_oauth_callback`
  action (`Pdf_Generator_For_Wp_Cloud_Storage::handle_oauth_callback()`)
  dispatches by `?provider=`, verifies the `state` nonce, exchanges the
  `code` for tokens, and stores the `refresh_token` in
  `pgfw_cloud_storage_tokens`.
- **Disconnect** — `admin_post_pgfw_cloud_storage_disconnect` removes the
  stored refresh token (nonce-protected via `check_admin_referer()`).
- **Access tokens** are short-lived (~1hr); `get_google_drive_access_token()`
  / `get_dropbox_access_token()` refresh them from the stored refresh token
  and cache the result in a transient for slightly less than its expiry, so
  a normal upload does one refresh call at most per hour, not per PDF.

## Amazon S3

No OAuth — static IAM credentials, signed per-request with AWS Signature
Version 4 (`upload_to_s3()` / `s3_signing_key()` in the new class), doing a
single `PUT` of the whole PDF to
`https://<bucket>.s3.<region>.amazonaws.com/<prefix><file>.pdf`. This is a
plain single-object PUT (no multipart/resumable upload), which is
appropriate for PDF-sized files; very large PDFs (multi-hundred-MB) are out
of scope.

The IAM user only needs `s3:PutObject` on the target bucket.

## New file

`includes/class-pdf-generator-for-wp-cloud-storage.php` —
`Pdf_Generator_For_Wp_Cloud_Storage`, required unconditionally in
`pdf_generator_for_wp_dependencies()` (not just in `is_admin()`), because
PDF generation — and therefore the upload call — also happens on the public
side of the site. Holds:

- `upload_dompdf_output( $dompdf, $file_name )` — the public entry point
  called from the 5 render sites. Bails out immediately (without even
  calling `$dompdf->output()`) unless the master toggle is on and at least
  one provider is enabled *and* fully configured.
- `upload_to_google_drive()`, `upload_to_dropbox()`, `upload_to_s3()` —
  per-provider upload.
- OAuth helpers and the `admin_post_*` handlers described above.

Registered from `includes/class-pdf-generator-for-wp.php` →
`pdf_generator_for_wp_admin_hooks()` (admin-post handlers run under
`is_admin()`, since `admin-post.php` is part of wp-admin).

## Where uploads are triggered

Same 5 render sites as PDF password protection:

| # | File | Context | Filename source |
|---|------|---------|------------------|
| 1 | `includes/pdf-generator-for-wp-global-functions.php` (`wps_generate_pdf()`) | Generic/shared PDF generation entry point | `$attr['file_name']` |
| 2 | `common/class-pdf-generator-for-wp-common.php` (~L392) | Single post/page/product → PDF | `$document_name` |
| 3 | `common/class-pdf-generator-for-wp-common.php` (~L668) | Bulk export from admin list table | `$document_name` |
| 4 | `common/class-pdf-generator-for-wp-common.php` (~L937) | WooCommerce order invoice generation | `$invoice_name` |
| 5 | `common/class-pdf-generator-for-wp-common.php` (~L1198) | Cron-based bulk PDF generation | `get_the_title( $prod_id )` |

## Error handling / observability

Upload failures don't interrupt PDF generation/delivery to the user — they
fire `do_action( 'wps_pgfw_cloud_storage_upload_result', $provider, $result,
$file_name )` (for anyone who wants to hook in their own logging/alerting)
and are also written to `error_log()` (visible in `debug.log` when
`WP_DEBUG_LOG` is enabled). A dedicated logs table/UI was intentionally
skipped to keep this change scoped — see Testing checklist below for how to
verify a connection is actually working.

## Explicitly out of scope

- Deleting the local copy after a successful cloud upload ("cloud-only"
  storage). Several existing features assume the local file exists after
  generation (WooCommerce invoice storage hook, bulk ZIP download, email
  attachments); wiring a safe opt-in deletion for every one of those code
  paths individually was left out to avoid touching working, unrelated
  functionality.
- Including `pgfw_cloud_storage_save_settings` in the plugin's "Reset to
  Default Settings" bulk action — intentionally, since these are live
  credentials/tokens, not display preferences.
- Per-document/random cloud paths or filenames beyond what's already used
  for local files.

## Testing checklist

1. Leave "Enable Cloud Storage" OFF → generate a PDF → behavior is
   unchanged, no network calls to any provider.
2. **Google Drive**: create an OAuth Client (Web application) in Google
   Cloud Console, add the redirect URI shown on the settings page, paste
   Client ID/Secret, Save Settings, click "Connect Google Drive", grant
   access → redirected back with a "Successfully connected!" notice and the
   button now reads "Disconnect Google Drive". Generate a PDF (post/page/
   product download, invoice, bulk export) → file appears in Drive.
3. **Dropbox**: same flow via the Dropbox App Console (Scoped App, `files.
   content.write` permission), using the redirect URI shown on the settings
   page.
4. **Amazon S3**: fill in Access Key/Secret/Region/Bucket for an IAM user
   with `s3:PutObject` on that bucket, enable, save → generate a PDF → file
   appears in the bucket (optionally under the configured folder prefix).
5. Enable more than one provider at once → generate a PDF → it lands in all
   of them.
6. Disconnect Google Drive/Dropbox → button reverts to "Connect ___" and no
   further uploads go to that provider until reconnected.
