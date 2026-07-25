# Master Stitch AI Generation Handbook: Premium Ecommerce (Laravel 12 Ready)

This handbook provides a sequenced, multi-phase workflow designed to generate a production-ready, component-based frontend within Google Stitch AI. It prioritizes design token architecture, mobile-first responsiveness, and semantic HTML structure optimized for Laravel 12 Blade integration.

---

## Strategic Core Principles for Stitch AI

### 1. The Token First Rule
Never let the AI generate a screen before the Design System is established. The `design_system_id` is the "Source of Truth" that prevents visual drift.

### 2. The "Atomic" Sequence
Generate in this order: **Tokens -> Shared Components -> Page Shells -> Page Content.** This ensures every page inherits the same Header, Footer, and Grid logic.

### 3. Laravel 12 Readiness
Instruct Stitch to use BEM-like naming or semantic utility classes that are easy to identify and replace with Blade directives or `@include` statements.

---

## Phase 1: Master Project Initialization & Design System
**Goal:** Establish the visual DNA and technical constraints.
**Why:** Without tokens, every screen will have slightly different hex codes and spacing.

**Stitch Tool:** `generate_design_system`
**Expected Output:** A `DESIGN_SYSTEM` artifact containing the specific palette, typography, and spacing tokens.

### The Prompt
> Create a comprehensive Design System for a "Modern Premium Ecommerce" platform. 
> 
> **Visual Identity:** Premium SaaS aesthetic (Apple/Linear style). Minimalist, high-contrast, generous whitespace.
> **Color Tokens (CSS Variables):**
> --color-primary: #F97316;
> --color-primary-hover: #EA580C;
> --color-secondary: #16A34A;
> --color-accent: #0EA5E9;
> --color-bg: #FFFDF8;
> --color-surface: #FFFFFF;
> --color-card: #FFFFFF;
> --color-border: #E5E7EB;
> --color-text: #111827;
> --color-heading: #0F172A;
> --color-muted: #6B7280;
> --color-success: #22C55E;
> --color-danger: #EF4444;
> --color-warning: #F59E0B;
> 
> **Architecture:**
> - Mobile-first responsive breakpoints.
> - 8px base grid system for spacing.
> - Typography: Sans-serif (Inter/Geist style), tight tracking for headings, readable line-height for body.
> - Shadows: Soft, multi-layered "Linear" style elevations.
> - Radii: 8px for buttons, 12px for cards.
> 
> **Technical Requirement:** All colors MUST be referenced via CSS variables.

---

## Phase 2: Global Layout Architecture & Component Prediction
**Goal:** Define the shared UI "bones."
**Why:** To ensure the navigation and footer are identical across 20+ pages.

**Stitch Tool:** `predict_shared_components`
**Expected Output:** A `COMPONENTS` artifact for the global Header, Mobile Nav, and Footer.

### The Prompt
> Based on {{DATA:DESIGN_SYSTEM:DESIGN_SYSTEM_1}}, predict and define the shared UI components for a premium ecommerce app.
> 
> **Core Components Needed:**
> 1. **Main Navigation:** Desktop (sticky, glassmorphism blur, search bar, cart trigger, user account) and Mobile (bottom bar or slide-out menu).
> 2. **Global Footer:** Multi-column layout with newsletter signup, social links, and trust badges.
> 3. **Product Card:** Optimized for high-quality imagery, quick-add button, and price display.
> 4. **Breadcrumbs & Page Headers:** Semantic structure for deep navigation.
> 5. **Admin Sidebar:** Collapsible, high-density navigation for the dashboard.
> 
> **UX Focus:** High "Premium Feeling" inspired by iammoringa.co (visual hierarchy and layout rhythm).

---

## Phase 3: High-Fidelity Customer Pages
**Goal:** Generate the core shopping experience.
**Why:** To build the conversion funnel (Home -> Category -> Product -> Cart).

