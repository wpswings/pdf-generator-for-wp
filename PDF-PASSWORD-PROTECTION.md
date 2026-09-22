# PDF Password Protection & Encryption — Implementation Notes

## Goal

Let admins restrict access to generated PDFs (invoices, gated post/page/product
PDFs, bulk exports) by requiring a password to open them, so sensitive
documents aren't freely readable by anyone with the file URL/path.

## How PDF encryption works in this plugin

The plugin generates all PDFs with **Dompdf**
(`package/lib/dompdf/vendor/dompdf/dompdf`). Dompdf's default rendering
backend (`Dompdf\Adapter\CPDF`) wraps an internal `Dompdf\Cpdf` object
(`package/lib/dompdf/vendor/dompdf/dompdf/lib/Cpdf.php`) that already
implements standard PDF encryption (RC4, via `o_encryption()` /
`encryptInit()`):

```php
$dompdf->getCanvas()->get_cpdf()->setEncryption( $userPassword, $ownerPassword, $permissions );
```

- `$userPassword` — required to **open** the PDF.
- `$ownerPassword` — controls permissions (print/copy/modify/add); falls back
  to `$userPassword` internally if left empty.
- `$permissions` — assoc array of booleans: `print`, `modify`, `copy`, `add`.

This must be called **after `$dompdf->render()`** and **before**
`$dompdf->output()` / `$dompdf->stream()`, because encryption is applied to
the already-rendered PDF object tree.

No new library/dependency is needed — this is existing functionality already
bundled with dompdf, just not wired up in this plugin yet.

## Where PDFs are rendered

There are 5 places that call `$dompdf->render()` in this plugin, each needs
the same encryption hook applied right after render:

| # | File | Context |
|---|------|---------|
| 1 | `includes/pdf-generator-for-wp-global-functions.php` (`wps_generate_pdf()`) | Generic/shared PDF generation entry point |
| 2 | `common/class-pdf-generator-for-wp-common.php` (~L391) | Single post/page/product → PDF (poster/download button) |
| 3 | `common/class-pdf-generator-for-wp-common.php` (~L664) | Bulk export from admin list table |
| 4 | `common/class-pdf-generator-for-wp-common.php` (~L931) | WooCommerce order **invoice** generation |
| 5 | `common/class-pdf-generator-for-wp-common.php` (~L1190) | Cron-based bulk PDF generation |

Rather than duplicating the encryption logic 5 times, a single shared helper
is added and called from all 5 spots.

## Settings (General tab)

Setting is stored in the existing `pgfw_general_settings_save` option
(same option already used for all other General tab fields), so it reuses
the plugin's existing generic settings save handler
(`pgfw_admin_save_tab_settings()` in `admin/class-pdf-generator-for-wp-admin.php`)
— no new save/AJAX code needed.

New fields added to `pgfw_admin_general_settings_page()`:

- `pgfw_pdf_password_protection_enable` — `radio-switch` (YES/NO), same UI
  pattern as the existing "Enable Plugin" toggle. This is step 1 of the task:
  ship the enable/disable switch first, wired to the option, before adding
  any encryption behaviour behind it.
- `pgfw_pdf_password` — `password` field, the password required to open a
  generated PDF. Only shown/used when the toggle above is `yes`.

The `password` field type template
(`includes/class-pdf-generator-for-wp.php` → `wps_pgfw_plug_generate_html()`,
`case 'password':`) was extended to support `parent-class` + `style`
(mirroring the existing `text` case) so the field can be hidden by default
and toggled with JS, the same way `pgfw_custom_pdf_file_name` is toggled by
`pgfw_general_pdf_file_name`.

`admin/src/js/pdf-generator-for-wp-admin-custom.js` gets a small
show/hide handler for the new switch, following the existing
`.pgfw_general_pdf_file_name` `change` handler pattern.

## Encryption helper

Added to `includes/pdf-generator-for-wp-global-functions.php`:

```php
wps_pgfw_apply_pdf_security( $dompdf );
```

- Reads `pgfw_general_settings_save` option.
- No-ops if the toggle isn't `yes` or the password is empty (so existing
  behaviour is 100% unchanged by default).
- Otherwise calls `setEncryption()` with the configured password as both
  user and owner password, granting full print/copy/modify/add permissions
  (the goal is "requires a password to open", not restricting what a user
  can do once they're in).

## Known dompdf bug fixed as part of this change

**Symptom:** password protection turns on (a password prompt appears when
opening the PDF), but the *correct* password is rejected as "wrong
password" too.

**Root cause:** the vendored `Dompdf\Cpdf::output()`
(`package/lib/dompdf/vendor/dompdf/dompdf/lib/Cpdf.php`) can legitimately be
called more than once per document — this plugin does exactly that in
several places (e.g. `$output = $dompdf->output();` followed by
`$dompdf->stream(...)`, which internally calls `output()` again). The
`o_encryption()` `'out'` case converted the permissions value (`/P`) to its
two's-complement form **by mutating the stored value in place**
(`$o['info']['p'] = (($o['info']['p'] ^ 255) + 1) * -1;`). Since that
conversion is its own inverse under PHP's integer bitwise ops
(`f(f(252)) === 252`), a *second* `output()` call flips `/P` back to a
**positive** number instead of the correct negative one. The PDF's stored
`/O`/`/U`/`/P` values (used by every reader to re-derive the decryption key
from the typed password) no longer match the key that was actually used to
encrypt the content streams — so *no* password, correct or not, opens the
file.

**Fix:** `o_encryption()`'s `'out'` case now computes the converted value
into a local variable instead of overwriting `$o['info']['p']`, so repeated
`output()` calls are idempotent. This was verified directly: reverting the
fix and generating a PDF via two `output()` calls (as this plugin does)
reproduces the exact "correct password rejected" symptom; with the fix,
independently re-implementing the PDF spec's password-authentication
algorithm confirms the correct password now authenticates and a wrong one
is still rejected, on the byte stream actually delivered to the browser.

This is a one-line change in a vendored file, not something managed by this
plugin's own code — if `package/lib/dompdf` is ever re-vendored/upgraded
from upstream, re-check that this fix (or an upstream equivalent) is still
in place before shipping password protection.

## Per-post/page/product password override

Beyond the single global password, an admin can set a different password for
an individual post, page or (when WooCommerce is active) product, via a new
"PDF Password Protection" metabox on that item's edit screen (side column,
same slot/pattern as the existing Flipbook metabox).

