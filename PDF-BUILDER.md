# Drag & Drop PDF Template Builder — Implementation Notes

## Goal

Let admins freely position text, image, and post-meta blocks on a canvas to
design a PDF layout, instead of being limited to the fixed header/body/footer
settings model. This is additive/opt-in per post type — it augments the
existing model rather than removing it.

## What already existed (and why it isn't touched)

- **"Layout Settings" / "Cover Page Setting" tabs are non-functional Pro
  teasers.** Every field they render carries the `wps_pgfw_pro_tag` class,
  which `admin/src/css/pdf-generator-for-wp-admin-common.css` turns into a
  disabled (`pointer-events: none`), "PRO"-ribboned mock UI, and their
  backing PHP methods are literally named `*_dummy()`
  (`pgfw_cover_page_html_layout_fields_dummy()`,
  `pgfw_template_pdf_settings_page_dummy()` in
  `admin/class-pdf-generator-for-wp-admin.php`). This builder does **not**
  extend or unlock those — it's a new, separate, genuinely-working tab.
- **How a PDF's HTML is actually built today**: `pgfw_generate_pdf_from_library()`
  (`common/class-pdf-generator-for-wp-common.php`) `require_once`s
  `admin/partials/pdf_templates/pdf-generator-for-wp-admin-template1.php`
  and calls its global `return_ob_html( $post_id, $template_name )`, which is
  one long function that string-concatenates `post_title`/`post_content`/
  featured image/taxonomy/meta fields (from the Meta Fields tab's selections)
  into an HTML string, fed to `$dompdf->loadHtml()`. There is no
  templating/placeholder syntax anywhere in the plugin (confirmed by search)
  — everything is raw PHP string building.

## Design decision: don't swap template files

The plugin already has an extension point for swapping in a different
template file per post type: `apply_filters( 'pgfw_load_templates_for_pdf_html',
$template_file_name, $template_name, $post_id )`, followed by
`require_once $template_file_name;`. The obvious-looking approach — return a
different builder template file from that filter when a builder layout
exists — was **rejected** after tracing a real crash it would cause:

`return_ob_html()` is a **global function**. If the filter is used to
`require_once` a second, different file that *also* defines
`function return_ob_html(...)`, and both a classic-template post and a
builder-enabled post get generated within the *same PHP request* (this is
not hypothetical — the auto-regeneration weekly cron added earlier this
session, `pgfw_cron_regenerate_all_pdfs()`, loops over every post of every
selected post type in one request), PHP would hit a fatal
"Cannot redeclare return_ob_html()" the moment the second, differently-typed
post is processed.

**Instead**, the builder hooks *inside* the existing `return_ob_html()`,
at the top, before any of its classic logic runs:

```php
// admin/partials/pdf_templates/pdf-generator-for-wp-admin-template1.php
function return_ob_html( $post_id, $template_name = '' ) {
    require_once PDF_GENERATOR_FOR_WP_DIR_PATH . 'admin/partials/pdf_templates/pdf-generator-for-wp-builder-render.php';
    $pgfw_builder_html = pgfw_builder_maybe_render( $post_id );
    if ( null !== $pgfw_builder_html ) {
        return $pgfw_builder_html;
    }
    // ...existing classic template logic, unchanged...
```

`pgfw_builder_maybe_render( $post_id )` lives in its own file with a
**uniquely-named** function (`admin/partials/pdf_templates/pdf-generator-for-wp-builder-render.php`)
— safe to `require_once` unconditionally on every call, no redeclare risk,
no filter needed. It returns the rendered HTML string when a builder layout
applies to that post's type and the master toggle is on, or `null` to fall
through to the classic renderer otherwise. This also means a single bulk/
weekly-regeneration run can correctly mix builder-rendered and
classic-rendered posts of different types in the same request — something
the filter-swap approach could not safely do at all.

## Canvas & data model

- Canvas size is **computed from the plugin's actual configured page
  size/orientation** (Body Settings), converted to CSS px at dompdf's 96dpi
  reference — see "Fixed: enabling the builder produced blank pages" below.
  Blocks are stored and rendered in that same coordinate space, so what's
  built in the editor lines up with the generated PDF.
- One layout per **post type** (post/page/product/...), selected via a
  dropdown at the top of the builder — not one global layout — so a product
  PDF and a blog post PDF can look completely different. Each layout is a
  **list of pages**, navigated via page tabs in the builder.
- Stored in a single option, `pgfw_pdf_builder_settings`:
  ```php
  array(
      'pgfw_pdf_builder_enable' => 'yes' | 'no',   // master toggle
      'layouts' => array(
          'post'    => array( 'pages' => array(
              array( 'blocks' => array( [block, block, ...] ) ),  // page 1
              array( 'blocks' => array( ... ) ),                  // page 2
          ) ),
          'page'    => array( 'pages' => array( ... ) ),
          'product' => array( 'pages' => array( ... ) ),
      ),
  )
  ```
  Layouts saved before multi-page support (a bare `{ blocks: [...] }`) are
  still read correctly, treated as a one-page layout, by both the editor and
  the renderer.
- Each block: `id, type (text|image|meta|rectangle), x, y, width, height,
  font_size, color, align, bold, italic, background_color, border_width,
  border_color, source, content, meta_key, label`. `source` covers where a
  text/image block's value comes from: `static` (the block's own `content`),
  or one of `post_title | post_date | post_author | post_excerpt |
  post_content | featured_image`. `meta` blocks resolve `meta_key` via
  `get_post_meta()`. `rectangle` blocks ignore the text-only fields and just
  render a filled/bordered box.
