<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Moringa') | Pure, Natural Wellness</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/storefront.css'])
    @stack('styles')
</head>
<body class="sf-body">

    <div class="sf-bg-aurora" aria-hidden="true"><span></span><span></span><span></span></div>

    <div id="sfLoader">
        <div class="sf-loader-mark" id="sfLoaderMark">M</div>
        <div class="sf-loader-bar"><span></span></div>
    </div>

    @php
        $sfHomeUrl = isset($storeSlug) ? route('store', $storeSlug) : route('home');
    @endphp

    <div class="sf-topbar d-none d-md-block">
        <div class="container d-flex justify-content-between py-1">
            <span><i class="fa-solid fa-truck-fast me-1"></i> Free shipping on orders over ₹999</span>
            <span><i class="fa-solid fa-phone me-1"></i> Need help? +91 98765 43210</span>
        </div>
    </div>

    <header class="sf-header" id="sfHeader">
        <div class="container sf-header-main">
            <div class="d-flex align-items-center gap-3 gap-lg-4 flex-wrap flex-lg-nowrap">

                <a href="{{ $sfHomeUrl }}" class="sf-brand flex-shrink-0">
                    <span class="sf-brand-logo" id="sfBrandLogo">M</span>
                    <span class="sf-brand-name d-none d-sm-inline" id="sfBrandName">Moringa</span>
                </a>

                <div class="sf-search position-relative order-3 order-lg-2 w-100">
                    <div class="input-group">
                        <select class="form-select d-none d-md-block" style="max-width:9rem;" id="sfSearchCategory" aria-label="Category">
                            <option value="">All Categories</option>
                        </select>
                        <input type="search" class="form-control" id="sfSearchInput" placeholder="Search for products…" aria-label="Search products" autocomplete="off">
                        <button class="btn sf-icon-btn-plain" type="button" id="sfVoiceBtn" title="Voice search" style="border:none; background:#fff; color:var(--sf-text-soft);">
                            <i class="fa-solid fa-microphone"></i>
                        </button>
                        <button class="btn" type="button" id="sfSearchBtn"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                    <div class="sf-search-suggest" id="sfSearchSuggest"></div>
                </div>

                <div class="d-flex align-items-center gap-2 order-2 order-lg-3 flex-shrink-0 ms-lg-auto">
                    <a href="{{ $sfHomeUrl }}" class="sf-nav-link d-none d-lg-inline">Home</a>
                    <a href="{{ $sfHomeUrl }}#featured" class="sf-nav-link d-none d-lg-inline">Shop</a>
                    <a href="{{ $sfHomeUrl }}#categories" class="sf-nav-link d-none d-lg-inline">Categories</a>

                    <button type="button" class="sf-icon-btn" id="sfWishlistBtn" title="Wishlist" data-bs-toggle="offcanvas" data-bs-target="#sfWishlistDrawer">
                        <i class="fa-regular fa-heart"></i>
                        <span class="sf-badge-count d-none" id="sfWishlistCount">0</span>
                    </button>
                    <button type="button" class="sf-icon-btn" id="sfCartBtn" title="Cart" data-bs-toggle="offcanvas" data-bs-target="#sfCartDrawer">
                        <i class="fa-solid fa-bag-shopping"></i>
                        <span class="sf-badge-count d-none" id="sfCartCount">0</span>
                    </button>
                    <div class="d-flex align-items-center gap-2" id="sfAuthLinks">
                        <a href="{{ route('login') }}" class="sf-nav-link" id="sfLoginLink">Log In</a>
                        <a href="{{ route('register') }}" class="sf-btn sf-btn-primary sf-btn-sm" id="sfSignupLink">Sign Up</a>
                    </div>
                    <a href="{{ route('account') }}" class="sf-icon-btn d-none" title="My Account" id="sfAccountBtn">
                        <i class="fa-regular fa-user"></i>
                    </a>
                    <button type="button" class="sf-icon-btn d-none" title="Log Out" id="sfLogoutBtn">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="sf-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3">
                    <div class="sf-brand mb-3" style="color:#fff;">
                        <span class="sf-brand-logo" id="sfFooterLogo">M</span>
                        <span class="sf-brand-name" id="sfFooterBrandName">Moringa</span>
                    </div>
                    <p id="sfFooterTagline">Pure, natural moringa products for everyday wellness.</p>
                    <div class="d-flex gap-2 mt-3">
                        <a href="#" class="sf-social" aria-label="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="sf-social" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="sf-social" aria-label="Twitter"><i class="fa-brands fa-twitter"></i></a>
                        <a href="#" class="sf-social" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
                    </div>
                </div>
                <div class="col-6 col-lg-3">
                    <h6>Shop</h6>
                    <ul>
                        <li><a href="{{ $sfHomeUrl }}#featured">Featured Products</a></li>
                        <li><a href="{{ $sfHomeUrl }}#categories">Categories</a></li>
                        <li><a href="{{ $sfHomeUrl }}#new-arrivals">New Arrivals</a></li>
                        <li><a href="{{ $sfHomeUrl }}#best-sellers">Best Sellers</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6>Customer Service</h6>
                    <ul>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Shipping Policy</a></li>
                        <li><a href="#">Returns &amp; Refunds</a></li>
                        <li><a href="#">FAQs</a></li>
                    </ul>
                </div>
                <div class="col-6 col-lg-3">
                    <h6>Contact</h6>
                    <ul>
                        <li><i class="fa-solid fa-location-dot me-1"></i> Nashik, Maharashtra, India</li>
                        <li><i class="fa-solid fa-envelope me-1"></i> hello@moringa.test</li>
                        <li><i class="fa-solid fa-phone me-1"></i> +91 98765 43210</li>
                    </ul>
                </div>
            </div>
            <div class="sf-footer-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
                <span>&copy; {{ date('Y') }} <span id="sfFooterYearBrand">Moringa</span>. All rights reserved.</span>
                <div class="d-flex gap-3">
                    <i class="fa-brands fa-cc-visa sf-pay-icon"></i>
                    <i class="fa-brands fa-cc-mastercard sf-pay-icon"></i>
                    <i class="fa-brands fa-cc-paypal sf-pay-icon"></i>
                    <i class="fa-solid fa-money-bill-wave sf-pay-icon" title="Cash on Delivery"></i>
                </div>
            </div>
        </div>
    </footer>

    <!-- Cart Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sfCartDrawer">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title"><i class="fa-solid fa-bag-shopping me-2"></i>Your Cart</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body d-flex flex-column">
            <div id="sfCartItems" class="flex-grow-1"></div>
            <div class="border-top pt-3 mt-2">
                <div class="d-flex justify-content-between fw-semibold mb-3">
                    <span>Subtotal</span>
                    <span id="sfCartSubtotal">₹0.00</span>
                </div>
                <button type="button" class="sf-btn sf-btn-primary w-100 mb-2" id="sfCheckoutBtn">Proceed to Checkout</button>
                <a href="{{ route('cart') }}" class="sf-btn sf-btn-outline w-100">View Cart &amp; Apply Coupon</a>
            </div>
        </div>
    </div>

    <!-- Wishlist Offcanvas -->
    <div class="offcanvas offcanvas-end" tabindex="-1" id="sfWishlistDrawer">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title"><i class="fa-regular fa-heart me-2"></i>Your Wishlist</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
            <div id="sfWishlistItems"></div>
        </div>
    </div>

    <!-- Quick View Modal -->
    <div class="modal fade" id="sfQuickViewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content" style="border-radius:1rem; overflow:hidden;">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0" id="sfQuickViewBody"></div>
            </div>
        </div>
    </div>

    <div class="sf-toast-stack" id="sfToastStack"></div>

    <div class="sf-offer-popup" id="sfOfferPopup">
        <button type="button" class="sf-offer-popup-close" id="sfOfferPopupClose" aria-label="Dismiss">
            <i class="fa-solid fa-xmark"></i>
        </button>
        <div class="sf-offer-popup-icon"><i class="fa-solid fa-tags"></i></div>
        <div class="sf-offer-popup-body">
            <p class="sf-offer-popup-title" id="sfOfferPopupTitle">Special Offer</p>
            <p class="sf-offer-popup-desc" id="sfOfferPopupDesc"></p>
            <span class="sf-offer-popup-code" id="sfOfferPopupCode" style="display:none;"></span>
        </div>
    </div>

    <!-- Sale Modal -->
    <div class="modal fade" id="sfSaleModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content sf-sale-modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body pt-0 text-center">
                    <span class="sf-flash-live"><span class="dot"></span> Live Now</span>
                    <h3 class="fw-bold mt-2 mb-1" id="sfSaleModalName">Flash Sale</h3>
                    <p class="sf-flash-name mb-3">Ends in:</p>
                    <div class="sf-flash-timer justify-content-center mb-4" id="sfSaleModalTimer">
                        <div class="box"><div class="num" id="smD">00</div><div class="lbl">Days</div></div>
                        <div class="box"><div class="num" id="smH">00</div><div class="lbl">Hrs</div></div>
                        <div class="box"><div class="num" id="smM">00</div><div class="lbl">Min</div></div>
                        <div class="box" id="smSBox"><div class="num" id="smS">00</div><div class="lbl">Sec</div></div>
                    </div>
                    <div class="row g-3 text-start" id="sfSaleModalProducts"></div>
                    <a href="#" class="sf-btn sf-btn-outline w-100 mt-3" id="sfSaleModalShop">View Full Sale</a>
                </div>
            </div>
        </div>
    </div>

    <div class="sf-float-stack">
        <button type="button" class="sf-fab sale d-none" id="sfSaleFab" title="View current sale" aria-label="View current sale">
            <i class="fa-solid fa-bolt"></i>
        </button>
        <button type="button" class="sf-fab top" id="sfBackToTop" title="Back to top" aria-label="Back to top">
            <i class="fa-solid fa-arrow-up"></i>
        </button>
        <button type="button" class="sf-fab chat" id="sfChatBtn" title="Chat with us" aria-label="Chat with us">
            <i class="fa-solid fa-comment-dots"></i>
        </button>
        <a href="https://wa.me/919876543210" target="_blank" class="sf-fab whatsapp" title="WhatsApp us" aria-label="WhatsApp us">
            <i class="fa-brands fa-whatsapp"></i>
        </a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.SF_STORE_SLUG = @json($storeSlug ?? null);

        // ─── Page loader ─────────────────────────────────────────────────────
        window.addEventListener('load', () => {
            setTimeout(() => document.getElementById('sfLoader')?.classList.add('is-hidden'), 300);
        });

        // ─── Header shrink + shadow on scroll, back-to-top visibility ──────────
        (function () {
            const header = document.getElementById('sfHeader');
            const topBtn = document.getElementById('sfBackToTop');

            window.addEventListener('scroll', () => {
                const scrolled = window.scrollY > 30;
                header?.classList.toggle('is-scrolled', scrolled);
                topBtn?.classList.toggle('is-visible', window.scrollY > 500);
            });

            topBtn?.addEventListener('click', () => window.scrollTo({ top: 0, behavior: 'smooth' }));

            document.getElementById('sfChatBtn')?.addEventListener('click', () => {
                SF.toast('Live chat is coming soon — email us at hello@moringa.test in the meantime!');
            });
        })();

        // ─── Quick View modal: explicit close handler ──────────────────────────
        // Belt-and-braces alongside data-bs-dismiss: openQuickView() re-shows this
        // same modal element on every click, and calling `.hide()` directly on
        // whatever instance is currently attached is more reliable than relying
        // purely on Bootstrap's global delegated dismiss listener.
        (function () {
            const modalEl = document.getElementById('sfQuickViewModal');
            if (!modalEl) return;

            modalEl.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
                btn.addEventListener('click', () => {
                    bootstrap.Modal.getInstance(modalEl)?.hide();
                });
            });
        })();

        // ─── Ripple effect on .sf-btn ────────────────────────────────────────
        document.addEventListener('click', (e) => {
            const btn = e.target.closest('.sf-btn');
            if (!btn) return;

            const rect = btn.getBoundingClientRect();
            const ripple = document.createElement('span');
            const size = Math.max(rect.width, rect.height);
            ripple.className = 'sf-ripple';
            ripple.style.width = ripple.style.height = size + 'px';
            ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
            ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
            btn.appendChild(ripple);
            setTimeout(() => ripple.remove(), 650);
        });

        // ─── Scroll reveal ───────────────────────────────────────────────────
        (function () {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('is-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12 });

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
            });
        })();

        // ─── Shared storefront helpers: theming, cart, wishlist, toasts ─────────
        const SF = (function () {
            const CART_KEY = 'sf_cart';
            const WISHLIST_KEY = 'sf_wishlist';

            function money(value) { return '₹' + parseFloat(value ?? 0).toFixed(2); }

            function escapeHtml(value) {
                const div = document.createElement('div');
                div.textContent = value ?? '';
                return div.innerHTML;
            }

            function toast(message, variant = 'success') {
                const stack = document.getElementById('sfToastStack');
                const el = document.createElement('div');
                el.className = `toast align-items-center text-bg-${variant === 'success' ? 'dark' : variant} border-0 show`;
                el.innerHTML = `<div class="d-flex"><div class="toast-body">${escapeHtml(message)}</div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>`;
                stack.appendChild(el);
                setTimeout(() => el.remove(), 3200);
            }

            function getToken() { return localStorage.getItem('token'); }
            function isLoggedIn() { return !!getToken(); }
            function authHeaders() {
                const token = getToken();
                return token ? { 'Authorization': 'Bearer ' + token } : {};
            }
            function loginUrl() { return '/login?redirect=' + encodeURIComponent(window.location.pathname + window.location.search); }
            function registerUrl() {
                const redirect = encodeURIComponent(window.location.pathname + window.location.search);
                return window.SF_STORE_SLUG
                    ? `/register?store=${encodeURIComponent(window.SF_STORE_SLUG)}&redirect=${redirect}`
                    : `/register?redirect=${redirect}`;
            }

            function readCart() { try { return JSON.parse(localStorage.getItem(CART_KEY)) ?? []; } catch { return []; } }
            function writeCart(items) { localStorage.setItem(CART_KEY, JSON.stringify(items)); renderCart(); }
            function clearCart() { localStorage.removeItem(CART_KEY); renderCart(); }

            // Guest wishlist lives in localStorage. Logged-in wishlist is the
            // real backend (/api/wishlists) — wishlistIds mirrors it locally
            // so product cards can synchronously check "is this wishlisted".
            function readWishlist() { try { return JSON.parse(localStorage.getItem(WISHLIST_KEY)) ?? []; } catch { return []; } }
            function writeWishlist(items) { localStorage.setItem(WISHLIST_KEY, JSON.stringify(items)); renderWishlist(); }
            let wishlistIds = new Set();

            // product.max_stock is the number of units available (null = not
            // tracked/unlimited). Adding more than what's left clamps the
            // quantity to what's actually in stock and warns instead of
            // silently over-committing — checkout would reject it anyway,
            // but this catches it before the customer even gets there.
            function addToCart(product, qty = 1) {
                const items = readCart();
                const variantId = product.variant_id ?? null;
                const existing = items.find(i => i.id === product.id && (i.variant_id ?? null) === variantId);
                const maxStock = product.max_stock ?? null;
                const price = (product.sale_price && parseFloat(product.sale_price) < parseFloat(product.regular_price))
                    ? product.sale_price : product.regular_price;

                const currentQty = existing?.qty ?? 0;

                if (maxStock != null && currentQty >= maxStock) {
                    toast(`Only ${maxStock} of "${product.name}" available — you already have the max in your cart.`, 'warning');
                    return;
                }

                let nextQty = currentQty + qty;
                let clamped = false;

                if (maxStock != null && nextQty > maxStock) {
                    nextQty = maxStock;
                    clamped = true;
                }

                if (existing) {
                    existing.qty = nextQty;
                    existing.max_stock = maxStock;
                } else {
                    items.push({
                        id: product.id, name: product.name, price: parseFloat(price ?? 0),
                        image: product.thumbnail_url, qty: nextQty,
                        variant_id: variantId, variant_label: product.variant_label ?? null, max_stock: maxStock,
                    });
                }
                writeCart(items);

                toast(
                    clamped ? `Only ${maxStock} of "${product.name}" available — added what's in stock.` : `${product.name} added to cart.`,
                    clamped ? 'warning' : 'success'
                );
            }

            // variantId defaults to null so existing call sites (product cards,
            // quick view) that don't offer variant selection keep working.
            function removeFromCart(id, variantId = null) {
                writeCart(readCart().filter(i => !(i.id === id && (i.variant_id ?? null) === variantId)));
            }

            function setQty(id, qty, variantId = null) {
                const items = readCart();
                const item = items.find(i => i.id === id && (i.variant_id ?? null) === variantId);
                if (!item) return;
                item.qty = Math.max(1, qty);
                writeCart(items);
            }

            function cartCount() { return readCart().reduce((sum, i) => sum + i.qty, 0); }
            function cartSubtotal() { return readCart().reduce((sum, i) => sum + i.qty * i.price, 0); }

            function renderCart() {
                const container = document.getElementById('sfCartItems');
                const items = readCart();

                document.querySelectorAll('#sfCartCount').forEach(el => {
                    const count = cartCount();
                    el.textContent = count;
                    el.classList.toggle('d-none', count === 0);
                });
                document.getElementById('sfCartSubtotal').textContent = money(cartSubtotal());

                if (!container) return;

                container.innerHTML = items.length ? items.map(item => `
                    <div class="sf-cart-item">
                        ${item.image ? `<img src="${item.image}" alt="">` : `<div class="sf-no-image" style="width:4.2rem;height:4.2rem;border-radius:.6rem;background:var(--sf-bg);display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-seedling"></i></div>`}
                        <div class="flex-grow-1">
                            <p class="fw-semibold mb-1" style="font-size:.88rem;"><a href="/product/${item.id}" style="color:inherit;">${escapeHtml(item.name)}</a></p>
                            ${item.variant_label ? `<p class="mb-1 text-muted" style="font-size:.74rem;">${escapeHtml(item.variant_label)}</p>` : ''}
                            <p class="mb-1 text-muted" style="font-size:.82rem;">${money(item.price)}</p>
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="sf-qty-control">
                                    <button type="button" data-qty-down="${item.id}" data-variant="${item.variant_id ?? ''}">−</button>
                                    <span>${item.qty}</span>
                                    <button type="button" data-qty-up="${item.id}" data-variant="${item.variant_id ?? ''}">+</button>
                                </div>
                                <button type="button" class="btn btn-sm text-danger" data-remove="${item.id}" data-variant="${item.variant_id ?? ''}"><i class="fa-solid fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `).join('') : `<div class="sf-empty"><i class="fa-solid fa-bag-shopping mb-2 d-block fs-3"></i>Your cart is empty.</div>`;

                container.querySelectorAll('[data-qty-up]').forEach(b => b.addEventListener('click', () => {
                    const variantId = b.dataset.variant ? parseInt(b.dataset.variant) : null;
                    const item = readCart().find(i => i.id == b.dataset.qtyUp && (i.variant_id ?? null) === variantId);
                    if (!item) return;
                    if (item.max_stock != null && item.qty >= item.max_stock) {
                        toast(`Only ${item.max_stock} of "${item.name}" available.`, 'warning');
                        return;
                    }
                    setQty(parseInt(b.dataset.qtyUp), item.qty + 1, variantId);
                }));
                container.querySelectorAll('[data-qty-down]').forEach(b => b.addEventListener('click', () => {
                    const variantId = b.dataset.variant ? parseInt(b.dataset.variant) : null;
                    const item = readCart().find(i => i.id == b.dataset.qtyDown && (i.variant_id ?? null) === variantId);
                    setQty(parseInt(b.dataset.qtyDown), (item?.qty ?? 1) - 1, variantId);
                }));
                container.querySelectorAll('[data-remove]').forEach(b => b.addEventListener('click', () => {
                    removeFromCart(parseInt(b.dataset.remove), b.dataset.variant ? parseInt(b.dataset.variant) : null);
                }));
            }

            async function toggleWishlist(product) {
                if (isLoggedIn()) {
                    const wasWishlisted = wishlistIds.has(product.id);

                    try {
                        if (wasWishlisted) {
                            await fetch('/api/wishlists/' + product.id, { method: 'DELETE', headers: authHeaders() });
                            wishlistIds.delete(product.id);
                            toast(`${product.name} removed from wishlist.`);
                        } else {
                            const res = await fetch('/api/wishlists', {
                                method: 'POST',
                                headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', ...authHeaders() },
                                body: JSON.stringify({ product_id: product.id }),
                            });
                            if (res.ok) {
                                wishlistIds.add(product.id);
                                toast(`${product.name} added to wishlist.`);
                            }
                        }
                    } catch (err) {
                        toast('Could not update wishlist.', 'warning');
                        return;
                    }

                    document.querySelectorAll(`[data-wishlist-btn="${product.id}"]`).forEach(btn => {
                        btn.classList.toggle('is-active', wishlistIds.has(product.id));
                    });
                    fetchWishlistFromServer();
                    return;
                }

                const items = readWishlist();
                const idx = items.findIndex(i => i.id === product.id);

                if (idx > -1) {
                    items.splice(idx, 1);
                    toast(`${product.name} removed from wishlist.`);
                } else {
                    items.push({ id: product.id, name: product.name, price: product.regular_price, image: product.thumbnail_url });
                    toast(`${product.name} added to wishlist. Log in to keep it saved to your account.`);
                }
                writeWishlist(items);
                document.querySelectorAll(`[data-wishlist-btn="${product.id}"]`).forEach(btn => {
                    btn.classList.toggle('is-active', idx === -1);
                });
            }

            function isWishlisted(id) {
                return isLoggedIn() ? wishlistIds.has(id) : readWishlist().some(i => i.id === id);
            }

            async function removeWishlistItem(id) {
                if (isLoggedIn()) {
                    await fetch('/api/wishlists/' + id, { method: 'DELETE', headers: authHeaders() });
                    wishlistIds.delete(id);
                    fetchWishlistFromServer();
                } else {
                    writeWishlist(readWishlist().filter(i => i.id != id));
                }
            }

            function renderWishlistItems(items) {
                const container = document.getElementById('sfWishlistItems');

                document.querySelectorAll('#sfWishlistCount').forEach(el => {
                    el.textContent = items.length;
                    el.classList.toggle('d-none', items.length === 0);
                });

                if (!container) return;

                container.innerHTML = items.length ? items.map(item => `
                    <div class="sf-cart-item">
                        ${item.image ? `<img src="${item.image}" alt="">` : `<div style="width:4.2rem;height:4.2rem;border-radius:.6rem;background:var(--sf-bg);display:flex;align-items:center;justify-content:center;"><i class="fa-solid fa-seedling"></i></div>`}
                        <div class="flex-grow-1">
                            <p class="fw-semibold mb-1" style="font-size:.88rem;"><a href="/product/${item.id}" style="color:inherit;">${escapeHtml(item.name)}</a></p>
                            <p class="mb-2 text-muted" style="font-size:.82rem;">${money(item.price)}</p>
                            <button type="button" class="btn btn-sm text-danger p-0" data-remove-wish="${item.id}"><i class="fa-solid fa-trash me-1"></i>Remove</button>
                        </div>
                    </div>
                `).join('') : `<div class="sf-empty"><i class="fa-regular fa-heart mb-2 d-block fs-3"></i>Your wishlist is empty.${isLoggedIn() ? '' : ' <a href="' + loginUrl() + '">Log in</a> to keep it saved.'}</div>`;

                container.querySelectorAll('[data-remove-wish]').forEach(b => b.addEventListener('click', () => removeWishlistItem(parseInt(b.dataset.removeWish))));
            }

            function renderWishlist() {
                if (isLoggedIn()) { fetchWishlistFromServer(); return; }
                renderWishlistItems(readWishlist());
            }

            async function fetchWishlistFromServer() {
                try {
                    const res = await fetch('/api/wishlists', { headers: authHeaders() });
                    const payload = await res.json();
                    const rows = payload.data ?? [];

                    wishlistIds = new Set(rows.map(r => r.product_id));
                    renderWishlistItems(rows.map(r => ({
                        id: r.product_id, name: r.product?.name, price: r.product?.sale_price ?? r.product?.regular_price,
                        image: r.product?.thumbnail_url,
                    })));
                    document.dispatchEvent(new CustomEvent('sf:wishlist-loaded'));
                } catch (err) {
                    // Leave wishlistIds as-is — hearts just won't reflect state until retried.
                }
            }

            document.getElementById('sfCheckoutBtn')?.addEventListener('click', () => {
                if (!cartCount()) { toast('Your cart is empty.', 'warning'); return; }
                window.location.href = '/checkout';
            });

            renderCart();
            renderWishlist();

            const accountBtn = document.getElementById('sfAccountBtn');
            const logoutBtn = document.getElementById('sfLogoutBtn');
            const authLinks = document.getElementById('sfAuthLinks');

            if (isLoggedIn()) {
                authLinks?.classList.add('d-none');
                accountBtn?.classList.remove('d-none');
                logoutBtn?.classList.remove('d-none');
            } else {
                document.getElementById('sfLoginLink')?.setAttribute('href', loginUrl());
                document.getElementById('sfSignupLink')?.setAttribute('href', registerUrl());
            }

            logoutBtn?.addEventListener('click', async () => {
                try {
                    await fetch('/api/logout', { method: 'POST', headers: authHeaders() });
                } catch (err) {
                    // Ignore network errors — we clear the local token regardless.
                }
                localStorage.removeItem('token');
                window.location.href = '/';
            });

            return {
                money, escapeHtml, toast, getToken, isLoggedIn, authHeaders, loginUrl, registerUrl,
                addToCart, removeFromCart, setQty, cartCount, cartSubtotal, clearCart, readCart,
                toggleWishlist, isWishlisted, readWishlist,
            };
        })();

        // ─── Branding + theming (slug-aware) ─────────────────────────────────
        (function () {
            const url = window.SF_STORE_SLUG
                ? '/api/settings?store=' + encodeURIComponent(window.SF_STORE_SLUG)
                : '/api/settings';

            fetch(url)
                .then(res => res.json())
                .then(payload => {
                    const settings = payload.data;
                    if (!settings) return;

                    window.SF_ADMIN_ID = payload.admin_id ?? null;

                    const root = document.documentElement;
                    if (settings.primary_color) root.style.setProperty('--brand-primary', settings.primary_color);
                    if (settings.secondary_color) root.style.setProperty('--brand-secondary', settings.secondary_color);
                    if (settings.accent_color) root.style.setProperty('--brand-accent', settings.accent_color);

                    document.querySelectorAll('#sfBrandName, #sfFooterBrandName, #sfFooterYearBrand').forEach(el => {
                        el.textContent = settings.site_name ?? 'Moringa';
                    });

                    const tagline = document.getElementById('sfFooterTagline');
                    if (tagline && settings.tagline) tagline.textContent = settings.tagline;

                    document.querySelectorAll('#sfBrandLogo, #sfFooterLogo, #sfLoaderMark').forEach(el => {
                        if (settings.logo_url) {
                            el.innerHTML = `<img src="${settings.logo_url}" alt="${settings.site_name ?? 'Logo'}" style="width:100%;height:100%;object-fit:cover;">`;
                        } else {
                            el.textContent = (settings.site_name ?? 'M').charAt(0).toUpperCase();
                        }
                    });

                    document.dispatchEvent(new CustomEvent('sf:settings-loaded', {
                        detail: { ...settings, admin_id: window.SF_ADMIN_ID },
                    }));
                })
                .catch(() => {});
        })();

        // ─── Offers popup (site-wide, pops in/out and cycles through offers) ──
        (function () {
            const popup = document.getElementById('sfOfferPopup');
            if (!popup) return;

            const DISMISS_KEY = 'sf_offer_popup_dismissed';
            if (sessionStorage.getItem(DISMISS_KEY)) return;

            const titleEl = document.getElementById('sfOfferPopupTitle');
            const descEl = document.getElementById('sfOfferPopupDesc');
            const codeEl = document.getElementById('sfOfferPopupCode');

            const VISIBLE_MS = 6000; // how long each offer stays on screen
            const GAP_MS = 650;      // pop-out transition time before the next one pops in

            let offers = [];
            let index = 0;
            let hideTimer = null;
            let showTimer = null;
            let dismissed = false;

            function renderOffer(offer) {
                const value = offer.discount_type === 'percentage'
                    ? `${parseFloat(offer.discount_value)}% OFF`
                    : `${SF.money(offer.discount_value)} OFF`;

                titleEl.textContent = offer.title;
                descEl.textContent = value + (offer.min_order_amount ? ` on orders over ${SF.money(offer.min_order_amount)}` : '');

                if (offer.type === 'coupon' && offer.code) {
                    codeEl.textContent = offer.code;
                    codeEl.style.display = 'inline-block';
                } else {
                    codeEl.style.display = 'none';
                }
            }

            // Pops the card in, holds it, pops it out, then swaps to the next
            // offer and repeats — for as long as there's more than one active
            // offer. A single offer just pops in once and stays put.
            function cycle() {
                if (dismissed) return;

                renderOffer(offers[index]);
                popup.classList.add('is-visible');

                if (offers.length <= 1) return;

                hideTimer = setTimeout(() => {
                    popup.classList.remove('is-visible');
                    showTimer = setTimeout(() => {
                        if (dismissed) return;
                        index = (index + 1) % offers.length;
                        cycle();
                    }, GAP_MS);
                }, VISIBLE_MS);
            }

            document.addEventListener('sf:settings-loaded', function onSettings(e) {
                document.removeEventListener('sf:settings-loaded', onSettings);

                const ownerFilter = e.detail?.admin_id ? { owner_id: e.detail.admin_id } : {};

                fetch('/api/offers/active?' + new URLSearchParams({ limit: 5, ...ownerFilter }))
                    .then(res => res.json())
                    .then(payload => {
                        offers = payload.data ?? [];
                        if (offers.length) setTimeout(cycle, 800);
                    })
                    .catch(() => {});
            });

            document.getElementById('sfOfferPopupClose')?.addEventListener('click', () => {
                dismissed = true;
                clearTimeout(hideTimer);
                clearTimeout(showTimer);
                popup.classList.remove('is-visible');
                sessionStorage.setItem(DISMISS_KEY, '1');
            });
        })();

        // ─── Sale FAB + popup (site-wide) ────────────────────────────────────
        // Shows a floating "Sale" button next to Back-to-Top whenever there's
        // an active flash sale; clicking it pops up the name + a live countdown.
        (function () {
            const fab = document.getElementById('sfSaleFab');
            if (!fab) return;

            let saleTimer = null;

            function startModalCountdown(target) {
                clearInterval(saleTimer);

                const secondsEl = document.getElementById('smS');
                const secondsBox = document.getElementById('smSBox');

                function tick() {
                    const diff = target - Date.now();
                    if (diff <= 0) { clearInterval(saleTimer); fab.classList.add('d-none'); return; }

                    const d = Math.floor(diff / 86400000);
                    const h = Math.floor((diff % 86400000) / 3600000);
                    const m = Math.floor((diff % 3600000) / 60000);
                    const s = Math.floor((diff % 60000) / 1000);

                    document.getElementById('smD').textContent = String(d).padStart(2, '0');
                    document.getElementById('smH').textContent = String(h).padStart(2, '0');
                    document.getElementById('smM').textContent = String(m).padStart(2, '0');
                    secondsEl.textContent = String(s).padStart(2, '0');

                    secondsEl.classList.remove('is-ticking');
                    void secondsEl.offsetWidth;
                    secondsEl.classList.add('is-ticking');

                    secondsBox?.classList.toggle('is-urgent', diff <= 60 * 60 * 1000);
                }
                tick();
                saleTimer = setInterval(tick, 1000);
            }

            function renderModalProducts(products) {
                const grid = document.getElementById('sfSaleModalProducts');
                const shown = products.slice(0, 6);

                grid.innerHTML = shown.map(p => `
                    <div class="col-6 col-md-4">
                        <a href="/product/${p.id}" class="sf-product-card d-block">
                            <div class="sf-product-media" style="aspect-ratio:1;">
                                ${p.thumbnail_url ? `<img src="${p.thumbnail_url}" alt="${SF.escapeHtml(p.name)}">` : `<div class="sf-no-image"><i class="fa-solid fa-seedling"></i></div>`}
                            </div>
                            <div class="sf-product-body">
                                <p class="sf-product-name">${SF.escapeHtml(p.name)}</p>
                                <div class="sf-price-row"><span class="sf-price-now">${SF.money(p.sale_price ?? p.regular_price)}</span></div>
                            </div>
                        </a>
                    </div>
                `).join('');
            }

            document.addEventListener('sf:settings-loaded', function onSettings(e) {
                document.removeEventListener('sf:settings-loaded', onSettings);

                const ownerFilter = e.detail?.admin_id ? { owner_id: e.detail.admin_id } : {};

                fetch('/api/flash-sales/active?' + new URLSearchParams(ownerFilter))
                    .then(res => res.json())
                    .then(payload => {
                        const sale = payload.data;
                        const products = sale?.products ?? [];
                        if (!sale || !products.length) return;

                        fab.classList.remove('d-none');
                        document.getElementById('sfSaleModalName').textContent = sale.name;
                        document.getElementById('sfSaleModalShop').href =
                            (window.SF_STORE_SLUG ? '/store/' + window.SF_STORE_SLUG : '/') + '#flash-sale';

                        renderModalProducts(products);
                        startModalCountdown(new Date(sale.ends_at).getTime());
                    })
                    .catch(() => {});
            });

            fab.addEventListener('click', () => {
                bootstrap.Modal.getOrCreateInstance(document.getElementById('sfSaleModal')).show();
            });
        })();

        // ─── Search: category list + live suggestions ───────────────────────
        (function () {
            const categorySelect = document.getElementById('sfSearchCategory');
            const input = document.getElementById('sfSearchInput');
            const suggest = document.getElementById('sfSearchSuggest');
            const searchBtn = document.getElementById('sfSearchBtn');
            let debounceTimer = null;

            fetch('/api/categories').then(r => r.json()).then(payload => {
                (payload.data?.data ?? []).forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.id;
                    opt.textContent = cat.name;
                    categorySelect?.appendChild(opt);
                });
            }).catch(() => {});

            function runSearch() {
                const q = input.value.trim();
                if (!q) return;
                SF.toast(`Full search results for "${q}" — coming soon! Try the suggestions below as you type.`, 'warning');
            }

            searchBtn?.addEventListener('click', runSearch);
            input?.addEventListener('keydown', (e) => { if (e.key === 'Enter') runSearch(); });

            document.getElementById('sfVoiceBtn')?.addEventListener('click', () => {
                SF.toast('Voice search is coming soon.', 'warning');
            });

            input?.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                const q = input.value.trim();

                if (q.length < 2) { suggest.classList.remove('is-open'); return; }

                debounceTimer = setTimeout(async () => {
                    const params = new URLSearchParams({ search: q, status: 'active', per_page: 5 });
                    if (categorySelect?.value) params.set('category_id', categorySelect.value);
                    if (window.SF_ADMIN_ID) params.set('owner_id', window.SF_ADMIN_ID);

                    try {
                        const res = await fetch('/api/products?' + params.toString());
                        const payload = await res.json();
                        const results = payload.data?.data ?? [];

                        suggest.innerHTML = results.length
                            ? results.map(p => `
                                <a href="/product/${p.id}" data-suggest-id="${p.id}">
                                    ${p.thumbnail_url ? `<img src="${p.thumbnail_url}" style="width:2.2rem;height:2.2rem;border-radius:.4rem;object-fit:cover;">` : `<i class="fa-solid fa-seedling"></i>`}
                                    <span class="flex-grow-1">${SF.escapeHtml(p.name)}</span>
                                    <strong>${SF.money(p.sale_price ?? p.regular_price)}</strong>
                                </a>
                            `).join('')
                            : `<div class="p-3 text-muted small">No products match "${SF.escapeHtml(q)}".</div>`;

                        suggest.classList.add('is-open');
                    } catch (err) {
                        suggest.classList.remove('is-open');
                    }
                }, 300);
            });

            document.addEventListener('click', (e) => {
                if (!e.target.closest('.sf-search')) suggest.classList.remove('is-open');
            });
        })();
    </script>

    @stack('scripts')
</body>
</html>
