@extends('layouts.storefront')

@section('title', 'Product')

@section('content')

<section class="sf-section" style="padding-top:2rem;">
    <div class="container">

        <nav class="sf-breadcrumb mb-4">
            <a href="{{ route('home') }}">Home</a>
            <span>/</span>
            <span id="sfPdBreadcrumbCategory"></span>
            <span>/</span>
            <span id="sfPdBreadcrumbName"></span>
        </nav>

        <div id="sfProductLoading" class="sf-empty">
            <i class="fa-solid fa-spinner fa-spin"></i> Loading product…
        </div>

        <div id="sfProductNotFound" class="sf-empty d-none">
            <i class="fa-solid fa-triangle-exclamation"></i> This product could not be found.
            <div class="mt-3"><a href="{{ route('home') }}" class="sf-btn sf-btn-primary">Back to Shop</a></div>
        </div>

        <div id="sfProductContent" class="d-none">

            <div class="row g-5">
                <div class="col-lg-6">
                    <div class="sf-pd-gallery reveal">
                        <div class="sf-pd-main-image" id="sfPdMainImage"></div>
                        <div class="sf-pd-thumbs" id="sfPdThumbs"></div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="reveal reveal-right">
                        <div class="sf-product-category" id="sfPdCategory"></div>
                        <h1 class="sf-pd-title" id="sfPdName"></h1>

                        <div class="sf-rating mb-2" id="sfPdRating"></div>

                        <div class="sf-price-row mb-3" id="sfPdPrice" style="font-size:1.35rem;"></div>

                        <p class="sf-pd-short" id="sfPdShort"></p>

                        <div class="sf-pd-variants mb-3 d-none" id="sfPdVariants"></div>

                        <div class="d-flex align-items-center gap-3 mt-3 flex-wrap">
                            <div class="sf-qty-control" id="sfPdQty">
                                <button type="button" data-pd-qty-down>−</button><span>1</span><button type="button" data-pd-qty-up>+</button>
                            </div>
                            <button type="button" class="sf-btn sf-btn-outline" id="sfPdAddCart"><i class="fa-solid fa-cart-plus"></i> Add to Cart</button>
                            <button type="button" class="sf-btn sf-btn-primary" id="sfPdBuyNow">Buy Now</button>
                            <button type="button" class="sf-icon-btn" id="sfPdWishlistBtn" title="Add to wishlist"><i class="fa-solid fa-heart"></i></button>
                        </div>

                        <div class="sf-pd-meta mt-4" id="sfPdMeta"></div>
                    </div>
                </div>
            </div>

            <div class="sf-pd-tabs reveal mt-5" id="sfPdTabs"></div>

            <div class="sf-section-head reveal mt-5 d-none" id="sfPdRelatedHead">
                <p class="sf-eyebrow">You May Also Like</p>
                <h2>Related Products</h2>
            </div>
            <div class="row g-4" id="sfPdRelated"></div>

        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
