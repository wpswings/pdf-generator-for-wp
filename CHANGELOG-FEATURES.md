# PDF Generator For WP — Feature Changelog (this session)

Consolidated, feature-by-feature summary of everything added/changed in the
plugin so far. Each feature also has its own deep-dive doc (design
decisions, data schemas, testing checklists) — this file is the map; the
individual `PDF-*.md` files are the territory.

---

## 1. PDF Password Protection & Encryption
**Doc:** `PDF-PASSWORD-PROTECTION.md`

- Admins can set a global password (General Settings → *Enable PDF Password
  Protection* + *PDF Password*) that encrypts every generated PDF (RC4, via
  dompdf's bundled `Cpdf` class) so it requires that password to open.
- **Per-post/page/product override**: a new "PDF Password Protection"
  metabox on the post/page/product edit screen lets an admin set a
  different password for one specific item. An override always wins over
  the global password when set, and — importantly — protects that item
  even if the global toggle is off.
- **Sharing the password with the customer**: since a protected invoice is
  useless without its password, the resolved password is now shown to the
  customer on the order-details table (thank-you page + My Account → View
  Order), in order emails, and on the admin order screen — all no-ops when
  no password protection applies.
- **Critical shared bug found & fixed**: dompdf's bundled `Cpdf.php`
  (`package/lib/dompdf/vendor/dompdf/dompdf/lib/Cpdf.php`) mutated the PDF's
  permissions value (`/P`) in place every time `output()` ran. Since this
  plugin calls `output()`/`stream()` more than once per PDF in several
  places, a second call flipped `/P` back to an invalid positive value —
  silently corrupting the encryption key so **no password, not even the
  correct one, could open the file**. Fixed with a one-line change so the
  conversion no longer mutates persistent state. Verified directly against
  the real PDF password-authentication algorithm (not just theory) — see
  the doc for the repro/fix confirmation.
- Key files: `includes/pdf-generator-for-wp-global-functions.php`
  (`wps_pgfw_get_pdf_password()`, `wps_pgfw_apply_pdf_security()`),
  `admin/class-pdf-generator-for-wp-admin.php` (metabox +
  order-details/email/admin notices), `common/class-pdf-generator-for-wp-common.php`,
  `package/lib/dompdf/vendor/dompdf/dompdf/lib/Cpdf.php` (bug fix).

## 2. Cloud Storage Integration
**Doc:** `PDF-CLOUD-STORAGE.md`

- New **Cloud Storage** settings tab: every generated PDF can be
  auto-uploaded to **Google Drive**, **Dropbox**, and/or **Amazon S3** — any
  combination, independently enabled, in addition to (not instead of) each
  PDF's normal local delivery.
- Google Drive & Dropbox use standard OAuth2 ("bring your own app" —
  Client ID/Secret pasted in, Connect/Disconnect buttons); Amazon S3 uses
  static IAM credentials signed with a hand-rolled AWS Signature V4
  implementation. No external SDKs added — everything goes through
  `wp_remote_request()`.
- OAuth refresh tokens are stored in a **separate option** from the
  settings form so saving settings can never wipe out a live connection.
- New file: `includes/class-pdf-generator-for-wp-cloud-storage.php`
  (`Pdf_Generator_For_Wp_Cloud_Storage`) — upload logic, OAuth
  connect/callback/disconnect handlers.
- Hooked in at the same 5 render sites used for password protection, right
  after encryption is applied, so an uploaded copy matches exactly what the
  user/customer receives (including password protection).

## 3. Scheduled / Automatic PDF Regeneration
**Doc:** `PDF-AUTO-REGENERATION.md`

- Found and completed a half-built feature: the "Select Post Type" field in
  Advanced Settings ("save as a PDF on server with weekly update") was a
  locked, non-functional Pro-teaser field wired to a function
  (`cron_job_wpg_common_generate_pdf()`) that was never actually hooked to
  anything — and had a bug that would have prevented it from ever working
  even if it had been called.
- **Bug fixed**: the cached-file "overwrite if exists" check was inverted
  (`if ( ! file_exists( $path ) ) { unlink(...) }` — a no-op), so a stale
  cached PDF could never actually be refreshed.
- **Unlocked the field for real** and wired it up: selecting post types now
  makes the plugin auto-regenerate that type's cached on-server PDF
  whenever an item of that type is saved (via a background WP-Cron event,
  so it doesn't slow down the post editor), plus a weekly bulk-refresh cron
  as a safety net for content changed outside a normal save (imports, direct
  DB edits).
- Key files: `common/class-pdf-generator-for-wp-common.php` (bug fix +
  3 new methods), `admin/class-pdf-generator-for-wp-admin.php` (field
  unlocked), `includes/class-pdf-generator-for-wp.php` (hook registration).

## 4. Drag & Drop PDF Template Builder
**Doc:** `PDF-BUILDER.md`

- New **PDF Builder** tab: a real, working canvas editor (the existing
  "Layout Settings"/"Cover Page" tabs are non-functional Pro-teaser mockups
  and were deliberately left untouched) where admins freely position
  **Text, Image, Meta Field, and Rectangle/Divider** blocks per post type,
  instead of being limited to the fixed header/body/footer settings model.
- **Multi-page layouts**, **layering** (bring to front/send to back),
  **duplicate block**, **snap-to-grid**, **bold/italic** text formatting,
  **background color + border** on every block type.
- **Page background color** and a **watermark** (text, color, opacity, font
  size) per post-type layout.
- **12 predefined reference templates** (Minimal, Classic Report, Modern
  Card, Invoice, Certificate, Magazine Cover, Two-Column Brochure,
  Corporate Letterhead, Dark Mode, Elegant Serif, Receipt/Compact, Product
  Sheet), browsable in a gallery modal that shows a genuine **scaled-down
  preview of each template's actual block layout** (not just a flat color
  swatch), so admins can tell them apart at a glance.
- Full visual redesign of the tab (toggle switch, card-based panels, icon
  block palette, page tabs, template gallery) after the first version
  looked unfinished/unstyled.
- **Two real bugs found and fixed via direct testing against the plugin's
  actual bundled dompdf** (not just reasoning about it):
  1. Missing `@page`/body margin reset + a hardcoded A4-only canvas size
     caused dompdf's absolutely-positioned content to overflow the page and
     get re-painted onto extra blank pages. Fixed by zeroing margins and
     computing the canvas size from the site's *actual* configured page
     size/orientation.
  2. A trailing `page-break-after: always` marker (copied from the classic
     template's convention) was proven, by testing directly against the
     real dompdf, to force a genuine extra blank page even with nothing
     following it. Removed from the builder's own renderer.
- **Editor legibility fix**: some templates use white/near-white text meant
  to sit on a colored header block; once a block is moved onto the plain
  canvas that same text becomes invisible (not a data bug — the binding was
  always correct). Every block now shows a small always-legible badge
  (fixed dark background, white text) naming what it's bound to
  (`Post Title`, `Meta: price`, etc.), independent of the block's own
  color — editor-only, doesn't affect the generated PDF.
- Integration approach: rather than swapping in a second template *file*
  (which would risk a PHP fatal error from two files declaring the same
  global `return_ob_html()` function within one request — verified this
  would actually happen given the weekly regeneration cron above), the
  builder hooks *inside* the classic template's existing
  `return_ob_html()`, at the very top, via a uniquely-named dispatcher.
- New files: `admin/partials/pdf-generator-for-wp-builder.php` (tab UI),
  `admin/partials/pdf_templates/pdf-generator-for-wp-builder-render.php`
  (renderer + dispatcher), `admin/src/js/pdf-generator-for-wp-builder.js`,
  `admin/src/css/pdf-generator-for-wp-builder.css`. One line changed in
  `admin/partials/pdf_templates/pdf-generator-for-wp-admin-template1.php`
  to call the dispatcher.

---

## All files touched this session

**New files**
- `includes/class-pdf-generator-for-wp-cloud-storage.php`
- `admin/partials/pdf-generator-for-wp-cloud-storage.php`
- `admin/partials/pdf-generator-for-wp-builder.php`
- `admin/partials/pdf_templates/pdf-generator-for-wp-builder-render.php`
- `admin/src/css/pdf-generator-for-wp-builder.css`
- `admin/src/js/pdf-generator-for-wp-builder.js`
- `PDF-PASSWORD-PROTECTION.md`, `PDF-CLOUD-STORAGE.md`,
  `PDF-AUTO-REGENERATION.md`, `PDF-BUILDER.md`, this file.

**Modified files**
- `includes/class-pdf-generator-for-wp.php` — new tabs, hook registrations
  (metabox, AJAX, cron, filters) for all four features.
- `includes/pdf-generator-for-wp-global-functions.php` — password
  resolution/encryption helpers, cloud-storage upload hook, page-size helper.
- `admin/class-pdf-generator-for-wp-admin.php` — metabox callbacks, cloud
  storage settings page, builder tab data/AJAX/templates, Advanced Settings
  field unlock, enqueue additions.
- `common/class-pdf-generator-for-wp-common.php` — encryption/cloud-upload
  hooks at every render site, auto-regeneration scheduling + cron handlers.
- `admin/partials/pdf_templates/pdf-generator-for-wp-admin-template1.php` —
  one-line hook into the builder's renderer.
- `admin/src/js/pdf-generator-for-wp-admin-custom.js` — password field
  show/hide toggle.
- `admin/src/css/pdf-generator-for-wp-admin-custom.css` — status
  badges/link-button styling for the Cloud Storage tab.
- `package/lib/dompdf/vendor/dompdf/dompdf/lib/Cpdf.php` — the permissions
  mutation bug fix described in section 1 (a vendored third-party file;
  re-check this fix survives if dompdf is ever re-vendored from upstream).

Not part of this work (pre-existing local edits found in the working tree,
left as-is): `admin/src/css/pdf-generator-for-wp-admin-global.css`,
`admin/src/css/wps-admin.css`.
