{{--
 | Component: components/ui/footer-minimal.blade.php
 | Purpose  : Minimal footer used on checkout and transactional pages.
 | Usage    : <x-ui.footer-minimal />
--}}

<footer class="bg-surface-container-highest dark:bg-inverse-surface border-t border-outline-variant w-full py-6xl px-gutter mt-8xl">
    <div class="max-w-container-max mx-auto text-center font-body-sm text-body-sm text-on-surface-variant">
        © {{ date('Y') }} LUXE Ecommerce. All rights reserved.
    </div>
</footer>