- **Meta field palette reuses the Meta Fields tab's existing selections**
  (`pgfw_meta_fields_save_settings` → `pgfw_meta_fields_{post_type}_list`)
  rather than re-scanning every post's meta keys again — if a key isn't
  offered in the builder, the admin adds it on the Meta Fields tab first,
  keeping the two features consistent instead of duplicating discovery
  logic.
- A per-post-type layout only takes effect once it has at least one block
  saved; post types with no saved layout keep using the classic
  header/body/footer template exactly as before, even with the master
  toggle on.

## New tab: "PDF Builder"

Added the same way the "Cloud Storage" tab was added earlier this session:
a new entry in `wps_pgfw_plug_default_tabs()`
(`includes/class-pdf-generator-for-wp.php`), auto-routed to
`admin/partials/pdf-generator-for-wp-builder.php` by the existing
`admin/partials/{tab-key}.php` naming convention — no router changes needed.
Unlike Layout Settings, this tab has **no** `wps_pgfw_pro_tag` class
anywhere; it's a real, working feature.

The palette (Text / Image / Meta Field buttons), canvas, and a properties
panel for the selected block are custom-built — there's no drag-and-drop
library already vendored in this plugin. Dragging/resizing uses **jQuery UI
draggable/resizable**, enqueued via their WordPress-core script handles
(`jquery-ui-draggable`, `jquery-ui-resizable`) — no new library files needed
to be vendored. The image block's "choose image" button reuses the exact
`wp.media()` pattern already used for the header-logo/PDF-icon uploaders in
`admin/src/js/pdf-generator-for-wp-admin-custom.js`, and `wp_enqueue_media()`
is already loaded on every tab of this plugin's settings screen, so no new
enqueue was needed for that part.

Saving is a **dedicated AJAX action** (`wp_ajax_pgfw_save_pdf_builder_layout`)
rather than the generic settings-array form-save handler used by every other
tab — that handler is built for flat field arrays, not a freeform canvas's
dynamic block list. The handler sanitizes every field of every block
server-side (type allow-list, ints for position/size, `sanitize_hex_color`,
`wp_kses_post` for static text content, `sanitize_text_field` elsewhere)
before writing the option; nothing from the client is trusted as-is.

## Rendering

`pgfw_builder_maybe_render( $post_id )` looks up the post's type, checks the
master toggle + that type's saved blocks are non-empty, then renders each
block as an absolutely-positioned `<div style="position:absolute;
left:{x}px; top:{y}px; width:{w}px; height:{h}px; ...">` (an `<img>` for
image blocks) with its resolved value — `esc_html()` for plain text/meta
values, `wp_kses_post()` + the same `the_content`/`the_excerpt` filters the
classic template already applies for `post_content`/`post_excerpt`, so
shortcodes and formatting in post content still work. Ends with the same
`<span id="wps_page_break_point" style="page-break-after: always;">` marker
the classic template uses, so bulk multi-post PDFs still get correct page
breaks between builder-rendered items.

