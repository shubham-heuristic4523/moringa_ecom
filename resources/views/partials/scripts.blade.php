{{--
 | Partial: partials/scripts.blade.php
 | Purpose: Global JavaScript — sticky header, scroll effects, and page-level stack injection.
--}}

{{-- Sticky Header Scroll Effect --}}
<script>
    (function () {
        const nav = document.getElementById('global-nav');
        if (!nav) return;
        window.addEventListener('scroll', () => {
            if (window.scrollY > 10) {
                nav.classList.add('shadow-md');
                nav.classList.remove('shadow-sm');
            } else {
                nav.classList.remove('shadow-md');
                nav.classList.add('shadow-sm');
            }
        });
    })();
</script>

{{-- Page-specific scripts pushed from child views --}}
@stack('scripts')
