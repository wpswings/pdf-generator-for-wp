# PDF Icon Display Compatibility Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Make all 8 PDF icon display templates render correctly on product, page, and post frontends, and ensure the icon-display settings in the PDF Settings tab apply consistently across direct-download, email-trigger, and related action icons.

**Architecture:** Replace the current duplicated frontend icon markup with one shared rendering contract driven by normalized display settings and template metadata. Move visual control out of inline styles and into scoped CSS variables so theme overrides are minimized and the same icon system works in `the_content`, WooCommerce hooks, shortcode output, and the email-trigger button.

**Tech Stack:** WordPress PHP, WooCommerce hooks, plugin option arrays, shared template partials, scoped frontend CSS, lightweight file-based regression tests

---

## Current Findings

- `public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php` and `public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php` each build their own icon markup, so template behavior already diverges between direct-download and email flows.
- `includes/pdf-generator-for-wp-global-functions.php` only maps built-in icon sources for `style-2` and `default`; every other style falls back to the default tray asset.
- `public/src/scss/pdf-generator-for-wp-public.css` hard-hides labels for `default`, `style-2`, `style-3`, `style-6`, and `style-8`, and hard-hides images for `style-4` and `style-5`, so the current setting contract cannot work across all 8 templates.
- The same CSS also forces fixed icon heights with `!important`, so `pgfw_pdf_icon_width` and `pgfw_pdf_icon_height` are only partially visible on the frontend.
- `admin/class-pdf-generator-for-wp-admin.php` saves the label field as `single_pdf_icon_name`, but the frontend templates read `wps_wpg_single_pdf_icon_name`, so the uploaded icon name setting is not reliably applied.
- The WooCommerce and non-WooCommerce rendering entry points both depend on the same template file, but the product-hook branch and the content-filter branch still emit slightly different DOM and inline styles.
- The email-trigger button wrapper uses inline justification without a proper shared flex wrapper, so alignment can drift from the main frontend button.
- Existing tests only assert contract snippets in files. They do not yet prove that every template supports the same visible settings across product/page/post contexts.

## Scope And Acceptance Criteria

- All 8 template IDs must continue to exist: `style-2`, `default`, `style-4`, `style-5`, `style-3`, `style-6`, `style-7`, `style-8`.
- Product, page, and post frontend output must use the same template contract regardless of whether rendering happens through `the_content`, a WooCommerce hook, or the `[WORDPRESS_PDF]` shortcode.
- The icon-display settings that control frontend design must apply consistently:
  - placement / hook position
  - alignment
  - selected display template
  - uploaded single-download icon
  - single-download icon label
  - icon width and height
- Email-trigger mode must render the same selected template as direct-download mode.
- Print and share buttons, when enabled, must inherit the same shell/template styling as the main PDF action and remain readable in every template.
- Theme compatibility means the plugin should no longer rely on fragile theme inheritance for button layout, icon sizing, text alignment, or spacing.
- Backward compatibility must be preserved for existing saved settings, especially the single icon name key mismatch.

## Recommended Implementation Strategy

Use one shared icon renderer instead of maintaining separate button templates per mode. Each template should expose the same slots:

- wrapper
- button shell
- media/icon slot
- text label slot
- optional action badge/decorative slot

The template style should decide presentation, not whether a setting exists. That means no template should permanently suppress the uploaded icon or the saved label. If a design is icon-first or label-first, it should still show the other slot in a controlled way rather than hiding it completely.

For sizing, apply width and height to the media/icon slot through CSS variables, not through hardcoded inline heights plus `!important`. This keeps the template shell recognizable while still making the admin size controls visible on the frontend.

## File Map

**Modify**

- `pdf-generator-for-wp/admin/class-pdf-generator-for-wp-admin.php`
- `pdf-generator-for-wp/includes/pdf-generator-for-wp-global-functions.php`
- `pdf-generator-for-wp/includes/class-pdf-generator-for-wp.php`
- `pdf-generator-for-wp/public/class-pdf-generator-for-wp-public.php`
- `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php`
- `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php`
- `pdf-generator-for-wp/public/src/scss/pdf-generator-for-wp-public.css`
- `pdf-generator-for-wp/tests/action-icon-display-regression.php`
- `pdf-generator-for-wp/tests/template-icon-source-regression.php`
- `pdf-generator-for-wp/tests/style5-public-css-regression.php`
- `pdf-generator-for-wp/docs/icon_display.md`

**Create**

- `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-icon-action-template.php`
- `pdf-generator-for-wp/tests/icon-display-setting-coverage-regression.php`
- `pdf-generator-for-wp/tests/icon-display-theme-compat-regression.php`

## Task 1: Normalize The Display-Setting Contract

