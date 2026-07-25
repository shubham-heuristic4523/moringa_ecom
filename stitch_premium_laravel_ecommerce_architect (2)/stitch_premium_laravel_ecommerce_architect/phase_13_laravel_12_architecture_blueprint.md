# Phase 13: Laravel 12 Frontend Architecture & Developer Handoff

This document serves as the official implementation blueprint for migrating the **LUXE Premium Ecommerce** designs from Stitch AI into a production-ready Laravel 12 application. It prioritizes atomic component reuse, clean data-binding patterns, and enterprise-grade asset organization.

---

## 1. Directory Structure (`resources/`)
The project follows a **Component-First** approach, separating layout logic from content and UI atoms.

```text
resources/
├── css/
│   ├── app.css             # Tailwind Directives & Imports
│   ├── tokens.css          # Design System Variables (The Source of Truth)
│   ├── components.css      # Custom complex component styling
│   └── vendor/             # Third-party styles (Charts, Datepickers)
├── js/
│   ├── app.js              # Entry point & Global Init
│   ├── modules/            # Logical units (Cart, Auth, Search)
│   ├── api/                # Axios/Fetch services for REST endpoints
│   └── components/         # Vanilla JS / Alpine.js component logic
└── views/
    ├── layouts/            # Master application shells
    ├── components/         # Atomic & Molecular Blade components
    ├── partials/           # Common structural fragments
    ├── pages/              # Domain-specific page views
    ├── emails/             # Transactional MJML/Blade templates
    └── errors/             # Custom 404, 500, Maintenance pages
```

---

## 2. Master Layout Architecture
We utilize three primary layouts to handle the distinct security and UX contexts defined in the design phases.

| Layout | Blade File | Used For |
| :--- | :--- | :--- |
| **Customer Shell** | `layouts.app` | Storefront, Product Discovery, Checkout. |
| **Admin Shell** | `layouts.admin` | Inventory, Orders, CRM, Analytics. |
| **Guest Shell** | `layouts.guest` | Login, Register, Password Reset. |

### Global Structural Stacks
*   `@stack('styles')`: For page-specific CSS (e.g., specialized charts).
*   `@stack('scripts')`: For page-specific JS modules.
*   `@yield('content')`: The primary injection point for page content.

---

## 3. Atomic Blade Component Strategy
Every recurring UI element from `{{DATA:COMPONENTS:COMPONENTS_3}}` is mapped to a `@props` driven Blade component.

### UI Elements (`components/ui/`)
*   `x-button`: Supports variants (primary, secondary, outline) and loading states.
*   `x-input`: Includes integrated error handling (`@error`) and floating labels.
*   `x-badge`: Status mapping for Orders (Pending, Paid, Shipped).
*   `x-modal`: Generic wrapper using `<dialog>` or Alpine.js for focus-trapping.

### Ecommerce Specialized (`components/ecommerce/`)
*   `x-product-card`: Shared between Home, Catalog, and Wishlist.
*   `x-order-summary`: Sticky component used in Cart and Checkout.
*   `x-quantity-selector`: Shared between Cart and PDP with AJAX trigger.

---

## 4. Design Token Integration (`tokens.css`)
To maintain the "Award-Winning" aesthetic without code duplication, all colors and spacing are mapped to CSS variables.

```css
:root {
    /* Colors from {{DATA:DESIGN_SYSTEM:DESIGN_SYSTEM_1}} */
    --color-primary: #f97316;
    --color-surface: #f9f9ff;
    --color-text: #111827;
    
    /* Spacing & Radii */
    --radius-base: 8px;
    --radius-card: 12px;
    --gutter: 2rem;
}
```

---

## 5. API-Ready Data Binding
Every page is architected to be "Pluggable," supporting both SSR (Server Side Rendering) and CSR (Client Side Rendering) via AJAX.

### Implementation Pattern
```blade
{{-- Example: Product Grid Component --}}
<div class="grid grid-cols-1 md:grid-cols-4 gap-6">
    @forelse($products as $product)
        <x-ecommerce.product-card :product="$product" />
    @empty
        <x-ui.empty-state 
            title="No products found" 
            icon="search-off" 
            cta-link="{{ route('shop') }}" 
        />
    @endforelse
</div>
```

---

## 6. JavaScript Architecture (Non-Coupled)
We avoid inline scripts. All interactivity is handled via modules located in `resources/js/modules/`.

*   **Cart Service:** Manages `localStorage` and `POST` requests to `/api/cart`.
*   **Search Overlay:** Debounced fetching for the global search bar.
*   **Interaction Layer:** Logic for "Sticky Header Shrink" and "Skeleton Shimmer" entrance animations.

---

## 7. Performance & Optimization Checklist
1.  **Vite Asset Bundling:** Code-split Admin vs. Customer JS bundles.
2.  **Blade Icons:** Use SVG sprites or Blade-icons package to avoid heavy icon fonts.
3.  **Image Optimization:** Use `srcset` and WebP formats; lazy-load all off-screen product images.
4.  **Skeleton Loading:** Use the established `.animate-pulse` shimmer for all data-heavy AJAX sections.

---

## 8. Handoff Summary
The frontend is now fully modeled for a standard Laravel 12 / Vite setup. The design system is abstracted into tokens, components are modularized for Blade, and the folder structure is optimized for multi-developer collaboration.

**Ready for Implementation.**
,data_type: