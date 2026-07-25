---
name: Modern Premium Ecommerce
colors:
  surface: '#f9f9ff'
  surface-dim: '#d3daef'
  surface-bright: '#f9f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f1f3ff'
  surface-container: '#e9edff'
  surface-container-high: '#e1e8fd'
  surface-container-highest: '#dce2f7'
  on-surface: '#141b2b'
  on-surface-variant: '#584237'
  inverse-surface: '#293040'
  inverse-on-surface: '#edf0ff'
  outline: '#8c7164'
  outline-variant: '#e0c0b1'
  surface-tint: '#9d4300'
  primary: '#9d4300'
  on-primary: '#ffffff'
  primary-container: '#f97316'
  on-primary-container: '#582200'
  inverse-primary: '#ffb690'
  secondary: '#006e2d'
  on-secondary: '#ffffff'
  secondary-container: '#7cf994'
  on-secondary-container: '#007230'
  tertiary: '#006591'
  on-tertiary: '#ffffff'
  tertiary-container: '#09a4e8'
  on-tertiary-container: '#003650'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#ffdbca'
  primary-fixed-dim: '#ffb690'
  on-primary-fixed: '#341100'
  on-primary-fixed-variant: '#783200'
  secondary-fixed: '#7ffc97'
  secondary-fixed-dim: '#62df7d'
  on-secondary-fixed: '#002109'
  on-secondary-fixed-variant: '#005320'
  tertiary-fixed: '#c9e6ff'
  tertiary-fixed-dim: '#89ceff'
  on-tertiary-fixed: '#001e2f'
  on-tertiary-fixed-variant: '#004c6e'
  background: '#f9f9ff'
  on-background: '#141b2b'
  surface-variant: '#dce2f7'
typography:
  display:
    fontFamily: Geist
    fontSize: 64px
    fontWeight: '700'
    lineHeight: '1.1'
    letterSpacing: -0.04em
  h1:
    fontFamily: Geist
    fontSize: 48px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.03em
  h1-mobile:
    fontFamily: Geist
    fontSize: 32px
    fontWeight: '700'
    lineHeight: '1.2'
    letterSpacing: -0.02em
  h2:
    fontFamily: Geist
    fontSize: 36px
    fontWeight: '600'
    lineHeight: '1.3'
    letterSpacing: -0.02em
  h3:
    fontFamily: Geist
    fontSize: 30px
    fontWeight: '600'
    lineHeight: '1.3'
    letterSpacing: -0.02em
  h4:
    fontFamily: Geist
    fontSize: 24px
    fontWeight: '600'
    lineHeight: '1.4'
    letterSpacing: -0.01em
  h5:
    fontFamily: Geist
    fontSize: 20px
    fontWeight: '600'
    lineHeight: '1.4'
    letterSpacing: '0'
  h6:
    fontFamily: Geist
    fontSize: 16px
    fontWeight: '600'
    lineHeight: '1.4'
    letterSpacing: '0'
  body-lg:
    fontFamily: Geist
    fontSize: 18px
    fontWeight: '400'
    lineHeight: '1.6'
  body-default:
    fontFamily: Geist
    fontSize: 16px
    fontWeight: '400'
    lineHeight: '1.6'
  body-sm:
    fontFamily: Geist
    fontSize: 14px
    fontWeight: '400'
    lineHeight: '1.5'
  caption:
    fontFamily: Geist
    fontSize: 12px
    fontWeight: '500'
    lineHeight: '1.4'
    letterSpacing: 0.02em
  nav:
    fontFamily: Geist
    fontSize: 14px
    fontWeight: '500'
    lineHeight: '1'
    letterSpacing: 0.01em
  button:
    fontFamily: Geist
    fontSize: 14px
    fontWeight: '600'
    lineHeight: '1'
    letterSpacing: 0.01em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 8px
  md: 12px
  lg: 16px
  xl: 20px
  2xl: 24px
  3xl: 32px
  4xl: 40px
  5xl: 48px
  6xl: 64px
  7xl: 80px
  8xl: 96px
  container-max: 1440px
  gutter: 24px
---

## Brand & Style

The design system is engineered to evoke a sense of "Precise Luxury"—a blend of high-end consumer aesthetics (Apple) and high-performance utility (Linear). The brand personality is professional, sophisticated, and quiet, allowing products and data to remain the focal point.

