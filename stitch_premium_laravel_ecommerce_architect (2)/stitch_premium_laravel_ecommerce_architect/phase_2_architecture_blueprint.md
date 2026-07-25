# Phase 2: Application Architecture & Component Strategy

This document outlines the architectural blueprints for the Laravel 12 / Blade-ready frontend. These rules and structures ensure 1:1 parity between design and production code.

## 1. Laravel 12 Blade Architecture (Recommendation)
To maintain the "Component-First" philosophy, the project should be structured as follows:

```text
resources/views/
├── layouts/
│   ├── customer.blade.php  # Main Customer Shell (Header + Footer + Bottom Nav)
│   ├── admin.blade.php     # Admin Dashboard Shell (Sidebar + Top Bar)
│   └── guest.blade.php     # Auth/Minimal layouts
├── components/
│   ├── ui/                 # Atomic: Buttons, Badges, Inputs, Checkboxes
│   ├── layout/             # Navigation, Sidebars, Footers
│   ├── cards/              # Product, Stat, Address, Review cards
│   ├── forms/              # Login, Checkout, Profile forms
│   └── feedback/           # Modals, Toasts, Skeletons, Empty States
├── partials/
│   ├── scripts.blade.php   # Global JS/Animation initialization
│   └── styles.blade.php    # CSS Variables/Token imports
└── pages/
    ├── customer/           # Page-specific Blade views
    └── admin/              # Dashboard-specific Blade views
```

## 2. Customer Application Shell
**Global Header:** Sticky with `backdrop-blur-md`. Contains Brand Logo (Left), Mega Menu (Center), Search/Cart/Account (Right).
**Mobile Navigation:** Bottom-fixed bar for primary touch-targets (Home, Search, Wishlist, Cart) + Slide-out drawer for category browsing.
**Global Footer:** 4-column layout (About, Customer Care, Policies, Newsletter) + Payment/Social strip.

## 3. Admin Application Shell
**Sidebar:** Fixed `256px` (collapsed to `64px`). High-density navigation with status indicators for 'Orders' and 'Inventory'.
**Top Navigation:** Breadcrumbs (Left), Search Input (Center), and Profile/Notifications (Right).
**Page Header Pattern:** Flexible component with `Title`, `Subtitle`, and `ActionGroup` (Primary Action + Secondary Dropdown).

## 4. Layout Rules & Responsive Constraints
- **Base Grid:** 8px (Tailwind-compatible spacing).
- **Container Max-Width:** `1280px` for Customer, `Full Width` with `px-8` padding for Admin.
- **Header Heights:** Desktop Customer: `64px`, Admin: `56px`.
- **Breakpoints:**
  - Mobile: `< 768px` (Mobile Shell active)
  - Tablet: `768px - 1024px` (Collapsed Sidebar active)
  - Desktop: `> 1024px` (Full Layouts active)

## 5. Predicted Shared Components
Refer to `{{DATA:COMPONENTS:COMPONENTS_3}}` for the full technical definitions.
- **Data Display:** Tables with sticky headers and responsive "Card-Stack" fallback on mobile.
- **Interactive:** Modals with focus trapping and standard `Animate-In-Up` transitions.
- **States:** Shimmering Skeletons that match the exact aspect ratio of Cards/Tables to prevent Layout Shift (CLS).