## Fixed: enabling the builder produced blank pages

**Symptom:** with the builder enabled, a downloaded PDF came back as several
blank pages instead of the designed layout.

**Root cause:** the canvas was sized to exactly one page's worth of CSS
pixels (`794×1123px` for A4), but dompdf applies its own non-zero default
`@page`/body margins unless the HTML explicitly zeroes them out (the classic
template already does this via its own `@page { margin-top: ...; }` block —
the builder's output didn't). With those default margins in place, the
actual *printable* area per page is smaller than 1123px tall, so the
canvas's absolutely-positioned content overflowed past the first page
boundary. dompdf has a well-known quirk where `position:absolute` content
whose containing block spans a page boundary gets **re-painted on every
page that block spans**, rather than being clipped — which is exactly what
produced the extra pages: page 1 had the real (barely-visible, pushed-up)
content, and the following pages were near-empty repaint artifacts of the
same overflowing container.

**Fix**, in `pgfw_builder_maybe_render()`:
1. Emit `<style>@page{margin:0;}html,body{margin:0;padding:0;}</style>`
   before the canvas markup, so the full page height is actually available
   and matches the canvas div's declared height exactly — no more overflow.
2. Stopped hardcoding `794×1123` (A4 portrait) and instead compute the
   canvas size from the plugin's **actual configured page size/orientation**
   (Body Settings → page size + orientation, including the `custom_page`
   width/height fields) via a new shared helper,
   `wps_pgfw_get_pdf_page_size_px()` in
   `includes/pdf-generator-for-wp-global-functions.php` — used by both the
   editor (so the canvas the admin designs against matches reality) and the
   renderer (so what's generated matches what was designed), converting
   dompdf's point-based paper-size arrays to CSS px the same way the rest of
   this codebase already sizes things (`pt × 96/72`).

## More advanced: what's new

- **Multi-page layouts.** A post type's layout is now a list of pages, each
  with its own canvas/blocks (`layouts[post_type] = { pages: [ { blocks:
  [...] }, ... ] }`), with page tabs in the builder UI to add/switch/delete
  pages. Pages are separated by `page-break-after: always` in the rendered
  PDF. Old single-page saves (`{ blocks: [...] }`) still load correctly —
  both the editor and the renderer treat that shape as a one-page layout.
- **Rectangle / Divider block** — a plain filled/bordered box, for section
  dividers, backgrounds, or visual structure, independent of text/image/meta
  content.
- **Background color + border** (width/color) are now available on every
  block type, not just rectangles — a Text or Meta block can be a labeled,
  bordered "card" instead of bare text.
- **Bold / Italic** for static text blocks, plus quick-format toolbar
  buttons that wrap the current textarea selection in `<strong>`/`<em>` (the
  renderer already allowed safe HTML in static text via `wp_kses_post()`;
  this just exposes it through the UI instead of requiring the admin to
  hand-type tags).
- **Layering** — "Bring to Front" / "Send to Back" reorder the selected
  block within its page's block array (paint order = array order, so this
  directly controls what overlaps what).
- **Duplicate block** — clones the selected block a few pixels offset from
  the original.
- **Snap to grid** — dragging/resizing snaps to a 10px grid by default
  (toggleable), for easier alignment between blocks.

## Round 2: templates, color, watermark, and hardening against blank pages

After the first pass shipped, testing surfaced two problems: the builder UI
looked bare/unfinished, and downloading a builder-enabled PDF still produced
extra blank pages in at least one test. This round addresses both.

### The actual, verified cause of the extra page

Round 1's hardening (`@page{margin:0;}`, dynamic page sizing, a 2px safety
margin, `max-height` + `page-break-inside:avoid`, a single root element)
turned out to all be correct but incomplete - an extra blank page was still
reported after shipping it. Rather than keep reasoning about dompdf from the
outside, this time it was tested directly against the actual vendored
dompdf build (`package/lib/dompdf`), which gave a definitive answer:

