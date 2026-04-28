# Marketing Services Side Panel Design

## Goal

Add a new sidebar card to the plugin admin dashboard that matches the provided reference as closely as possible and appears immediately before the existing `Still facing problems?` card.

## Placement

Update the dashboard rail in `admin/partials/pdf-generator-for-wp-admin-dashboard.php`.

Insert the new card between:

- `Need help with this plugin?`
- `Still facing problems?`

## Card Content

The card will include:

- Heading: `Marketing Services`
- Subheading: `Take your store to the next level.`
- Three service rows:
  - `SEO Services` with supporting copy `Improve rankings & organic traffic`
  - `Google Ads Setup` with supporting copy `Run profitable ad campaigns`
  - `Speed Optimization` with supporting copy `Faster store, happier customers`
- Primary CTA: `Book Free Consultation`
- Small footer note: `Optional services by WP Swings`

## Visual Design

The card should follow the provided reference closely:

- warm cream/beige card background
- rounded outer card with soft border
- compact header spacing
- three stacked white service rows with subtle border and rounded corners
- left circular/icon badges for each service row
- right-facing chevron on each service row
- dark full-width CTA button
- small muted footer note centered under the button

The implementation should use the plugin's existing admin visual language where possible, but the internal layout and colors of this card should intentionally mirror the reference instead of reusing the generic sidebar card treatment.

## Implementation Approach

### Option 1: Dedicated Markup and CSS

Create a standalone rail card with purpose-built markup and CSS classes.

This is the recommended option because the card structure is more complex than the existing rail cards and needs nested service rows, icon wrappers, and a specific CTA layout.

### Option 2: Reuse Existing Generic Rail Card Pattern

Try to compose the card from existing `pgfw-card` and `pgfw-rail-link` pieces.

This is lower effort but would not match the screenshot closely enough.

## Recommended Approach

Use dedicated markup and CSS.

## Files To Update

- `admin/partials/pdf-generator-for-wp-admin-dashboard.php`
- `admin/src/css/pdf-generator-for-wp-admin-modern.css`

## Responsive Behavior

The new card must remain readable in the existing rail layout:

- normal stacked column on desktop
- existing rail wrapping behavior on narrower admin widths
- no overflow from the CTA or service-row text

## Risks

- existing global sidebar styles may override some visual details
- the plugin's admin theme tokens may affect exact colors, so some styles may need stronger specificity

## Verification

- PHP syntax check for the updated dashboard partial
- visual code-path review of the new CSS selectors
- no claim of browser-perfect parity without manual UI review
