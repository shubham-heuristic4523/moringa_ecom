{{--
 | Partial: partials/announcement-bar.blade.php
 | Purpose: Top announcement strip shown on all storefront pages.
 | Props  : $message (string) — defaults to free shipping notice.
--}}
<div class="bg-on-background text-on-tertiary font-caption text-caption py-xs px-gutter text-center flex justify-center items-center gap-base">
    <span class="material-symbols-outlined text-[16px]">local_shipping</span>
    {{ $message ?? 'Complimentary express shipping on all orders over $250' }}
</div>
