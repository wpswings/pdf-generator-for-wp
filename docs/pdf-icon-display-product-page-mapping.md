# PDF Icon Display Mapping For Product Page

## Scope

This note documents the PDF icon options available in the backend `PDF Settings` / display area and how they render on the product page frontend.

It covers:

- main PDF download button
- email-trigger button when download mode is set to `Email`
- related action buttons that now inherit the selected display template:
  - `Print`
  - `Share` / WhatsApp

## Backend Icon Options Found

The backend icon-related settings currently available are:

| Backend Setting | Option Key | Frontend Purpose |
| --- | --- | --- |
| Show PDF Icon | `pgfw_display_pdf_icon_after` | Decides where the PDF action block appears on the product page |
| PDF Icon Alignment | `pgfw_display_pdf_icon_alignment` | Aligns the PDF action block wrapper left / center / right |
| PDF Icon Display | `pgfw_pdf_icon_display_template` | Chooses the frontend design template |
| Icon Size | `pgfw_pdf_icon_width`, `pgfw_pdf_icon_height` | Sends width / height values to the image markup |
| Choose Single Download PDF Icon | `sub_pgfw_pdf_single_download_icon` | Replaces the built-in PDF icon asset for the main PDF button when the selected style actually shows an image |
| Single Download PDF Icon Name | `wps_wpg_single_pdf_icon_name` | Supplies label text for styles that show a label |

## Backend Templates And Frontend Display

| Backend Label | Template Key | Frontend Display On Product Page |
| --- | --- | --- |
| Adobe Badge | `style-2` | Red Adobe-like PDF badge, image only |
| Printer Classic | `default` | Soft rounded circle / pill with printer glyph, image only |
| Boxed Button | `style-4` | Purple rounded button with text label, image hidden |
| Stamped Seal | `style-5` | Circular stamp / seal with text in center, image hidden |
| Brand Tile | `style-3` | Solid purple tile with white icon, no visible label |
| Gradient FAB | `style-6` | Circular gradient floating button with white icon, no visible label |
| Glass Pill | `style-7` | Glassy pill with icon and label; main PDF button also shows `PDF` chip |
| Shimmer Tile | `style-8` | Gradient tile with icon only, no visible label |

## Which Settings Apply On Product Page

### Settings that apply across all templates

| Setting | Status | Notes |
| --- | --- | --- |
| Show PDF Icon | Applies | Controls placement / hook position of the action block |
| PDF Icon Alignment | Applies | Wrapper uses selected left / center / right alignment |
| PDF Icon Display | Applies | Main PDF button, email-trigger button, print button, and share button now inherit the selected template class |

### Settings that apply only on some templates

| Setting | Fully Applied On | Partially Applied On | Not Visibly Applied On | Notes |
| --- | --- | --- | --- | --- |
| Choose Single Download PDF Icon | `style-2`, `default`, `style-3`, `style-6`, `style-7`, `style-8` | None | `style-4`, `style-5` | `style-4` and `style-5` hide the `<img>` completely, so uploaded icon does not show there |
| Single Download PDF Icon Name | `style-4`, `style-5`, `style-7` | None | `style-2`, `default`, `style-3`, `style-6`, `style-8` | Label is hidden in the icon-only templates |
| Icon Size | None | `style-2`, `default`, `style-3`, `style-6`, `style-7`, `style-8` | `style-4`, `style-5` | Image-based templates receive size attrs in HTML, but frontend CSS forces fixed icon heights; label-only templates hide the image |

## Important Frontend Reality

### No template currently applies every icon setting fully

There is **no single template** where all of these are fully visible and fully controlled at the same time:

- display template
- alignment
- custom uploaded icon
- custom label
- custom width / height

Reason:

1. `style-4` and `style-5` are label-driven designs and hide the image.
2. `style-2`, `default`, `style-3`, `style-6`, `style-7`, and `style-8` show the image, but frontend CSS normalizes the icon height with fixed values, so backend size does not fully control the final rendered size.
3. Icon-only templates hide the custom label completely.

## Closest Template To Full Setting Support

`style-7` (`Glass Pill`) is the closest to full support for the **main PDF button** because:

- selected template is visible
- uploaded custom icon is visible
- custom label is visible
- alignment applies

But it is still **not fully complete** because icon size is still normalized by frontend CSS.

## Related Action Icons: Print And Share

After the recent frontend update:

| Action Icon | Selected Template Applied | Alignment Applied | Custom Uploaded PDF Icon Applied | Custom PDF Label Applied | Notes |
| --- | --- | --- | --- | --- | --- |
| Main PDF button | Yes | Yes | Yes, only on image-based templates | Yes, only on label-based templates | Primary target of the backend icon settings |
| Email-trigger button | Yes | Yes | Yes, only on image-based templates | Yes, only on label-based templates | Uses same template system as main PDF button |
| Print button | Yes | Yes | No | No | Uses built-in print icon and fixed `Print` label |
| Share / WhatsApp button | Yes | Yes | No | No | Uses built-in WhatsApp icon and fixed `Share` label |

## Final Status Summary

| Template | Display Template | Alignment | Custom Upload | Custom Label | Icon Size | Overall Status |
| --- | --- | --- | --- | --- | --- | --- |
| Adobe Badge (`style-2`) | Yes | Yes | Yes | No | Partial | Partial |
| Printer Classic (`default`) | Yes | Yes | Yes | No | Partial | Partial |
| Boxed Button (`style-4`) | Yes | Yes | No | Yes | No | Partial |
| Stamped Seal (`style-5`) | Yes | Yes | No | Yes | No | Partial |
| Brand Tile (`style-3`) | Yes | Yes | Yes | No | Partial | Partial |
| Gradient FAB (`style-6`) | Yes | Yes | Yes | No | Partial | Partial |
| Glass Pill (`style-7`) | Yes | Yes | Yes | Yes | Partial | Best current coverage |
| Shimmer Tile (`style-8`) | Yes | Yes | Yes | No | Partial | Partial |

## Practical Conclusion

If the requirement is:

- "selected backend icon design should show on the product page"  
  Status: **Yes**

- "same selected design should also style print / share actions"  
  Status: **Yes**

- "every icon setting should fully control every template"  
  Status: **No**

Main current limitation: `Icon Size` is not fully honored by the frontend design CSS, and label-only templates cannot visually use the uploaded custom icon.