The design style utilizes **Minimalism** with **Tonal Layering**. It avoids unnecessary decorative elements, relying instead on expansive whitespace, razor-sharp typography, and subtle micro-interactions. The aesthetic is defined by:
- **Spatial Intent:** Generous breathing room between elements to reduce cognitive load.
- **Precision:** Perfect alignment to an 8px grid, ensuring a systematic and high-quality feel.
- **Warm Professionalism:** A background that is slightly off-white (#FFFDF8) to reduce eye strain and feel more premium than pure white.

## Colors

This design system uses a curated palette that balances high-energy action colors with a grounded, neutral foundation.

- **Primary & Actions:** The primary orange is used sparingly for key calls-to-action to ensure maximum conversion visibility.
- **The "Warm" Neutral:** The background is set to a subtle cream-white to differentiate the platform from "standard" SaaS tools and provide a more editorial, high-end feel.
- **Semantic Clarity:** Success, danger, and warning colors follow industry standards but are calibrated for high contrast against the surface colors to ensure accessibility.
- **Layering:** Use `--color-surface` for elements that sit on top of the background, and `--color-card` for specific content containers.

## Typography

The typography system uses **Geist** for its technical precision and modern character. 

- **Headings:** Utilize tight tracking (negative letter-spacing) for a "premium editorial" look. This creates a dense, confident visual block.
- **Body:** Standard line-height is set to 1.6 to ensure readability in product descriptions and data tables.
- **Hierarchy:** Contrast is achieved primarily through weight (Medium to Bold) rather than just size.
- **Responsiveness:** Large display and H1 styles must scale down on mobile to prevent awkward line breaks.

## Layout & Spacing

This design system is built on an **8px base grid system**, ensuring mathematical harmony across all components.

- **Layout Model:** A 12-column fluid grid for desktop with a maximum content width of 1440px. 
- **Margins & Gutters:** On desktop, use 32px or 40px for outer margins; on mobile, reduce to 16px. Gutters remain consistent at 24px for desktop to provide ample "air" between content blocks.
- **Vertical Rhythm:** Components should be separated using the higher end of the scale (48px to 96px) to maintain the premium, minimal feel. Avoid overcrowding elements.
- **Alignment:** All elements must snap to the 8px grid. Use "MD" (12px) or "LG" (16px) for internal component padding.

## Elevation & Depth

Hierarchy is established through **Ambient Shadows** and **Tonal Layers** rather than heavy borders.

- **Shadow Style:** Multi-layered, "Linear-style" shadows. These are highly diffused, using low-opacity neutrals (e.g., `rgba(0,0,0,0.04)`) to create a soft lift rather than a harsh drop.
- **Elevation Levels:**
  - **Level 1 (sm):** Used for cards and subtle interactive elements.
  - **Level 2 (md):** Used for hover states on cards and dropdown menus.
  - **Level 3 (lg):** Used for modals and floating action panels.
  - **Level 4 (xl):** Reserved for large overlays or high-priority notifications.
- **Surface Strategy:** Background elements are on `--color-background`. Elevated elements (cards/inputs) use `--color-surface` with a 1px border of `--color-border` to define the edge before the shadow begins.

## Shapes

The shape language is "Softly Geometric." It avoids the playfulness of fully rounded circles in favor of structured, elegant corners.

- **Standard Elements:** Buttons and Input fields should use `rounded-md` (8px).
- **Containers:** Product cards and dashboard widgets use `rounded-lg` (12px) or `rounded-xl` (16px).
- **Full Radius:** Only used for status badges (chips) and toggle switches.
- **Consistency:** Never mix sharp 0px corners with rounded elements in the same view.

## Components

### Buttons
- **Primary:** Background `--color-primary`, text white, no border. Transitions to `--color-primary-hover` (200ms).
- **Outline:** 1px border `--color-border`, text `--color-text`. On hover, background becomes `--color-background`.
- **States:** Loading states should use a centered spinner or a ghosting effect (opacity 0.7) with a "wait" cursor.

### Forms & Inputs
- **Inputs:** Height of 44px (large) or 40px (default). Background `--color-surface`, border `--color-border`. Focus state: 1px solid `--color-primary` with a subtle 3px spread ring of the primary color at 10% opacity.
- **Labels:** Use `body-sm` with `Medium` weight and `--color-heading`.

### Cards
- **Product Cards:** Minimalist. Large image area, 12px border radius, subtle `--color-border`. Use `body-default` for titles and `body-sm` with `--color-muted` for categories.
- **Dashboard Widgets:** White background, Level 1 shadow, 16px padding.

### Feedback & Transitions
- **Skeleton Screens:** Use a static light grey gradient that pulses slowly (1.5s duration) to represent loading data.
- **Interactions:** Subtle scale-up (1.02x) on product card hover to invite clicks. All hover transitions use `cubic-bezier(0.4, 0, 0.2, 1)`.

### Navigation
- **Top Bar:** 64px height, semi-transparent white background with a backdrop blur (blur-md) and a bottom border `--color-border`.