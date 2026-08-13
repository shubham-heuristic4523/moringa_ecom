@extends('layouts.storefront')

@section('title', 'Home')

@section('content')

    {{-- ── Hero ─────────────────────────────────────────────────────────── --}}
    <section class="sf-hero">
        <div id="sfHeroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-indicators sf-carousel-indicators">
                <button type="button" data-bs-target="#sfHeroCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#sfHeroCarousel" data-bs-slide-to="1"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="sf-hero-slide">
                        <span class="sf-hero-art s1"><span class="sf-hero-shape">@include('storefront.partials.leaf-branch')</span></span>
                        <span class="sf-hero-art s2"><span class="sf-hero-shape">@include('storefront.partials.leaf-branch')</span></span>
                        <div class="container position-relative">
                            <div class="row align-items-center">
                                <div class="col-lg-7 sf-hero-content">
                                    <span class="sf-eyebrow" style="color:#fff;"><i class="fa-solid fa-leaf"></i> 100% Natural</span>
                                    <h1 class="mt-2">Pure Moringa, Straight From Nature</h1>
                                    <p id="sfHeroTagline">Pure, natural moringa products for everyday wellness.</p>
                                    <div class="d-flex gap-3 flex-wrap mt-4">
                                        <a href="#featured" class="sf-btn sf-btn-light">Shop Now</a>
                                        <a href="#categories" class="sf-btn sf-btn-outline" style="border-color:rgba(255,255,255,.5); color:#fff;">Browse Categories</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sf-hero-card card-1 d-none d-lg-flex">
                            <span class="icon"><i class="fa-solid fa-truck-fast"></i></span>
                            <div><strong style="font-size:.85rem;">Free Delivery</strong><br><span class="text-muted" style="font-size:.75rem;">On orders over ₹999</span></div>
                        </div>
                        <div class="sf-hero-card card-2 d-none d-lg-flex">
                            <span class="icon"><i class="fa-solid fa-shield-heart"></i></span>
                            <div><strong style="font-size:.85rem;">Lab Tested</strong><br><span class="text-muted" style="font-size:.75rem;">100% purity guaranteed</span></div>
                        </div>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="sf-hero-slide" style="background: linear-gradient(120deg, color-mix(in srgb, var(--brand-accent) 90%, black 10%), var(--brand-primary));">
                        <span class="sf-hero-art s1"><span class="sf-hero-shape">@include('storefront.partials.leaf-branch')</span></span>
                        <div class="container position-relative">
                            <div class="row align-items-center">
                                <div class="col-lg-7 sf-hero-content">
                                    <span class="sf-eyebrow" style="color:#fff;"><i class="fa-solid fa-bolt"></i> Limited Time</span>
                                    <h1 class="mt-2">Seasonal Sale — Up To 30% Off</h1>
                                    <p>Stock up on your favorite wellness essentials before the sale ends.</p>
                                    <div class="d-flex gap-3 flex-wrap mt-4">
                                        <a href="#flash-sale" class="sf-btn sf-btn-light">View Deals</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#sfHeroCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#sfHeroCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </section>

    {{-- ── Trust Marquee ────────────────────────────────────────────────── --}}
    <div class="sf-marquee-wrap">
        <div class="sf-marquee-track" id="sfMarqueeTrack"></div>
    </div>

    {{-- ── Categories ───────────────────────────────────────────────────── --}}
    <section class="sf-section" id="categories">
        <div class="container">
            <div class="sf-section-head reveal">
                <p class="sf-eyebrow">Explore</p>
                <h2>Shop by Category</h2>
                <p>Find exactly what you're looking for.</p>
            </div>
            <div class="row g-3" id="categoryGrid">
                <div class="sf-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading categories…</div>
            </div>
        </div>
    </section>

    {{-- ── The Moringa Difference ───────────────────────────────────────── --}}
    <section class="sf-section sf-section-alt">
        <div class="container">
            <div class="row align-items-center g-5">
                <div class="col-lg-5">
                    <div class="sf-diff-graphic reveal reveal-zoom">
                        <span class="sf-diff-ring r1"></span>
                        <span class="sf-diff-ring r2"></span>
                        <div class="sf-diff-core"><i class="fa-solid fa-seedling"></i></div>
                        <span class="sf-diff-chip c1"><i class="fa-solid fa-leaf"></i> 100% Organic</span>
                        <span class="sf-diff-chip c2"><i class="fa-solid fa-star"></i> 4.8 Rated</span>
                        <span class="sf-diff-chip c3"><i class="fa-solid fa-flask"></i> Lab Tested</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="reveal reveal-right mb-4">
                        <p class="sf-eyebrow">Our Promise</p>
                        <h2 class="mb-2">The Moringa Difference</h2>
                        <p class="mb-0" style="color:var(--sf-text-soft);">From farm to bottle, every batch is grown, tested and packed with the same care — so what you take every morning is exactly what nature intended.</p>
                    </div>
                    <div>
                        <div class="sf-diff-item reveal reveal-right reveal-d1">
                            <div class="sf-diff-item-icon"><i class="fa-solid fa-tractor"></i></div>
                            <div>
                                <h6>Sustainably Farmed</h6>
                                <p>Sourced from trusted organic farms with zero harmful pesticides.</p>
                            </div>
                        </div>
                        <div class="sf-diff-item reveal reveal-right reveal-d2">
                            <div class="sf-diff-item-icon"><i class="fa-solid fa-vial-circle-check"></i></div>
                            <div>
                                <h6>Third-Party Lab Tested</h6>
                                <p>Every batch is verified for purity, potency and safety before it ships.</p>
                            </div>
                        </div>
                        <div class="sf-diff-item reveal reveal-right reveal-d3">
                            <div class="sf-diff-item-icon"><i class="fa-solid fa-box-open"></i></div>
                            <div>
                                <h6>Freshly Packed</h6>
                                <p>Small batches, packed on order, so potency never sits on a shelf.</p>
                            </div>
                        </div>
                        <div class="sf-diff-item reveal reveal-right reveal-d4">
                            <div class="sf-diff-item-icon"><i class="fa-solid fa-heart-circle-check"></i></div>
                            <div>
                                <h6>No Fillers, Ever</h6>
                                <p>Just moringa — no artificial additives, preservatives or fillers.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Stats ─────────────────────────────────────────────────────────── --}}
    <section class="sf-stats-band">
        <div class="sf-stats-art">@include('storefront.partials.leaf-scatter')</div>
        <div class="container">
            <div class="row g-4">
                <div class="col-6 col-lg-3 reveal reveal-d1">
                    <div class="sf-stat">
                        <div class="sf-stat-icon"><i class="fa-solid fa-users"></i></div>
                        <div class="sf-stat-num" data-count="50000" data-suffix="+">0</div>
                        <div class="sf-stat-label">Happy Customers</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal reveal-d2">
                    <div class="sf-stat">
                        <div class="sf-stat-icon"><i class="fa-solid fa-box"></i></div>
                        <div class="sf-stat-num" data-count="120000" data-suffix="+">0</div>
                        <div class="sf-stat-label">Orders Delivered</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal reveal-d3">
                    <div class="sf-stat">
                        <div class="sf-stat-icon"><i class="fa-solid fa-star"></i></div>
                        <div class="sf-stat-num" data-count="4.8" data-decimals="1">0</div>
                        <div class="sf-stat-label">Average Rating</div>
                    </div>
                </div>
                <div class="col-6 col-lg-3 reveal reveal-d4">
                    <div class="sf-stat">
                        <div class="sf-stat-icon"><i class="fa-solid fa-leaf"></i></div>
                        <div class="sf-stat-num" data-count="100" data-suffix="%">0</div>
                        <div class="sf-stat-label">Natural Ingredients</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Flash Sale ───────────────────────────────────────────────────── --}}
    <section class="sf-section pt-0 d-none" id="flash-sale">
        <div class="container">
            <div class="sf-flash reveal reveal-zoom">
                <div class="sf-flash-glow"></div>
                <div class="sf-flash-art">@include('storefront.partials.leaf-scatter')</div>
                <div class="row align-items-center g-4">
                    <div class="col-lg-4">
                        <span class="sf-flash-live"><span class="dot"></span> Live Now</span>
                        <span class="sf-eyebrow d-block"><i class="fa-solid fa-bolt"></i> Flash Sale</span>
                        <h2 class="mt-2 mb-2 fw-bold" id="sfFlashName">Deals Ending Soon</h2>
                        <p class="sf-flash-name mb-3">Grab these before time runs out.</p>
                        <div class="sf-flash-timer" id="sfFlashTimer">
                            <div class="box"><div class="num" id="fdD">00</div><div class="lbl">Days</div></div>
                            <div class="box"><div class="num" id="fdH">00</div><div class="lbl">Hrs</div></div>
                            <div class="box"><div class="num" id="fdM">00</div><div class="lbl">Min</div></div>
                            <div class="box" id="fdSBox"><div class="num" id="fdS">00</div><div class="lbl">Sec</div></div>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row g-3" id="flashSaleGrid"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Offers ───────────────────────────────────────────────────────── --}}
    <section class="sf-section sf-section-alt" id="offers">
        <div class="container">
            <div class="sf-section-head reveal">
                <p class="sf-eyebrow">Save More</p>
                <h2>Current Offers</h2>
                <p>Coupon codes and discounts you can use right now.</p>
            </div>
            <div class="row g-3" id="offersGrid"></div>
        </div>
    </section>

    {{-- ── Featured Products ────────────────────────────────────────────── --}}
    <section class="sf-section" id="featured">
        <div class="container">
            <div class="sf-section-head reveal">
                <p class="sf-eyebrow">Handpicked</p>
                <h2>Featured Products</h2>
                <p>A few of our customers' favorites, picked for quality and freshness.</p>
            </div>
            <div class="row g-4" id="featuredGrid">
                <div class="sf-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading products…</div>
            </div>
        </div>
    </section>

    {{-- ── Best Sellers ─────────────────────────────────────────────────── --}}
    <section class="sf-section sf-section-alt" id="best-sellers">
        <div class="container">
            <div class="sf-section-top reveal">
                <div>
                    <p class="sf-eyebrow">Customer Favorites</p>
                    <h2 class="mb-0">Best Sellers</h2>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="sf-scroll-btn" id="bsPrev"><i class="fa-solid fa-chevron-left"></i></button>
                    <button type="button" class="sf-scroll-btn" id="bsNext"><i class="fa-solid fa-chevron-right"></i></button>
                </div>
            </div>
            <div class="sf-scroller" id="bestSellerScroller">
                <div class="sf-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading products…</div>
            </div>
        </div>
    </section>

    {{-- ── New Arrivals ─────────────────────────────────────────────────── --}}
    <section class="sf-section" id="new-arrivals">
        <div class="container">
            <div class="sf-section-head reveal">
                <p class="sf-eyebrow">Just In</p>
                <h2>New Arrivals</h2>
                <p>The newest additions to our collection.</p>
            </div>
            <div class="row g-4" id="newArrivalsGrid">
                <div class="sf-empty"><i class="fa-solid fa-spinner fa-spin"></i> Loading products…</div>
            </div>
        </div>
    </section>

    {{-- ── Brands ───────────────────────────────────────────────────────── --}}
    <section class="sf-section sf-section-alt" id="brands">
        <div class="container">
            <div class="sf-section-head reveal">
                <p class="sf-eyebrow">Trusted</p>
                <h2>Top Brands</h2>
            </div>
            <div class="row g-3" id="brandGrid"></div>
        </div>
    </section>

    {{-- ── Certifications & Trust ───────────────────────────────────────── --}}
    <section class="sf-section">
        <div class="container">
            <div class="sf-section-head reveal">
                <p class="sf-eyebrow">Certified &amp; Trusted</p>
                <h2>Quality You Can Verify</h2>
                <p>Every claim we make is backed by testing, certification and a straightforward returns policy.</p>
            </div>
            <div class="row g-3">
                <div class="col-6 col-md-4 col-lg-2 reveal reveal-d1">
                    <div class="sf-cert-card">
                        <div class="sf-cert-icon"><i class="fa-solid fa-leaf"></i></div>
                        <h6>Organic Certified</h6>
                        <p>No pesticides, ever</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal reveal-d2">
                    <div class="sf-cert-card">
                        <div class="sf-cert-icon"><i class="fa-solid fa-flask-vial"></i></div>
                        <h6>Lab Tested</h6>
                        <p>Verified for purity</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal reveal-d3">
                    <div class="sf-cert-card">
                        <div class="sf-cert-icon"><i class="fa-solid fa-industry"></i></div>
                        <h6>GMP Certified</h6>
                        <p>Good manufacturing</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal reveal-d4">
                    <div class="sf-cert-card">
                        <div class="sf-cert-icon"><i class="fa-solid fa-paw"></i></div>
                        <h6>Cruelty-Free</h6>
                        <p>Never tested on animals</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal reveal-d5">
                    <div class="sf-cert-card">
                        <div class="sf-cert-icon"><i class="fa-solid fa-recycle"></i></div>
                        <h6>Eco Packaging</h6>
                        <p>Recyclable materials</p>
                    </div>
                </div>
                <div class="col-6 col-md-4 col-lg-2 reveal reveal-d6">
                    <div class="sf-cert-card">
                        <div class="sf-cert-icon"><i class="fa-solid fa-rotate-left"></i></div>
                        <h6>Easy Returns</h6>
                        <p>7-day return policy</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Customer Reviews ─────────────────────────────────────────────── --}}
    {{-- Sample/placeholder testimonials — there's no site-wide reviews API yet, only per-product reviews. --}}
    <section class="sf-section sf-section-alt">
        <div class="container">
            <div class="sf-section-head reveal">
                <p class="sf-eyebrow">Testimonials</p>
                <h2>What Our Customers Say</h2>
            </div>
            <div id="sfReviewCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="sf-testimonial text-center reveal reveal-zoom">
                                    <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i></div>
                                    <p class="quote">"The moringa powder has become part of my morning routine. Great quality and fast delivery every time."</p>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="sf-avatar">A</span>
                                        <div class="text-start">
                                            <p class="fw-semibold mb-0" style="font-size:.88rem;">Ananya S.</p>
                                            <span class="sf-verified"><i class="fa-solid fa-circle-check"></i> Verified Purchase</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="row justify-content-center">
                            <div class="col-md-8">
                                <div class="sf-testimonial text-center reveal reveal-zoom">
                                    <div class="stars"><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i></div>
                                    <p class="quote">"Genuinely fresh product and the packaging felt premium. Will be ordering again."</p>
                                    <div class="d-flex align-items-center justify-content-center gap-2">
                                        <span class="sf-avatar">R</span>
                                        <div class="text-start">
                                            <p class="fw-semibold mb-0" style="font-size:.88rem;">Rohit M.</p>
                                            <span class="sf-verified"><i class="fa-solid fa-circle-check"></i> Verified Purchase</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#sfReviewCarousel" data-bs-slide="prev" style="filter:invert(1); width:3rem;">
                    <span class="carousel-control-prev-icon"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#sfReviewCarousel" data-bs-slide="next" style="filter:invert(1); width:3rem;">
                    <span class="carousel-control-next-icon"></span>
                </button>
            </div>
        </div>
    </section>

    {{-- ── Newsletter ───────────────────────────────────────────────────── --}}
    <section class="sf-section">
        <div class="container">
            <div class="sf-newsletter reveal reveal-zoom">
                <div class="sf-newsletter-art">@include('storefront.partials.leaf-branch')</div>
                <div class="sf-newsletter-content">
                    <h2>Join Our Wellness Newsletter</h2>
                    <p class="text-white-50 mb-0">Get tips, offers, and new product updates in your inbox.</p>
                    <form id="sfNewsletterForm">
                        <div class="d-flex">
                            <input type="email" class="form-control" placeholder="Enter your email" required id="sfNewsletterEmail">
                            <button type="submit" class="btn sf-btn-primary" style="border-radius:0 999px 999px 0;">Subscribe</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('scripts')
<script>
(function () {
    const STAR_FULL = '<i class="fa-solid fa-star"></i>';
    const STAR_EMPTY = '<i class="fa-regular fa-star"></i>';

    // Rendered server-side once so JS-templated cards (offers) can reuse the
    // same botanical artwork as the static sections without duplicating it.
    const LEAF_BRANCH_SVG = `@include('storefront.partials.leaf-branch')`;

    function starRow(rating) {
        const r = Math.round(parseFloat(rating ?? 0));
        return Array.from({ length: 5 }, (_, i) => i < r ? STAR_FULL : STAR_EMPTY).join('');
    }

    // `stock` is null when the product has no variants — i.e. not tracked,
    // treated as always orderable (matches product.blade.php's activeStock()).
    function stockInfo(product) {
        if (!product.variants_count && !product.variants?.length) return { label: 'In Stock', cls: 'in', stock: null };
        const stock = (product.variants ?? []).reduce((sum, v) => sum + (v.stock ?? 0), 0);
        if (stock === 0) return { label: 'Out of Stock', cls: 'out', stock };
        if (stock <= 10) return { label: `Only ${stock} left — hurry!`, cls: 'low', stock };
        return { label: 'In Stock', cls: 'in', stock };
    }

    function productCardHtml(product) {
        const regular = parseFloat(product.regular_price ?? 0);
        const sale = product.sale_price !== null && product.sale_price !== undefined ? parseFloat(product.sale_price) : null;
        const onSale = sale !== null && sale < regular;
        const off = onSale ? Math.round((1 - sale / regular) * 100) : 0;
        const stock = stockInfo(product);
        const wishlisted = SF.isWishlisted(product.id);

        return `
            <div class="sf-product-card" data-product='${JSON.stringify(product).replace(/'/g, "&apos;")}'>
                <div class="sf-product-media">
                    <div class="sf-product-badges">
                        ${product.is_new ? '<span class="sf-pbadge new">New</span>' : ''}
                        ${onSale ? `<span class="sf-pbadge sale">-${off}%</span>` : ''}
                        ${!product.is_new && !onSale && product.is_featured ? '<span class="sf-pbadge featured">Featured</span>' : ''}
                    </div>
                    <div class="sf-product-actions">
                        <button type="button" class="sf-icon-btn ${wishlisted ? 'is-active' : ''}" data-wishlist-btn="${product.id}" title="Wishlist"><i class="fa-solid fa-heart"></i></button>
                        <button type="button" class="sf-icon-btn" data-quickview="${product.id}" title="Quick view"><i class="fa-solid fa-eye"></i></button>
                        <button type="button" class="sf-icon-btn" data-compare="${product.id}" title="Compare"><i class="fa-solid fa-code-compare"></i></button>
                    </div>
                    <a href="/product/${product.id}" class="sf-product-img-link" aria-label="${SF.escapeHtml(product.name)}">
                        ${product.thumbnail_url ? `<img src="${product.thumbnail_url}" alt="${SF.escapeHtml(product.name)}" loading="lazy">` : `<div class="sf-no-image"><i class="fa-solid fa-seedling"></i></div>`}
                    </a>
                    <div class="sf-quickview-trigger" data-quickview="${product.id}">Quick View</div>
                </div>
                <div class="sf-product-body">
                    ${product.category ? `<div class="sf-product-category">${SF.escapeHtml(product.category.name)}</div>` : ''}
                    <p class="sf-product-name"><a href="/product/${product.id}">${SF.escapeHtml(product.name)}</a></p>
                    <div class="sf-rating">
                        <span class="stars">${starRow(product.average_rating)}</span>
                        <span>(${product.total_reviews ?? 0})</span>
                    </div>
                    <div class="sf-price-row">
                        <span class="sf-price-now">${SF.money(onSale ? sale : regular)}</span>
                        ${onSale ? `<span class="sf-price-was">${SF.money(regular)}</span><span class="sf-price-off">${off}% OFF</span>` : ''}
                    </div>
                    <span class="sf-stock ${stock.cls}"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> ${stock.label}</span>
                    <div class="sf-product-cta">
                        <button type="button" class="sf-btn sf-btn-outline" data-add-cart="${product.id}"><i class="fa-solid fa-cart-plus"></i> Add</button>
                        <button type="button" class="sf-btn sf-btn-primary" data-buy-now="${product.id}">Buy Now</button>
                    </div>
                </div>
            </div>
        `;
    }

    function categoryCardHtml(category) {
        return `
            <div class="col-6 col-md-3 col-lg-2 reveal">
                <a href="#featured" class="sf-cat-card">
                    <div class="sf-cat-icon"><i class="fa-solid fa-leaf"></i></div>
                    <div class="sf-cat-name">${SF.escapeHtml(category.name)}</div>
                </a>
            </div>
        `;
    }

    function brandCardHtml(brand) {
        return `<div class="col-6 col-md-3 col-lg-2 reveal"><div class="sf-brand-card">${SF.escapeHtml(brand.name)}</div></div>`;
    }

    function offerCardHtml(offer) {
        const value = offer.discount_type === 'percentage' ? `${parseFloat(offer.discount_value)}% OFF` : `${SF.money(offer.discount_value)} OFF`;
        return `
            <div class="col-md-6 col-lg-4 reveal">
                <div class="sf-offer-card">
                    <div class="sf-offer-art">${LEAF_BRANCH_SVG}</div>
                    <div>
                        <span class="sf-eyebrow" style="color:rgba(255,255,255,.85);">${offer.type === 'coupon' ? 'Coupon Code' : 'Automatic Discount'}</span>
                        <h4 class="fw-bold mt-1 mb-1">${SF.escapeHtml(offer.title)}</h4>
                        <p class="mb-0" style="opacity:.9; font-size:.85rem;">${value}${offer.min_order_amount ? ` on orders over ${SF.money(offer.min_order_amount)}` : ''}</p>
                    </div>
                    ${offer.type === 'coupon' ? `<span class="sf-offer-code mt-3">${SF.escapeHtml(offer.code)}</span>` : ''}
                </div>
            </div>
        `;
    }

    async function loadProducts(gridId, params, emptyMessage) {
        const grid = document.getElementById(gridId);

        try {
            const query = new URLSearchParams({ status: 'active', per_page: 8, ...params });
            const response = await fetch('/api/products?' + query.toString());
            const payload = await response.json();
            const products = payload.data?.data ?? [];

            grid.innerHTML = products.length
                ? products.map(p => `<div class="col-6 col-md-4 col-lg-3 reveal">${productCardHtml(p)}</div>`).join('')
                : `<div class="sf-empty"><i class="fa-solid fa-box-open"></i> ${emptyMessage}</div>`;

            bindProductEvents(grid);
            grid.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
        } catch (err) {
            grid.innerHTML = `<div class="sf-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load products.</div>`;
        }
    }

    async function loadScrollerProducts(containerId, params) {
        const container = document.getElementById(containerId);

        try {
            const query = new URLSearchParams({ status: 'active', per_page: 10, ...params });
            const response = await fetch('/api/products?' + query.toString());
            const payload = await response.json();
            const products = payload.data?.data ?? [];

            container.innerHTML = products.length
                ? products.map(productCardHtml).join('')
                : `<div class="sf-empty">No products yet.</div>`;

            bindProductEvents(container);
        } catch (err) {
            container.innerHTML = `<div class="sf-empty">Could not load products.</div>`;
        }
    }

    // Pulled from the real admin-managed Flash Sale (name + end time +
    // hand-picked products) rather than guessing from sale_price. Hides the
    // whole section when there's nothing currently running.
    async function loadFlashSale(ownerFilter) {
        const section = document.getElementById('flash-sale');
        const grid = document.getElementById('flashSaleGrid');

        try {
            const query = new URLSearchParams(ownerFilter);
            const response = await fetch('/api/flash-sales/active?' + query.toString());
            const payload = await response.json();
            const sale = payload.data;
            const products = sale?.products ?? [];

            if (!sale || !products.length) {
                section.classList.add('d-none');
                return;
            }

            section.classList.remove('d-none');
            document.getElementById('sfFlashName').textContent = sale.name;

            grid.innerHTML = products.map(p => `<div class="col-sm-6">${productCardHtml(p)}</div>`).join('');
            bindProductEvents(grid);

            startCountdown(new Date(sale.ends_at).getTime());
        } catch (err) {
            section.classList.add('d-none');
        }
    }

    async function loadCategories() {
        const grid = document.getElementById('categoryGrid');
        try {
            const response = await fetch('/api/categories');
            const payload = await response.json();
            const categories = payload.data?.data ?? [];

            grid.innerHTML = categories.length
                ? categories.map(categoryCardHtml).join('')
                : `<div class="sf-empty"><i class="fa-solid fa-tags"></i> No categories yet.</div>`;

            grid.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
        } catch (err) {
            grid.innerHTML = `<div class="sf-empty"><i class="fa-solid fa-triangle-exclamation"></i> Could not load categories.</div>`;
        }
    }

    async function loadBrands() {
        const grid = document.getElementById('brandGrid');
        try {
            const response = await fetch('/api/brands');
            const payload = await response.json();
            const brands = payload.data?.data ?? [];

            grid.innerHTML = brands.length
                ? brands.map(brandCardHtml).join('')
                : `<div class="sf-empty">No brands listed yet.</div>`;

            grid.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
        } catch (err) {
            grid.innerHTML = '';
        }
    }

    async function loadOffers(ownerFilter) {
        const grid = document.getElementById('offersGrid');
        try {
            const query = new URLSearchParams({ limit: 3, ...ownerFilter });
            const response = await fetch('/api/offers/active?' + query.toString());
            const payload = await response.json();
            const offers = payload.data ?? [];

            grid.innerHTML = offers.length
                ? offers.map(offerCardHtml).join('')
                : `<div class="sf-empty">No active offers right now — check back soon!</div>`;

            grid.querySelectorAll('.reveal').forEach(el => revealObserver.observe(el));
        } catch (err) {
            grid.innerHTML = '';
        }
    }

    function bindProductEvents(container) {
        container.querySelectorAll('[data-add-cart]').forEach(btn => {
            btn.addEventListener('click', () => {
                const product = JSON.parse(btn.closest('[data-product]').dataset.product.replace(/&apos;/g, "'"));
                product.max_stock = stockInfo(product).stock;
                SF.addToCart(product);
            });
        });
        container.querySelectorAll('[data-buy-now]').forEach(btn => {
            btn.addEventListener('click', () => {
                const product = JSON.parse(btn.closest('[data-product]').dataset.product.replace(/&apos;/g, "'"));
                product.max_stock = stockInfo(product).stock;
                SF.addToCart(product);
                new bootstrap.Offcanvas(document.getElementById('sfCartDrawer')).show();
            });
        });
        container.querySelectorAll('[data-wishlist-btn]').forEach(btn => {
            btn.addEventListener('click', () => {
                const product = JSON.parse(btn.closest('[data-product]').dataset.product.replace(/&apos;/g, "'"));
                SF.toggleWishlist(product);
            });
        });
        container.querySelectorAll('[data-compare]').forEach(btn => {
            btn.addEventListener('click', () => SF.toast('Compare is coming soon.', 'warning'));
        });
        container.querySelectorAll('[data-quickview]').forEach(btn => {
            btn.addEventListener('click', () => {
                const product = JSON.parse(btn.closest('[data-product]').dataset.product.replace(/&apos;/g, "'"));
                openQuickView(product);
            });
        });
    }

    function openQuickView(product) {
        const regular = parseFloat(product.regular_price ?? 0);
        const sale = product.sale_price !== null && product.sale_price !== undefined ? parseFloat(product.sale_price) : null;
        const onSale = sale !== null && sale < regular;
        const stock = stockInfo(product).stock;
        const outOfStock = stock === 0;

        document.getElementById('sfQuickViewBody').innerHTML = `
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="sf-product-media" style="border-radius:.75rem;">
                        ${product.thumbnail_url ? `<img src="${product.thumbnail_url}" alt="">` : `<div class="sf-no-image"><i class="fa-solid fa-seedling"></i></div>`}
                    </div>
                </div>
                <div class="col-md-6">
                    ${product.category ? `<div class="sf-product-category">${SF.escapeHtml(product.category.name)}</div>` : ''}
                    <h4 class="fw-bold mt-1">${SF.escapeHtml(product.name)}</h4>
                    <div class="sf-rating mb-2"><span class="stars">${starRow(product.average_rating)}</span><span>(${product.total_reviews ?? 0} reviews)</span></div>
                    <div class="sf-price-row mb-2">
                        <span class="sf-price-now fs-4">${SF.money(onSale ? sale : regular)}</span>
                        ${onSale ? `<span class="sf-price-was">${SF.money(regular)}</span>` : ''}
                    </div>
                    ${stock !== null ? `<div class="mb-2">${outOfStock ? '<span class="sf-stock out">Out of Stock</span>' : stock <= 10 ? `<span class="sf-stock low">Only ${stock} left — hurry!</span>` : '<span class="sf-stock in">In Stock</span>'}</div>` : ''}
                    <p class="text-muted" style="font-size:.9rem;">${SF.escapeHtml(product.short_description ?? product.description ?? 'No description available.')}</p>
                    <div class="d-flex align-items-center gap-3 mt-3">
                        <div class="sf-qty-control" id="qvQty">
                            <button type="button" data-qv-down>−</button><span>1</span><button type="button" data-qv-up>+</button>
                        </div>
                        <button type="button" class="sf-btn sf-btn-primary flex-grow-1" id="qvAddCart" ${outOfStock ? 'disabled' : ''}><i class="fa-solid fa-cart-plus"></i> ${outOfStock ? 'Out of Stock' : 'Add to Cart'}</button>
                    </div>
                </div>
            </div>
        `;

        const qtyEl = document.querySelector('#qvQty span');
        document.querySelector('[data-qv-up]').onclick = () => {
            const current = parseInt(qtyEl.textContent);
            if (stock !== null && current >= stock) {
                SF.toast(`Only ${stock} available.`, 'warning');
                return;
            }
            qtyEl.textContent = current + 1;
        };
        document.querySelector('[data-qv-down]').onclick = () => qtyEl.textContent = Math.max(1, parseInt(qtyEl.textContent) - 1);
        document.getElementById('qvAddCart').onclick = () => {
            product.max_stock = stock;
            SF.addToCart(product, parseInt(qtyEl.textContent));
        };

        // getOrCreateInstance (not `new Modal(...)`) reuses the same instance
        // across repeated opens, so the close button never ends up dismissing
        // a stale instance.
        bootstrap.Modal.getOrCreateInstance(document.getElementById('sfQuickViewModal')).show();
    }

    // ─── Best sellers horizontal scroll controls ─────────────────────────
    document.getElementById('bsNext')?.addEventListener('click', () => {
        document.getElementById('bestSellerScroller').scrollBy({ left: 280, behavior: 'smooth' });
    });
    document.getElementById('bsPrev')?.addEventListener('click', () => {
        document.getElementById('bestSellerScroller').scrollBy({ left: -280, behavior: 'smooth' });
    });

    // ─── Flash sale countdown ─────────────────────────────────────────────
    // Started by loadFlashSale() once it knows the real sale's ends_at —
    // there's nothing to count down to until an active sale is loaded.
    let flashCountdownTimer = null;

    function startCountdown(target) {
        clearInterval(flashCountdownTimer);

        const secondsEl = document.getElementById('fdS');
        const secondsBox = document.getElementById('fdSBox');

        function tick() {
            const diff = target - Date.now();
            if (diff <= 0) { clearInterval(flashCountdownTimer); document.getElementById('flash-sale')?.classList.add('d-none'); return; }

            const d = Math.floor(diff / 86400000);
            const h = Math.floor((diff % 86400000) / 3600000);
            const m = Math.floor((diff % 3600000) / 60000);
            const s = Math.floor((diff % 60000) / 1000);

            document.getElementById('fdD').textContent = String(d).padStart(2, '0');
            document.getElementById('fdH').textContent = String(h).padStart(2, '0');
            document.getElementById('fdM').textContent = String(m).padStart(2, '0');
            secondsEl.textContent = String(s).padStart(2, '0');

            // Re-trigger the tick animation each second (removing+reflowing
            // the class is what lets a CSS animation replay on an unchanged element).
            secondsEl.classList.remove('is-ticking');
            void secondsEl.offsetWidth;
            secondsEl.classList.add('is-ticking');

            secondsBox?.classList.toggle('is-urgent', diff <= 60 * 60 * 1000);
        }
        tick();
        flashCountdownTimer = setInterval(tick, 1000);
    }

    // ─── Trust marquee ────────────────────────────────────────────────────
    (function () {
        const track = document.getElementById('sfMarqueeTrack');
        if (!track) return;

        const items = [
            ['fa-solid fa-leaf', '100% Natural Moringa'],
            ['fa-solid fa-flask-vial', 'Lab Tested & Certified'],
            ['fa-solid fa-paw', 'Cruelty-Free'],
            ['fa-solid fa-truck-fast', 'Free Shipping Over ₹999'],
            ['fa-solid fa-money-bill-wave', 'Cash on Delivery Available'],
            ['fa-solid fa-lock', 'Secure Checkout'],
            ['fa-solid fa-rotate-left', '7-Day Easy Returns'],
        ];

        const itemsHtml = items.map(([icon, text]) => `
            <span class="sf-marquee-item"><i class="${icon}"></i> ${text}</span>
        `).join('');

        // Duplicated once so the CSS animation (translateX -50%) loops seamlessly
        track.innerHTML = itemsHtml + itemsHtml;
    })();

    // ─── Animated stat counters ───────────────────────────────────────────
    (function () {
        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        function animateCount(el) {
            const target = parseFloat(el.dataset.count ?? '0');
            const decimals = parseInt(el.dataset.decimals ?? '0', 10);
            const suffix = el.dataset.suffix ?? '';

            if (reduceMotion) {
                el.textContent = target.toLocaleString(undefined, { maximumFractionDigits: decimals }) + suffix;
                return;
            }

            const duration = 1400;
            const start = performance.now();

            function tick(now) {
                const progress = Math.min((now - start) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3); // ease-out cubic
                const value = target * eased;
                el.textContent = value.toLocaleString(undefined, { minimumFractionDigits: decimals, maximumFractionDigits: decimals }) + suffix;
                if (progress < 1) requestAnimationFrame(tick);
            }

            requestAnimationFrame(tick);
        }

        const statObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (!entry.isIntersecting) return;
                animateCount(entry.target);
                statObserver.unobserve(entry.target);
            });
        }, { threshold: 0.4 });

        document.querySelectorAll('.sf-stat-num').forEach(el => statObserver.observe(el));
    })();

    // ─── Hero shapes: subtle cursor parallax ──────────────────────────────
    (function () {
        const hero = document.querySelector('.sf-hero');
        if (!hero || window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        hero.addEventListener('mousemove', (e) => {
            const rect = hero.getBoundingClientRect();
            const x = (e.clientX - rect.left) / rect.width - 0.5;
            const y = (e.clientY - rect.top) / rect.height - 0.5;

            hero.querySelectorAll('.sf-hero-shape').forEach((shape, i) => {
                const strength = 18 + i * 10;
                shape.style.setProperty('--mx', (x * strength) + 'px');
                shape.style.setProperty('--my', (y * strength) + 'px');
            });
        });

        hero.addEventListener('mouseleave', () => {
            hero.querySelectorAll('.sf-hero-shape').forEach(shape => {
                shape.style.setProperty('--mx', '0px');
                shape.style.setProperty('--my', '0px');
            });
        });
    })();

    // ─── Newsletter (no backend endpoint yet — client-side only) ─────────
    document.getElementById('sfNewsletterForm')?.addEventListener('submit', (e) => {
        e.preventDefault();
        SF.toast(`Thanks! We'll send updates to ${document.getElementById('sfNewsletterEmail').value}.`);
        e.target.reset();
    });

    const revealObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) { entry.target.classList.add('is-visible'); revealObserver.unobserve(entry.target); }
        });
    }, { threshold: 0.12 });

    let dataLoaded = false;

    document.addEventListener('sf:settings-loaded', (e) => {
        const tagline = document.getElementById('sfHeroTagline');
        if (tagline && e.detail?.tagline) tagline.textContent = e.detail.tagline;

        if (dataLoaded) return;
        dataLoaded = true;

        const ownerFilter = e.detail?.admin_id ? { owner_id: e.detail.admin_id } : {};

        loadProducts('featuredGrid', { is_featured: 1, ...ownerFilter }, 'No featured products yet — check back soon.');
        loadProducts('newArrivalsGrid', { is_new: 1, ...ownerFilter }, 'No new arrivals yet — check back soon.');
        loadScrollerProducts('bestSellerScroller', { is_best_seller: 1, ...ownerFilter });
        loadFlashSale(ownerFilter);
        loadOffers(ownerFilter);
    });

    setTimeout(() => document.dispatchEvent(new CustomEvent('sf:settings-loaded', { detail: {} })), 3000);

    loadCategories();
    loadBrands();
})();
</script>
@endpush