**Files:**
- Modify: `pdf-generator-for-wp/admin/class-pdf-generator-for-wp-admin.php`
- Modify: `pdf-generator-for-wp/includes/pdf-generator-for-wp-global-functions.php`

- [ ] Align the saved single-label option key with what the frontend reads. Recommended fix: make the admin field save `wps_wpg_single_pdf_icon_name`, and keep a read fallback to the legacy `single_pdf_icon_name` value so existing installations do not lose labels.
- [ ] Audit the icon-display settings array and explicitly document which keys are frontend icon settings versus PDF document settings. Keep this scoped to icon-display behavior; do not repurpose PDF document color settings unless product requirements explicitly expand.
- [ ] Add one normalized helper in `includes/pdf-generator-for-wp-global-functions.php` that returns the canonical frontend icon settings array with defaults, legacy fallbacks, and sanitized dimensions.
- [ ] Expand the template metadata contract so each template describes its frontend behavior in one place instead of scattering those decisions between PHP and CSS.

## Task 2: Introduce A Shared Frontend Icon Renderer

**Files:**
- Create: `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-icon-action-template.php`
- Modify: `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php`
- Modify: `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php`
- Modify: `pdf-generator-for-wp/includes/pdf-generator-for-wp-global-functions.php`

- [ ] Create one shared partial that renders a single action button from a config array:
  - action type: `download`, `email`, `print`, `share`
  - template slug
  - href / click behavior
  - icon source
  - visible label
  - CSS variable payload for dimensions and spacing
  - extra modifier classes
- [ ] Refactor the direct-download template to build the wrapper once and render each action through the shared partial.
- [ ] Refactor the email-trigger template to use the same shared partial for the visible trigger button, so selected templates and settings cannot drift between download and email modes.
- [ ] Remove duplicated inline `<img>` sizing and duplicated label markup from the two existing templates.
- [ ] Keep existing action behavior intact: direct link for download, modal trigger for email, `window.print()` for print, WhatsApp URL for share.

## Task 3: Make All 8 Templates Compatible With The Same Setting Slots

**Files:**
- Modify: `pdf-generator-for-wp/public/src/scss/pdf-generator-for-wp-public.css`
- Modify: `pdf-generator-for-wp/includes/pdf-generator-for-wp-global-functions.php`

- [ ] Replace the current “hide image” and “hide label” approach with a shared visual contract where every template can display both the icon and the label.
- [ ] Preserve each template’s identity, but redesign the frontend CSS so compatibility wins over novelty:
  - `default` and `style-2` can remain compact but must still show a readable label when configured.
  - `style-4` and `style-5` must stop suppressing the media slot entirely.
  - `style-3`, `style-6`, and `style-8` must stop suppressing the label slot entirely.
  - `style-7` should remain the hybrid reference implementation.
- [ ] Decide whether each style needs a unique built-in asset or whether a shared neutral PDF glyph plus template CSS is enough. Only add new SVG assets where the visual design truly requires it.
- [ ] Keep icon width and height attached to the media slot through CSS variables such as `--pgfw-icon-width` and `--pgfw-icon-height`, instead of fixed heights with `!important`.

## Task 4: Harden Theme Compatibility On Product, Page, And Post Frontends

**Files:**
- Modify: `pdf-generator-for-wp/public/src/scss/pdf-generator-for-wp-public.css`
- Modify: `pdf-generator-for-wp/public/class-pdf-generator-for-wp-public.php`
- Modify: `pdf-generator-for-wp/includes/class-pdf-generator-for-wp.php`

- [ ] Replace wrapper-level inline layout styles with scoped CSS classes and CSS variables so theme CSS is less likely to break justification, gap, or display behavior.
- [ ] Add a narrow scoped reset for the plugin icon system under `.pgfw-icon-display` covering:
  - `display`
  - `align-items`
  - `justify-content`
  - `box-sizing`
  - `text-decoration`
  - `line-height`
  - `img` display / max-width behavior
- [ ] Keep the current placement system working for:
  - `before_content`
  - `after_content`
  - WooCommerce single-product hooks
  - shortcode output on page/post/product content
- [ ] Ensure public assets continue loading in all contexts that can render the icon, including WooCommerce archive contexts already guarded in `pgfw_should_enqueue_public_assets()`.

## Task 5: Unify Action Icons With The Selected Template

**Files:**
- Modify: `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php`
- Create: `pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-icon-action-template.php`
- Modify: `pdf-generator-for-wp/public/src/scss/pdf-generator-for-wp-public.css`