- Registered in `admin/class-pdf-generator-for-wp-admin.php`:
  `wps_pgfw_add_pdf_password_metabox_callback()` (hooked to `add_meta_boxes`)
  adds the box to `post`, `page`, and `product` (product only if
  `woocommerce/woocommerce.php` is active, matching how this plugin checks
  for WooCommerce elsewhere). `wps_pgfw_pdf_password_metabox_render()` draws
  a single password field. `wps_pgfw_save_pdf_password_metabox_callback()`
  (hooked to the generic `save_post`, since three post types are involved)
  verifies its own nonce/capability before doing anything, so it's a no-op
  for any other post type or save request.
- Stored as post meta `_pgfw_pdf_password_override` (empty/deleted when the
  field is cleared, so removing the override is just saving it blank).
- **Precedence**: `wps_pgfw_apply_pdf_security( $dompdf, $post_id )`
  (`includes/pdf-generator-for-wp-global-functions.php`) now takes an
  optional `$post_id`. If that item has a non-empty override, it's used
  regardless of the global toggle — setting a password on one product
  protects that product's PDF even if password protection is globally off.
  Otherwise, behavior is unchanged: the global toggle + password apply.
- **Where it's wired up**: only the two render sites that have a genuine
  single post/product ID in scope pass it through —
  `pgfw_generate_pdf_from_library()` (single post/page/product download) and
  `cron_job_wpg_common_generate_pdf()` (cron-based bulk generation, one item
  at a time). The bulk multi-post export and the WooCommerce invoice
  generator (order-based, not product-based — an order can contain several
  products) still only receive `$post_id = 0`, i.e. global-password-only;
  there's no single product to key an override off in either of those paths.

## Sharing the invoice password with the customer

Since a protected invoice PDF is useless to a customer who doesn't know the
password, the resolved password (see `wps_pgfw_get_pdf_password()` above) is
also surfaced wherever an order's details are shown, via new WooCommerce
hooks (none of these existed in the plugin before this change):

- **Order details table** (covers both the order-received/thank-you page and
  My Account → Orders → View Order — WooCommerce renders both from the same
  template, hooked the same way): `woocommerce_order_details_after_order_table`
  → `Pdf_Generator_For_Wp_Common::wpg_show_invoice_pdf_password_notice()`.
- **Order emails** (any WooCommerce email that includes the standard order
  items table — confirmation, invoice, etc.): `woocommerce_email_after_order_table`
  → `Pdf_Generator_For_Wp_Common::wpg_show_invoice_pdf_password_notice_email()`,
  which renders plain text when `$plain_text` is true (WooCommerce's
  plain-text email alternative) and a short HTML paragraph otherwise.
- **Admin order edit screen** (classic and HPOS — the hook itself is
  unaffected by which order storage is active):
  `woocommerce_admin_order_data_after_order_details` →
  `Pdf_Generator_For_Wp_Admin::wpg_show_invoice_pdf_password_notice_admin()`,
  so support staff can see/share it without opening General Settings.

All three are no-ops (render nothing) when `wps_pgfw_get_pdf_password()`
resolves to an empty string, i.e. whenever password protection isn't active
— they add no visual clutter for merchants not using this feature. They're
also only registered when the plugin's invoice feature itself is enabled
(`wpg_enable_plugin` option — the same gate the plugin already uses for its
other order/invoice hooks), consistent with existing conventions.

Since an order can contain several different products, invoices only ever
use the **global** password (never a per-product override — see the
"per-post/page/product password override" section above), so
`wps_pgfw_get_pdf_password()` is called with no post ID at all three sites.

**Scope note:** this only *displays* the password — it does not attach the
invoice PDF to the order email. This plugin has no
`woocommerce_email_attachments` integration today (confirmed by search); if
invoices should also be emailed as an attachment, that's a separate,
larger feature (building the attachment pipeline itself) and wasn't part of
this change.

## Security notes / limitations

- The password is stored in plain text in the `pgfw_general_settings_save`
  option (consistent with how other plugin credentials/text settings are
  already stored in this codebase). It must be stored in plain text because
  the raw password is needed at generation time to encrypt each PDF.
- RC4 encryption via dompdf's `Cpdf` class is standard PDF "open password"
  protection (same as older Acrobat-compatible PDFs). It's suitable for
  access-gating, not a substitute for transport security (still serve PDFs
  over HTTPS).
- This is a single, global password for all generated PDFs (matches the
  scope of the ask). Per-document/random passwords are out of scope here.

## Testing checklist

1. General tab → toggle "Enable PDF Password Protection" to NO, leave
   password blank → generate a PDF → opens normally, no password prompt.
2. Toggle ON, set a password, save → generate a PDF (post/page/product
   download, bulk export, WooCommerce invoice) → PDF viewer prompts for a
   password; correct password opens it, wrong password is rejected.
3. Toggle ON but password left blank → generation falls back to unprotected
   (no accidental lockout with an empty password).
