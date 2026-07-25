{{--
 | Partial: partials/head.blade.php
 | Purpose : Universal <head> section — design system tokens, fonts, Tailwind config.
 | Usage   : @include('partials.head', ['title' => 'Page Title', 'description' => '...'])
--}}
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- SEO --}}
<title>{{ $title ?? config('app.name', 'LUXE') }}</title>
@isset($description)
    <meta name="description" content="{{ $description }}">
@endisset

{{-- Canonical --}}
@isset($canonical)
    <link rel="canonical" href="{{ $canonical }}">
@endisset

{{-- Tailwind CSS CDN (replace with Vite build in production) --}}
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Geist:wght@400;500;600;700;900&display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>

{{-- Design System Tailwind Config --}}
<script id="tailwind-config">
    tailwind.config = {
        darkMode: "class",
        theme: {
            extend: {
                colors: {
                    "on-background":               "#141b2b",
                    "on-error-container":          "#93000a",
                    "inverse-primary":             "#ffb690",
                    "error":                       "#ba1a1a",
                    "surface-dim":                 "#d3daef",
                    "on-primary-fixed":            "#341100",
                    "tertiary-fixed":              "#c9e6ff",
                    "on-surface":                  "#141b2b",
                    "surface-variant":             "#dce2f7",
                    "on-tertiary-fixed":           "#001e2f",
                    "surface-container-high":      "#e1e8fd",
                    "surface-container-low":       "#f1f3ff",
                    "secondary-fixed-dim":         "#62df7d",
                    "on-tertiary":                 "#ffffff",
                    "on-secondary-fixed":          "#002109",
                    "error-container":             "#ffdad6",
                    "primary-container":           "#f97316",
                    "on-primary-fixed-variant":    "#783200",
                    "on-secondary":                "#ffffff",
                    "on-secondary-container":      "#007230",
                    "surface-container-lowest":    "#ffffff",
                    "primary-fixed":               "#ffdbca",
                    "secondary-fixed":             "#7ffc97",
                    "background":                  "#f9f9ff",
                    "tertiary-container":          "#09a4e8",
                    "on-secondary-fixed-variant":  "#005320",
                    "on-surface-variant":          "#584237",
                    "on-tertiary-fixed-variant":   "#004c6e",
                    "on-tertiary-container":       "#003650",
                    "secondary-container":         "#7cf994",
                    "surface-container":           "#e9edff",
                    "surface":                     "#f9f9ff",
                    "on-primary-container":        "#582200",
                    "outline":                     "#8c7164",
                    "tertiary":                    "#006591",
                    "on-error":                    "#ffffff",
                    "surface-bright":              "#f9f9ff",
                    "outline-variant":             "#e0c0b1",
                    "surface-container-highest":   "#dce2f7",
                    "primary":                     "#9d4300",
                    "on-primary":                  "#ffffff",
                    "secondary":                   "#006e2d",
                    "inverse-surface":             "#293040",
                    "primary-fixed-dim":           "#ffb690",
                    "tertiary-fixed-dim":          "#89ceff",
                    "surface-tint":                "#9d4300",
                    "inverse-on-surface":          "#edf0ff",
                },
                borderRadius: {
                    "DEFAULT": "0.25rem",
                    "lg":      "0.5rem",
                    "xl":      "0.75rem",
                    "full":    "9999px",
                },
                spacing: {
                    "xs":            "4px",
                    "sm":            "8px",
                    "base":          "8px",
                    "md":            "12px",
                    "lg":            "16px",
                    "xl":            "20px",
                    "2xl":           "24px",
                    "3xl":           "32px",
                    "4xl":           "40px",
                    "5xl":           "48px",
                    "6xl":           "64px",
                    "7xl":           "80px",
                    "8xl":           "96px",
                    "gutter":        "24px",
                    "container-max": "1440px",
                },
                fontFamily: {
                    "display":       ["Geist"],
                    "h1":            ["Geist"],
                    "h1-mobile":     ["Geist"],
                    "h2":            ["Geist"],
                    "h3":            ["Geist"],
                    "h4":            ["Geist"],
                    "h5":            ["Geist"],
                    "h6":            ["Geist"],
                    "body-lg":       ["Geist"],
                    "body-default":  ["Geist"],
                    "body-sm":       ["Geist"],
                    "button":        ["Geist"],
                    "nav":           ["Geist"],
                    "caption":       ["Geist"],
                },
                fontSize: {
                    "display":      ["64px", { lineHeight:"1.1",  letterSpacing:"-0.04em", fontWeight:"700" }],
                    "h1":           ["48px", { lineHeight:"1.2",  letterSpacing:"-0.03em", fontWeight:"700" }],
                    "h1-mobile":    ["32px", { lineHeight:"1.2",  letterSpacing:"-0.02em", fontWeight:"700" }],
                    "h2":           ["36px", { lineHeight:"1.3",  letterSpacing:"-0.02em", fontWeight:"600" }],
                    "h3":           ["30px", { lineHeight:"1.3",  letterSpacing:"-0.02em", fontWeight:"600" }],
                    "h4":           ["24px", { lineHeight:"1.4",  letterSpacing:"-0.01em", fontWeight:"600" }],
                    "h5":           ["20px", { lineHeight:"1.4",  letterSpacing:"0",       fontWeight:"600" }],
                    "h6":           ["16px", { lineHeight:"1.4",  letterSpacing:"0",       fontWeight:"600" }],
                    "body-lg":      ["18px", { lineHeight:"1.6",  fontWeight:"400" }],
                    "body-default": ["16px", { lineHeight:"1.6",  fontWeight:"400" }],
                    "body-sm":      ["14px", { lineHeight:"1.5",  fontWeight:"400" }],
                    "button":       ["14px", { lineHeight:"1",    letterSpacing:"0.01em",  fontWeight:"600" }],
                    "nav":          ["14px", { lineHeight:"1",    letterSpacing:"0.01em",  fontWeight:"500" }],
                    "caption":      ["12px", { lineHeight:"1.4",  letterSpacing:"0.02em",  fontWeight:"500" }],
                },
            }
        }
    }
</script>

{{-- Design Tokens CSS --}}
<link href="{{ asset('css/tokens.css') }}" rel="stylesheet"/>

{{-- Page-specific styles pushed from child views --}}
@stack('styles')
