# PDF Icon Display Frontend Contract

## Scope

This document describes how the PDF icon system now behaves on the frontend for:

- product pages
- posts
- pages
- shortcode output
- direct-download mode
- email-trigger mode
- related action buttons added by the pro plugin

## Supported Templates

The frontend keeps the same 8 template keys:

- `style-2`
- `default`
- `style-4`
- `style-5`
- `style-3`
- `style-6`
- `style-7`
- `style-8`

Each template still has its own shell style, but all of them now share the same rendering slots:

- wrapper
- icon/media slot
- label slot
- optional action-chip/decorative slot

## Shared Setting Contract

These display settings are expected to apply across the frontend icon system:

| Setting | Option Key | Frontend Effect |
| --- | --- | --- |
| Show PDF Icon | `pgfw_display_pdf_icon_after` | Controls content or WooCommerce hook placement |
| PDF Icon Alignment | `pgfw_display_pdf_icon_alignment` | Controls shared wrapper justification |
| PDF Icon Display | `pgfw_pdf_icon_display_template` | Selects the active template shell |
| Choose Single Download PDF Icon | `sub_pgfw_pdf_single_download_icon` | Overrides the built-in download icon |
| Single Download PDF Icon Name | `wps_wpg_single_pdf_icon_name` | Provides the main download/email label |
| Icon Size | `pgfw_pdf_icon_width`, `pgfw_pdf_icon_height` | Drives media dimensions through CSS variables |

Legacy fallback:

- older saved sites may still contain `single_pdf_icon_name`
- the frontend reads that as a fallback if `wps_wpg_single_pdf_icon_name` is empty

## Frontend Rendering Rules

- Product, page, post, and shortcode output use the same shared button renderer.
- Direct-download mode and email-trigger mode use the same visible action shell.
- The active template is applied through shared classes:
  - wrapper: `pgfw-icon-display pgfw-icon-display--{template}`
  - button: `pgfw-single-pdf-download-button pgfw-single-pdf-download-button--{template}`
- Icon sizing is controlled through:
  - `--pgfw-icon-width`
  - `--pgfw-icon-height`
- Wrapper alignment is controlled through:
  - `--pgfw-icon-justify`

## Related Action Buttons

When the pro plugin is active:

- print uses the same template shell as the main download action
- WhatsApp share uses the same template shell as the main download action
- bulk-download add buttons in the pro plugin use the same shared button renderer and the same template classes

Action-specific labels remain independent:

- `Print`
- `Share`
- bulk label from `wps_wpg_bulk_pdf_icon_name`, with fallback text when empty

## Theme Compatibility Rules

The frontend no longer depends on template-specific fixed image heights with `!important`.

Theme resilience now comes from:

- scoped `.pgfw-icon-display` wrapper layout
- shared button class structure
- CSS-variable-driven icon sizing
- button-level shell styling instead of ad-hoc inline layout

WooCommerce hook output uses a custom `wp_kses()` allowlist so the style-variable attributes survive when the buttons are echoed in product hooks.

## Manual QA Matrix

Check these combinations before release:

| Context | What To Verify |
| --- | --- |
| Post | Template changes, label visibility, icon sizing, alignment |
| Page | Template changes, label visibility, icon sizing, alignment |
| Product | Template changes, label visibility, icon sizing, alignment |
| Shortcode | Shared renderer output matches automatic placement output |
| Direct download | Main action shell and icon render correctly |
| Email trigger | Trigger button matches the selected template |
| Print enabled | Print action inherits the selected template shell |
| Share enabled | Share action inherits the selected template shell |
| Bulk enabled | Pro bulk action inherits the selected template shell |
| Logged-in user | Access and rendering match configured mode |
| Guest user | Access and rendering match configured mode |
| Role restricted | Button hides/shows correctly when role restriction is enabled |
| Block theme | Layout remains stable without theme collisions |
| Classic/WooCommerce-heavy theme | Layout remains stable inside product summary hooks |

## Known Intentional Differences

- `style-7` keeps the extra `PDF` chip on the main download action.
- Action buttons under `style-7` suppress that chip so `Print`, `Share`, and bulk actions do not gain misleading `PDF` badges.
- Template shells are intentionally different, but no template should permanently suppress the icon slot or the label slot anymore.