```php
$dompdf->loadHtml($html_with_trailing_span, 'UTF-8');
$dompdf->render();
$dompdf->getCanvas()->get_page_count();   // => 2, for ONE page of content
```

The trailing marker every render ended with -
`<span id="wps_page_break_point" style="page-break-after: always;">` -
**always forces a second, blank page in dompdf, even with nothing after it.**
That's not a browser-print convention dompdf happens to share; verified
directly, removing only that one span (nothing else changed) takes the same
HTML from 2 pages to the correct 1. A genuine two-page layout (internal
`page-break-after:always` between page 1 and page 2, no trailing marker at
all) was also verified to still correctly produce exactly 2 pages - so nothing
about actual multi-page layouts depends on that trailing span.

**Fix**: the trailing span is no longer emitted by `pgfw_builder_maybe_render()`
at all. Multi-page layouts already get a correct break *between* their own
pages (every page except the last one gets `page-break-after:always` on its
own canvas div), which - per the test above - is sufficient by itself.

This span exists in the *classic* (non-builder) template too
(`admin/partials/pdf_templates/pdf-generator-for-wp-admin-template1.php`)
and was carried over into the builder's renderer by matching that existing
convention; it likely has the same effect there for a single-post classic
download, but that file wasn't touched here - fixing the builder's own,
separate copy was enough to resolve what was reported, without touching
the classic template's shared code path.

**Known follow-up**: the trailing marker was originally there to separate
consecutive posts when `pgfw_generate_pdf_from_library()` concatenates
multiple posts onto one PDF (`continuous_on_same_page` bulk mode). Removing
it fixes the single-post download (the reported bug) but means two
builder-enabled posts concatenated back-to-back in that bulk mode no longer
get a break between them. Reintroducing a between-posts (but not
after-the-last-post) separator would need to live in the bulk loop itself,
not in `pgfw_builder_maybe_render()` (which has no way to know whether it's
the last post in a batch) - left as-is for now since bulk mode wasn't part
of what was reported broken, and changing the shared bulk loop risks the
classic template's bulk path too.

### 12 predefined templates ("get a reference")

`Pdf_Generator_For_Wp_Admin::pgfw_get_builder_predefined_templates()`
returns 12 ready-made block layouts, each with its own color scheme: Minimal,
Classic Report, Modern Card, Invoice, Certificate, Magazine Cover,
Two-Column Brochure, Corporate Letterhead, Dark Mode, Elegant Serif,
Receipt/Compact, and Product Sheet. They're sent to the browser once as part
of the same localized `pgfw_pdf_builder_param.data` payload the rest of the
builder already uses (no extra AJAX round trip needed to browse them).

"Choose a Template" opens a modal grid of cards, each showing a genuine
scaled-down **preview of that template's actual block layout** (every
block rendered as a small positioned rectangle at its real relative
position/size/color - rectangles and images as filled/bordered boxes, text
and meta blocks as colored bars standing in for a line of text, since real
text isn't legible at that scale) rather than just a flat color swatch and
a name, so an admin can tell templates apart at a glance before picking
one. Clicking one
**replaces the blocks on the current page only** (other pages, and other
post types, are untouched) and clears any custom page background color, so
the template's own look applies cleanly. Templates use only
post-type-agnostic sources (`post_title`, `post_content`, `post_excerpt`,
`post_date`, `post_author`, `featured_image`, or static placeholder text) —
no meta-field bindings — since a template needs to make sense on any post
type. They're designed against the default 794×1123 (A4 portrait) canvas;
on a differently sized/oriented page the blocks can simply be repositioned
after loading, same as any other block.

### Page background color & watermark

Both are per-post-type-layout settings (not per-page, not per-block):

