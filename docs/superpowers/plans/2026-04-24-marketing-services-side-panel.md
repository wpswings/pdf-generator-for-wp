# Marketing Services Side Panel Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a new marketing services card to the admin dashboard rail before the existing support card and match the provided reference closely.

**Architecture:** Extend the existing dashboard sidebar partial with one dedicated markup block and support it with purpose-built CSS in the modern admin stylesheet. Keep the change isolated to the dashboard rail so no settings-form logic or shared admin components are affected.

**Tech Stack:** WordPress PHP templates, plugin admin CSS

---

### Task 1: Add Sidebar Card Markup

**Files:**
- Modify: `admin/partials/pdf-generator-for-wp-admin-dashboard.php`

- [ ] Add a new rail card directly before the `Still facing problems?` card.
- [ ] Include title, subtitle, three service rows, CTA button, and footer note.
- [ ] Use dedicated class names so the new card styling stays isolated from the generic rail-card styling.

### Task 2: Add Dedicated Card Styling

**Files:**
- Modify: `admin/src/css/pdf-generator-for-wp-admin-modern.css`

- [ ] Add the cream card shell styling, stacked service-row layout, icon badges, chevrons, dark CTA button, and muted footer note.
- [ ] Add responsive safeguards so the new card remains readable when the rail wraps on narrower admin widths.
- [ ] Keep the selectors scoped to the new marketing card classes to avoid changing existing rail cards.

### Task 3: Verify

**Files:**
- Verify: `admin/partials/pdf-generator-for-wp-admin-dashboard.php`
- Review: `admin/src/css/pdf-generator-for-wp-admin-modern.css`

- [ ] Run `php -l admin/partials/pdf-generator-for-wp-admin-dashboard.php`.
- [ ] Review the final CSS block for selector collisions and obvious layout regressions.
- [ ] Report that browser-perfect validation still needs a manual admin UI check.