**Stitch Tool:** `generate_design_with_components`
**Expected Output:** Multiple `SCREEN` artifacts.

### The Prompt
> Generate 4 premium customer-facing screens using {{DATA:DESIGN_SYSTEM:DESIGN_SYSTEM_1}} and {{DATA:COMPONENTS:COMPONENTS_1}}.
> 
> **Screens:**
> 1. **Home:** Hero section with micro-animations, featured categories, "Trending" product grid, and brand story.
> 2. **Product Catalog:** Advanced filtering sidebar, sort dropdown, and responsive grid.
> 3. **Product Detail:** High-resolution gallery, sticky "Add to Cart" on mobile, tabbed technical specs, and "Related Products."
> 4. **Checkout/Cart:** Minimalist Linear-style layout, progress indicator, order summary, and trust building elements.
> 
> **Technical Constraint:** Use semantic HTML5 tags. Avoid deep <div> nesting. Ensure all components use the design system tokens.

---

## Phase 4: Admin Dashboard (Premium SaaS Style)
**Goal:** Build the management interface.
**Why:** To handle orders and inventory with a Stripe-level UX.

**Stitch Tool:** `generate_design_with_components`
**Expected Output:** Admin `SCREEN` artifacts.

### The Prompt
> Generate 3 high-density Admin Dashboard screens for a Modern Ecommerce platform. Use {{DATA:DESIGN_SYSTEM:DESIGN_SYSTEM_1}} and {{DATA:COMPONENTS:COMPONENTS_1}}.
> 
> **Style:** SaaS dashboard (Stripe/Vercel style). Clean, data-heavy but readable.
> **Screens:**
> 1. **Dashboard Overview:** Sales analytics charts (use SVG placeholders), recent orders table, and inventory alerts.
> 2. **Product Management:** Data table with search, status badges, and "Edit" modals.
> 3. **Order Detail:** Customer timeline, fulfillment status, and payment breakdown.
> 
> **UX Goal:** Efficiency and trust. Use the secondary (#16A34A) and accent (#0EA5E9) colors for success states and interactive actions.

---

## Phase 5: Interaction, Animation & Production Audit
**Goal:** Add the "Premium Polish" and verify technical integrity.
**Why:** Micro-interactions (hover states, smooth transitions) separate premium products from templates.

**Stitch Tool:** `edit_html_full_regen`
**Expected Output:** Updated `SCREEN` artifacts with CSS/JS motion.

### The Prompt
> Apply a "Premium Interaction Layer" to the generated screens.
> 
> **Interactions:**
> - Smooth CSS transitions for all button hovers and card elevations.
> - Staggered fade-in entrance animations for product grids.
> - Sticky Header "Shrink" effect on scroll.
> - Loading state skeletons for data-heavy sections.
> 
> **Consistency Audit:**
> - Ensure EVERY color is a CSS variable from the token file.
> - Verify mobile-first responsiveness (check stacking on small screens).
> - Ensure semantic accessibility (aria-labels on icons, proper heading levels).
> - Optimize HTML structure for easy conversion to Laravel Blade templates (clear sectioning).

---

## How to Avoid "AI Drift" and Maintain Quality

1.  **Always Reference the Design System:** In every single prompt for a new page, include the `{{DATA:DESIGN_SYSTEM:DESIGN_SYSTEM_N}}` ID. This forces Stitch to use your specific colors and fonts.
2.  **Sequential Edits:** If Stitch makes a mistake in the header, fix it using `edit_html_in_place` on the *original* component before generating new pages.
3.  **The "Keep Previous" Instruction:** If you like the layout but need a content change, use: "Maintain the exact layout, spacing, and CSS variables of {{DATA:SCREEN:SCREEN_N}}, but update the [specific element] only."
4.  **Laravel Blade Tip:** Ask Stitch to "Wrap logical sections in HTML comments like `<!-- Start: Product Grid -->`". This makes it trivial to identify where to place your `@foreach` loops later.