- [ ] Keep print and share actions as distinct actions, but render them through the same shell and slot structure as the main PDF action.
- [ ] Use per-action icon sources where needed, while keeping the same spacing, border radius, typography, and hover treatment selected by the active template.
- [ ] Ensure action labels remain readable in all 8 templates and do not disappear because of template-specific label suppression rules.
- [ ] Keep the action-specific style exception small and explicit, for example the `PDF` chip suppression on `style-7`.

## Task 6: Backward Compatibility And Data Safety

**Files:**
- Modify: `pdf-generator-for-wp/includes/pdf-generator-for-wp-global-functions.php`
- Modify: `pdf-generator-for-wp/admin/class-pdf-generator-for-wp-admin.php`

- [ ] Add read fallbacks for legacy saved values so existing sites continue rendering after the refactor.
- [ ] Do not change the existing template slugs or placement option values; those are already persisted in user sites.
- [ ] Preserve pro-only behavior boundaries. This feature is about compatibility and rendering correctness, not expanding paid features into free code paths.
- [ ] Avoid changing unrelated PDF document template settings stored in the same option array.

## Task 7: Regression Coverage

**Files:**
- Modify: `pdf-generator-for-wp/tests/action-icon-display-regression.php`
- Modify: `pdf-generator-for-wp/tests/template-icon-source-regression.php`
- Modify: `pdf-generator-for-wp/tests/style5-public-css-regression.php`
- Create: `pdf-generator-for-wp/tests/icon-display-setting-coverage-regression.php`
- Create: `pdf-generator-for-wp/tests/icon-display-theme-compat-regression.php`

- [ ] Extend the existing file-based tests to assert that both direct-download and email-trigger templates use the shared renderer.
- [ ] Add a regression test that checks the canonical frontend setting keys, including the corrected label key and legacy fallback handling.
- [ ] Add a regression test that blocks reintroduction of template rules that fully hide the icon slot or label slot for any of the 8 templates.
- [ ] Add a regression test that checks CSS-variable-driven sizing exists and that forced fixed icon heights with `!important` are removed from the main template selectors.
- [ ] Keep the tests lightweight and file-based so they fit the current repository tooling.

## Task 8: Documentation And QA Matrix

**Files:**
- Modify: `pdf-generator-for-wp/docs/icon_display.md`

- [ ] Rewrite the compatibility note so it describes the new contract instead of the current partial-support limitations.
- [ ] Add a manual QA matrix covering:
  - page
  - post
  - product
  - shortcode insertion
  - direct download
  - email trigger
  - print enabled
  - share enabled
  - logged-in user
  - guest user
  - role-restricted icon display
  - at least two different themes, one block theme and one classic/WooCommerce-heavy theme
- [ ] Call out any template-specific compromises that remain intentional after implementation.

## Verification Commands

- `php -l pdf-generator-for-wp/admin/class-pdf-generator-for-wp-admin.php`
- `php -l pdf-generator-for-wp/includes/pdf-generator-for-wp-global-functions.php`
- `php -l pdf-generator-for-wp/public/class-pdf-generator-for-wp-public.php`
- `php -l pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-pdf-generate-button-template.php`
- `php -l pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-email-storage-modal-template.php`
- `php -l pdf-generator-for-wp/public/templates/pdf-generator-for-wp-public-icon-action-template.php`
- `php pdf-generator-for-wp/tests/action-icon-display-regression.php`
- `php pdf-generator-for-wp/tests/template-icon-source-regression.php`
- `php pdf-generator-for-wp/tests/style5-public-css-regression.php`
- `php pdf-generator-for-wp/tests/icon-display-setting-coverage-regression.php`
- `php pdf-generator-for-wp/tests/icon-display-theme-compat-regression.php`

## Manual Acceptance Checklist

- [ ] Switching between all 8 templates changes the visible frontend design on product, page, and post.
- [ ] The uploaded single-download icon is visible in every template.
- [ ] The single-download icon name is visible in every template.
- [ ] Width and height changes are visibly reflected in every template without breaking layout.
- [ ] The email-trigger button matches the selected template exactly.
- [ ] Print and share actions inherit the active template shell and remain readable.
- [ ] Alignment changes left/center/right behave the same across page, post, and product contexts.
- [ ] The icon block remains stable in at least two different frontend themes.

## Risks To Watch During Implementation

- The current CSS preview language in admin may not exactly match the new frontend-compatible rendering for every template. Frontend correctness should take priority, but admin previews may need a follow-up sync if they diverge too far.
- Existing user sites may already have saved `single_pdf_icon_name`; removing that read path without fallback would look like a regression.
- Some WooCommerce themes heavily style anchors and images inside product summaries. If scoped reset selectors are too weak, compatibility will remain flaky. If they are too broad, they could affect nearby theme markup.
- If the renderer keeps inline styles for convenience, the refactor will not fully solve theme compatibility.