- **Background color** — a color picker in the Page panel; empty means
  white (dompdf's default), matching how `sanitize_hex_color()` treats an
  empty string.
- **Watermark** — enable checkbox + text + color + font size + opacity
  slider. Rendered as a semi-transparent, `rotate(-35deg)`-transformed text
  block sized to the full page, placed *before* (so visually underneath) the
  page's blocks. dompdf's CSS `transform` support is more limited than a
  browser's; this is a best-effort rendering and is worth confirming
  visually against a real generated PDF, same caveat as everything else in
  this feature.

Both save/load through the same AJAX action as the blocks
(`pgfw_save_pdf_builder_layout` now also accepts `background_color` and
`watermark`, sanitized server-side the same way every other field is).

### Visual redesign

The tab's markup and CSS were rewritten from the Material-Design-Components
look (which rendered as bare, unstyled-looking buttons without MDC's JS
initialized) to a purpose-built, self-contained design: a proper toggle
switch for the master enable, a card-based palette/properties sidebar, an
icon block-picker grid (using WP core's already-loaded Dashicons - no new
icon set needed), a page-tab strip instead of plain buttons, and the
template gallery modal. Colors use CSS custom properties
(`--pgfw-accent`, etc.) so the whole builder's accent color can be
re-themed by changing a handful of variables in
`admin/src/css/pdf-generator-for-wp-builder.css` if needed later.

## Always-visible block binding badge

Some templates (Invoice, Magazine Cover, Dark Mode, etc.) intentionally use
white/near-white text meant to sit on a colored header or background block.
In the editor, once a block is dragged, resized, or simply lands over a
different part of the canvas than the template originally placed it, that
same color can become invisible against the white canvas (white-on-white) -
the block's binding (its `source`/`meta_key`) is still completely intact,
it's just not visible to look at. This showed up as "some containers show
their value, others don't, even though all of them have one set."

Every text/meta/image block now renders a small always-legible badge (dark
background, white text, fixed size/weight - not affected by the block's own
color/background/font styling) in its top-left corner showing what it's
bound to (`Post Title`, `Meta: price`, `Featured Image`, etc.), independent
of whatever the block's actual configured color is. This is purely an editor
aid; the real generated PDF is unaffected and still renders each block with
its actual configured styling.

## Explicitly out of scope

- No undo/redo (beyond an explicit Duplicate as a manual "copy before you
  break it").
- Long `post_content`/`post_excerpt` blocks can still overflow their box on
  a single page (dompdf clips/flows past it per plain CSS `overflow`
  behavior) — if content is long, either size the block generously or split
  it across multiple pages/blocks; there's no auto-flow-to-next-page for a
  single block yet.
- No live PDF preview inside the builder — the canvas is the same coordinate
  space used for the real PDF, but dompdf's box model isn't pixel-perfect
  identical to a browser's; verify visually against a real generated PDF
  after saving, same caveat that already applies to every other template in
  this plugin.
- Per-post overrides (a single product with a different layout than the
  rest of its post type) — layouts are per post type only, matching the
  granularity the rest of the plugin's template settings already use.

## Testing checklist

1. PDF Builder tab → pick "post" → drag a Text block onto the canvas, set
   its source to "Post Title" → drag a Meta Field block, pick a key already
   selected on the Meta Fields tab → drag an Image block, choose "Featured
   Image" → drag a Rectangle block as a divider → move/resize blocks →
   Bring to Front / Send to Back on an overlapping pair → Duplicate a block
   → Add a second page and put a block on it → Save Layout.
2. Toggle "Enable PDF Builder" on → download a post's PDF (the normal
   download button) → the generated PDF shows only the builder's pages/
   blocks, at the same relative positions as the canvas, **on the correct
   number of pages with no extra blank pages**, instead of the classic
   header/body/footer layout.
3. Download a **page's** PDF (no builder layout saved for `page`) → still
   renders via the classic template, confirming per-post-type fallback.
4. With mixed post types, trigger the weekly bulk regeneration
   (`do_action( 'pgfw_cron_weekly_regenerate_pdfs' )`) across post types
   where only some have a builder layout → confirm no fatal error and each
   post type renders with its own correct template (classic or builder).
5. Change Body Settings → Page Size/Orientation to something other than A4
   portrait (e.g. Letter, or landscape) → reopen PDF Builder → the canvas
   resizes to match → confirm a generated PDF still fills the page correctly
   at the new size.