(function () {
    const productId = @json($productId);

    const STAR_FULL = '<i class="fa-solid fa-star"></i>';
    const STAR_EMPTY = '<i class="fa-regular fa-star"></i>';

    function starRow(rating) {
        const r = Math.round(parseFloat(rating ?? 0));
        return Array.from({ length: 5 }, (_, i) => i < r ? STAR_FULL : STAR_EMPTY).join('');
    }

    let product = null;
    let qtyEl = null;
    let selectedVariant = null;

    function activePrices() {
        const regular = parseFloat(selectedVariant ? selectedVariant.regular_price : product.regular_price ?? 0);
        const saleRaw = selectedVariant ? selectedVariant.sale_price : product.sale_price;
        const sale = saleRaw !== null && saleRaw !== undefined ? parseFloat(saleRaw) : null;
        const onSale = sale !== null && sale < regular;
        return { regular, sale, onSale };
    }

    function activeStock() {
        if (selectedVariant) return selectedVariant.stock ?? 0;
        const variants = product.variants ?? [];
        if (!variants.length) return null; // no per-unit stock tracked — treat as always orderable
        return variants.reduce((sum, v) => sum + (v.stock ?? 0), 0);
    }

    // Keeps the quantity stepper from sitting above what's actually
    // available — called on load and whenever the selected variant changes.
    function clampQtyToStock() {
        if (!qtyEl) return;
        const stock = activeStock();
        if (stock !== null && parseInt(qtyEl.textContent) > stock) {
            qtyEl.textContent = Math.max(1, stock);
        }
    }

    function renderPrice() {
        const { regular, sale, onSale } = activePrices();
        const off = onSale ? Math.round((1 - sale / regular) * 100) : 0;

        document.getElementById('sfPdPrice').innerHTML = `
            <span class="sf-price-now">${SF.money(onSale ? sale : regular)}</span>
            ${onSale ? `<span class="sf-price-was">${SF.money(regular)}</span><span class="sf-price-off">${off}% OFF</span>` : ''}
        `;

        const stock = activeStock();
        const metaStock = stock === null
            ? '<span class="sf-stock in"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> In Stock</span>'
            : stock === 0
                ? '<span class="sf-stock out"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> Out of Stock</span>'
                : stock <= 10
                    ? `<span class="sf-stock low"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> Only ${stock} left — hurry!</span>`
                    : '<span class="sf-stock in"><i class="fa-solid fa-circle" style="font-size:.5rem;"></i> In Stock</span>';

        document.getElementById('sfPdMeta').innerHTML = `
            ${product.sku ? `<div><strong>SKU:</strong> ${SF.escapeHtml(product.sku)}</div>` : ''}
            ${product.brand ? `<div><strong>Brand:</strong> ${SF.escapeHtml(product.brand.name)}</div>` : ''}
            <div>${metaStock}</div>
        `;

        const addBtn = document.getElementById('sfPdAddCart');
        const buyBtn = document.getElementById('sfPdBuyNow');
        const disabled = stock === 0;
        addBtn.disabled = disabled;
        buyBtn.disabled = disabled;
        addBtn.style.opacity = buyBtn.style.opacity = disabled ? .5 : 1;
    }

    function renderGallery() {
        const galleryImages = (product.images ?? []).map(img => img.image_url).filter(Boolean);
        const gallery = product.thumbnail_url ? [product.thumbnail_url, ...galleryImages] : galleryImages;

        const mainImage = document.getElementById('sfPdMainImage');
        const thumbs = document.getElementById('sfPdThumbs');

        function showImage(src) {
            mainImage.innerHTML = src
                ? `<img src="${src}" alt="${SF.escapeHtml(product.name)}">`
                : `<div class="sf-no-image"><i class="fa-solid fa-seedling"></i></div>`;
        }

        showImage(gallery[0]);

        thumbs.innerHTML = gallery.length > 1
            ? gallery.map((src, i) => `<div class="sf-pd-thumb ${i === 0 ? 'is-active' : ''}" data-thumb-src="${src}"><img src="${src}" alt=""></div>`).join('')
            : '';

        thumbs.querySelectorAll('[data-thumb-src]').forEach(el => {
            el.addEventListener('click', () => {
                showImage(el.dataset.thumbSrc);
                thumbs.querySelectorAll('.sf-pd-thumb').forEach(t => t.classList.remove('is-active'));
                el.classList.add('is-active');
            });
        });
    }

    function renderVariants() {
        const variants = (product.variants ?? []).filter(v => v.status !== false);
        const wrap = document.getElementById('sfPdVariants');

        if (!variants.length) { wrap.classList.add('d-none'); return; }

        wrap.classList.remove('d-none');
        wrap.innerHTML = `
            <p class="sf-eyebrow" style="margin-bottom:.5rem;">Select Option</p>
            <div class="d-flex gap-2 flex-wrap">
                ${variants.map((v, i) => `
                    <button type="button" class="sf-pd-variant-btn ${i === 0 ? 'is-active' : ''}" data-variant-index="${i}" ${v.stock === 0 ? 'disabled title="Out of stock"' : ''}>
                        ${SF.escapeHtml(v.unit)}
                        ${v.stock > 0 && v.stock <= 10 ? `<span style="display:block; font-weight:400; font-size:.7rem; color:#dc2626;">${v.stock} left</span>` : ''}
                    </button>
                `).join('')}
            </div>
        `;

        selectedVariant = variants.find(v => v.stock !== 0) ?? variants[0];

        wrap.querySelectorAll('[data-variant-index]').forEach(btn => {
            btn.addEventListener('click', () => {
                if (btn.disabled) return;
                selectedVariant = variants[parseInt(btn.dataset.variantIndex, 10)];
                wrap.querySelectorAll('.sf-pd-variant-btn').forEach(b => b.classList.remove('is-active'));
                btn.classList.add('is-active');
                renderPrice();
                clampQtyToStock();
            });
        });
    }

    function section(iconClass, title, bodyHtml) {
        return `
            <div class="sf-pd-section">
                <h3><i class="fa-solid ${iconClass}"></i> ${title}</h3>
                ${bodyHtml}
            </div>
        `;
    }

    function renderTabs() {
        const tabs = document.getElementById('sfPdTabs');
        let html = '';

        if (product.description) {
            html += section('fa-align-left', 'Description', `<p>${SF.escapeHtml(product.description)}</p>`);
        }

        const benefits = product.benefits ?? [];
        if (benefits.length) {
            html += section('fa-heart-circle-check', 'Benefits', `
                <ul>${benefits.map(b => `<li>${SF.escapeHtml(b.benefit ?? b)}</li>`).join('')}</ul>
            `);
        }

        if (product.ingredients) {
            html += section('fa-flask', 'Ingredients', `<p>${SF.escapeHtml(product.ingredients)}</p>`);
        }

        if (product.who_can_use) {
            html += section('fa-user-check', 'Who Can Use This', `<p>${SF.escapeHtml(product.who_can_use)}</p>`);
        }

        if (product.how_to_use) {
            html += section('fa-mortar-pestle', 'How To Use', `<p>${SF.escapeHtml(product.how_to_use)}</p>`);
        }

        if (product.product_features) {
            html += section('fa-star', 'Key Features', `<p>${SF.escapeHtml(product.product_features)}</p>`);
        }

        const faqs = product.faqs ?? [];
        if (faqs.length) {
            html += section('fa-circle-question', 'Frequently Asked Questions', `
                <div>
                    ${faqs.map((f, i) => `
                        <div class="sf-pd-faq-item" data-faq-index="${i}">
                            <div class="sf-pd-faq-q">${SF.escapeHtml(f.question)} <i class="fa-solid fa-chevron-down"></i></div>
                            <div class="sf-pd-faq-a">${SF.escapeHtml(f.answer)}</div>
                        </div>
                    `).join('')}
                </div>
            `);
        }

        tabs.innerHTML = html;

        tabs.querySelectorAll('.sf-pd-faq-item').forEach(item => {
            item.querySelector('.sf-pd-faq-q').addEventListener('click', () => item.classList.toggle('is-open'));
        });
    }

    function currentCartProduct() {
        const { regular, sale } = activePrices();
        return {
            id: product.id,
            name: product.name,
            thumbnail_url: product.thumbnail_url,
            regular_price: regular,
            sale_price: sale,
            variant_id: selectedVariant ? selectedVariant.id : null,
            variant_label: selectedVariant ? selectedVariant.unit : null,
            max_stock: activeStock(),
        };
    }

    function renderRelated() {
        if (!product.category) return;

        const grid = document.getElementById('sfPdRelated');
        const head = document.getElementById('sfPdRelatedHead');

        fetch('/api/products?' + new URLSearchParams({ category_id: product.category.id, status: 'active', per_page: 8 }))
            .then(res => res.json())
            .then(payload => {
                const items = (payload.data?.data ?? []).filter(p => p.id !== product.id).slice(0, 4);
                if (!items.length) return;

                head.classList.remove('d-none');
                grid.innerHTML = items.map(p => `
                    <div class="col-6 col-md-3 reveal">
                        <a href="/product/${p.id}" class="sf-product-card" style="display:flex; flex-direction:column;">
                            <div class="sf-product-media">
                                ${p.thumbnail_url ? `<img src="${p.thumbnail_url}" alt="${SF.escapeHtml(p.name)}" loading="lazy">` : `<div class="sf-no-image"><i class="fa-solid fa-seedling"></i></div>`}
                            </div>
                            <div class="sf-product-body">
                                <p class="sf-product-name">${SF.escapeHtml(p.name)}</p>
                                <div class="sf-price-row"><span class="sf-price-now">${SF.money(p.sale_price ?? p.regular_price)}</span></div>
                            </div>
                        </a>
                    </div>
                `).join('');

                grid.querySelectorAll('.reveal').forEach(el => el.classList.add('is-visible'));
            })
            .catch(() => {});
    }

    async function load() {
        try {
            const response = await fetch('/api/products/' + productId);
            const payload = await response.json();

            if (!payload.status || !payload.data) throw new Error('not found');

            product = payload.data;

            document.title = product.name + ' | Moringa';
            document.getElementById('sfPdBreadcrumbCategory').innerHTML = product.category
                ? SF.escapeHtml(product.category.name)
                : 'Shop';
            document.getElementById('sfPdBreadcrumbName').textContent = product.name;
            document.getElementById('sfPdCategory').textContent = product.category?.name ?? '';
            document.getElementById('sfPdName').textContent = product.name;
            document.getElementById('sfPdRating').innerHTML = `
                <span class="stars">${starRow(product.average_rating)}</span>
                <span>(${product.total_reviews ?? 0} reviews)</span>
            `;
            document.getElementById('sfPdShort').textContent = product.short_description ?? '';

            renderGallery();
            renderVariants();
            renderPrice();
            renderTabs();
            renderRelated();

            document.getElementById('sfProductLoading').classList.add('d-none');
            document.getElementById('sfProductContent').classList.remove('d-none');
            document.querySelectorAll('#sfProductContent .reveal').forEach(el => el.classList.add('is-visible'));

            const wishBtn = document.getElementById('sfPdWishlistBtn');
            wishBtn.setAttribute('data-wishlist-btn', product.id);
            wishBtn.classList.toggle('is-active', SF.isWishlisted(product.id));
            wishBtn.addEventListener('click', () => {
                SF.toggleWishlist({ id: product.id, name: product.name, thumbnail_url: product.thumbnail_url, regular_price: product.regular_price, sale_price: product.sale_price });
            });

            qtyEl = document.querySelector('#sfPdQty span');
            clampQtyToStock();

            document.querySelector('[data-pd-qty-up]').addEventListener('click', () => {
                const stock = activeStock();
                const current = parseInt(qtyEl.textContent);
                if (stock !== null && current >= stock) {
                    SF.toast(`Only ${stock} available.`, 'warning');
                    return;
                }
                qtyEl.textContent = current + 1;
            });
            document.querySelector('[data-pd-qty-down]').addEventListener('click', () => { qtyEl.textContent = Math.max(1, parseInt(qtyEl.textContent) - 1); });

            document.getElementById('sfPdAddCart').addEventListener('click', () => {
                SF.addToCart(currentCartProduct(), parseInt(qtyEl.textContent));
            });
            document.getElementById('sfPdBuyNow').addEventListener('click', () => {
                SF.addToCart(currentCartProduct(), parseInt(qtyEl.textContent));
                window.location.href = '/checkout';
            });
        } catch (err) {
            document.getElementById('sfProductLoading').classList.add('d-none');
            document.getElementById('sfProductNotFound').classList.remove('d-none');
        }
    }

    load();
})();
</script>
@endpush
