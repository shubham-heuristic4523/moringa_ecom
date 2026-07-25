{{--
 | Partial: partials/scripts.blade.php
 | Purpose: Global JS — sticky header, API config, and page-level stack injection.
--}}

{{-- Global API Config (exposed to all JS modules) --}}
<script>
    window.APP_URL = '{{ rtrim(config("app.url"), "/") }}';
    window.LUXE_TOKEN = localStorage.getItem('luxe_token') ?? null;

    // Sticky Header Scroll Effect
    (function () {
        const nav = document.getElementById('global-nav');
        if (!nav) return;
        window.addEventListener('scroll', () => {
            nav.classList.toggle('shadow-md', window.scrollY > 10);
            nav.classList.toggle('shadow-sm', window.scrollY <= 10);
        });
    })();
</script>

{{-- Vite Assets --}}
@vite(['resources/js/app.js'])

{{-- Page-specific scripts pushed from child views --}}
@stack('scripts')
