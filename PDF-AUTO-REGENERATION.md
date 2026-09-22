# Scheduled / Automatic PDF Regeneration — Implementation Notes

## Goal

When a source post/page/product is updated, its cached PDF (the copy this
plugin keeps saved on the server) should refresh automatically, instead of
the admin having to manually regenerate it.

## What was already there (and why it didn't work)

This plugin already had a "Select Post Type" field in **Advanced Settings**
(`admin/class-pdf-generator-for-wp-admin.php` →
`pgfw_admin_advanced_settings_page()`) whose own description says: *"Select
all post types that you want save as a PDF on server with weekly update."*
That's exactly this feature — but it turned out to be unfinished, not built:

- It was marked with the `wps_pgfw_pro_tag` class — this plugin's convention
  for a locked/grayed-out Pro-upsell placeholder (JS/CSS in
  `admin/src/js/pdf-generator-for-wp-admin.js:38-40` and
  `admin/src/css/pdf-generator-for-wp-admin-common.css` hide/disable any
  field with that class and show a "PRO" badge instead).
- Its `options` was a literal empty string (`''`) and its `value` a
  hardcoded `'posts'` string — it never actually read from or saved to the
  `pgfw_advanced_save_settings` option like every other field on that tab.
- The function that would have used it, `cron_job_wpg_common_generate_pdf(
  $prod_id, $type, $action )` (`common/class-pdf-generator-for-wp-common.php`),
  existed and was fully written, but **was never registered as a hook or
  scheduled anywhere** — confirmed by a full-plugin search; it was
  unreachable dead code.
- It also had a bug that would have defeated the whole point even if it had
  been wired up: the "on server" cache-write guard was
  `if ( ! file_exists( $path ) ) { @unlink( $path ); }` — i.e. it only
  unlinked the file when it *didn't* exist (a no-op), so an existing cached
  PDF could never actually be replaced. Fixed to
  `if ( file_exists( $path ) ) { @unlink( $path ); }`.

Given this was clearly meant to be a real (Pro) feature and not just
placeholder copy, and per the user's explicit choice, this change completes
it for real rather than building a separate parallel free-tier toggle.

## What changed

1. **Bug fix** — `cron_job_wpg_common_generate_pdf()`'s file-exists guard
   (above), so regeneration actually overwrites the stale file.
2. **Unlocked the "Select Post Type" field** — removed `wps_pgfw_pro_tag`,
   wired its `value`/`options` to the real option (`pgfw_advanced_save_settings`
   → `pgfw_advanced_post_on_server`) and the site's actual public post types
   (same list already used by "Show Icons for Post Type" right above it), and
   updated its description to explain both triggers below. No changes were
   needed to the generic settings-save handler — multiselect fields already
   save as an array automatically.
3. **Save-triggered regeneration** — new
   `Pdf_Generator_For_Wp_Common::wps_pgfw_maybe_schedule_pdf_regeneration( $post_id, $post, $update )`,
   hooked to the generic `save_post` (fires for posts, pages, and products
   alike, matching the pattern already used by this session's PDF-password-
   override metabox). It skips autosaves/revisions and unpublished posts,
   checks whether that post's type is one of the selected "Select Post Type"
   values, and — if so — schedules a **single background WP-Cron event**
   (`pgfw_regenerate_pdf_on_server_event`, ~1 minute out via
   `wp_schedule_single_event`) rather than regenerating synchronously during
   the save request. Dompdf rendering can take a noticeable moment; doing it
   inline would slow down every save in the post editor for no benefit to
   the person saving.
4. **Weekly refresh (matches the field's own "weekly update" description)**
   — `pgfw_schedule_weekly_pdf_regeneration()` ensures a `weekly`
   `pgfw_cron_weekly_regenerate_pdfs` event is scheduled (checked on every
   `init`, mirroring `pgfw_delete_pdf_form_server_scheduler`'s existing
   pattern for the plugin's other weekly cron). Its handler,
   `pgfw_cron_regenerate_all_pdfs()`, regenerates the on-server PDF for
   *every* published post of the selected type(s) — a safety net for content
   that changed outside a normal `save_post` (import, direct DB update,
   etc.), not just a relative-time re-run of the same items.
5. **Registration** — all four hooks are registered in
   `pdf_generator_for_wp_common_hooks()` (`includes/class-pdf-generator-for-wp.php`),
   which runs unconditionally on every request (including an actual
   `wp-cron.php` run, where `is_admin()` is false) — deliberately *not*
   placed in the admin-only hook method, so the cron handlers are guaranteed
   to be registered when WP-Cron itself fires them.

## Where the regenerated file goes

Same as before (unchanged): `wp-content/uploads/bulk_pdf/{post title}.pdf`,
served via `download_on_server`. Note the cache key is the **post title**,
not the post ID — a pre-existing quirk of `cron_job_wpg_common_generate_pdf()`
this change didn't alter; renaming a post's title will leave behind an
orphaned file under the old title rather than renaming it in place.

## Explicitly out of scope

- Batching/throttling the weekly bulk-regeneration loop for very large
  catalogs (it currently regenerates every matching post in one cron run).
  Fine for typical site sizes; a large catalog may want this chunked in a
  future pass.
- Clearing the scheduled weekly event on plugin deactivation — the
  plugin's existing weekly cron (`pgfw_cron_delete_pdf_from_server`) doesn't
  do this either, so this change follows the same existing (imperfect)
  convention rather than introducing an inconsistent new one.

## Testing checklist

1. Advanced Settings → "Select Post Type" is no longer grayed out/PRO-tagged;
   select e.g. `post`, save.
2. Edit and update a published post → within about a minute (WP-Cron
   permitting - on sites without real server cron, this fires on the next
   front-end visit), `wp-content/uploads/bulk_pdf/{post title}.pdf` is
   rewritten with the new content (check its modified time/content).
3. Manually trigger `do_action( 'pgfw_cron_weekly_regenerate_pdfs' )` (e.g.
   via WP-CLI `wp cron event run pgfw_cron_weekly_regenerate_pdfs`) →
   every published post of the selected type gets its on-server PDF
   refreshed.
4. Leave "Select Post Type" empty → saving posts does nothing extra, no
   cron events get scheduled.
