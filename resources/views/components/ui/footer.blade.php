{{--
 | Component: components/ui/footer.blade.php
 | Purpose  : Full storefront footer with brand, links, newsletter, and social icons.
 | Usage    : <x-ui.footer />
--}}

<footer class="bg-surface-container-highest dark:bg-inverse-surface border-t border-outline-variant w-full mt-8xl">
    <div class="w-full py-6xl px-gutter grid grid-cols-1 md:grid-cols-4 gap-3xl max-w-container-max mx-auto">

        {{-- Brand Column --}}
        <div class="flex flex-col gap-lg">
            <span class="font-h5 text-h5 text-on-surface font-bold tracking-tight">LUXE</span>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Elevating everyday essentials through exceptional craftsmanship and minimalist design.
            </p>
            <div class="flex gap-md mt-md">
                <a href="#" aria-label="Instagram" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">photo_camera</span>
                </a>
                <a href="#" aria-label="Twitter" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">tag</span>
                </a>
                <a href="#" aria-label="Facebook" class="text-on-surface-variant hover:text-primary transition-colors">
                    <span class="material-symbols-outlined text-[20px]">thumb_up</span>
                </a>
            </div>
        </div>

        {{-- Shop Links --}}
        <div class="flex flex-col gap-md">
            <h4 class="font-h6 text-h6 text-on-surface mb-xs uppercase tracking-wider">Shop</h4>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">New Arrivals</a>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">Best Sellers</a>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">Collections</a>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">Sale</a>
        </div>

        {{-- Support Links --}}
        <div class="flex flex-col gap-md">
            <h4 class="font-h6 text-h6 text-on-surface mb-xs uppercase tracking-wider">Support</h4>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">Privacy Policy</a>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">Terms of Service</a>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">Shipping Info</a>
            <a class="font-body-sm text-body-sm text-on-surface-variant hover:text-primary transition-colors hover:underline" href="#">Contact Us</a>
        </div>

        {{-- Newsletter --}}
        <div class="flex flex-col gap-md">
            <h4 class="font-h6 text-h6 text-on-surface mb-xs uppercase tracking-wider">Newsletter</h4>
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                Subscribe to receive updates, access to exclusive deals, and more.
            </p>
            <form class="mt-xs flex gap-xs" action="{{ route('newsletter.subscribe') }}" method="POST">
                @csrf
                <input type="email"
                       name="email"
                       required
                       placeholder="Enter your email address"
                       class="w-full h-[40px] bg-surface border border-outline px-md font-body-sm text-body-sm text-on-surface
                              focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary/10 rounded-sm"
                       aria-label="Newsletter email"/>
                <button type="submit"
                        class="bg-primary text-on-primary px-lg h-[40px] font-button text-button rounded-sm hover:bg-surface-tint transition-colors whitespace-nowrap">
                    Subscribe
                </button>
            </form>
        </div>

    </div>

    {{-- Bottom Bar --}}
    <div class="w-full border-t border-outline-variant/30 py-xl px-gutter">
        <div class="max-w-container-max mx-auto flex flex-col md:flex-row justify-between items-center gap-md">
            <p class="font-body-sm text-body-sm text-on-surface-variant">
                © {{ date('Y') }} LUXE Ecommerce. All rights reserved.
            </p>
            <div class="flex gap-md">
                <span class="material-symbols-outlined text-on-surface-variant text-[24px]">payments</span>
                <span class="material-symbols-outlined text-on-surface-variant text-[24px]">credit_card</span>
            </div>
        </div>
    </div>
</footer>
