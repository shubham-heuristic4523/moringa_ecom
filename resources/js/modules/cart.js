/**
 * LUXE — Cart Module
 * GET /api/cart, POST /api/cart, PATCH /api/cart/{id}, DELETE /api/cart/{id}
 * POST /api/cart/coupon, DELETE /api/cart/coupon
 */

import { api, isAuthenticated, showToast, setButtonLoading, resetButton } from '../api/client.js';

document.addEventListener('DOMContentLoaded', async () => {

    const isCartPage = document.getElementById('cart-page-root');
    if (!isCartPage) return;

    if (!isAuthenticated()) {
        window.location.href = '/login';
        return;
    }

    // ─── Load Cart ───────────────────────────────────────────────────────────
    await loadCart();

    // ─── Coupon Form ─────────────────────────────────────────────────────────
    const couponForm = document.getElementById('coupon-form');
    if (couponForm) {
        couponForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = couponForm.querySelector('button[type="submit"]');
            const code = couponForm.querySelector('[name="coupon_code"]')?.value?.trim();

            if (!code) { showToast('warning', 'Please enter a coupon code.'); return; }

            setButtonLoading(btn, 'Applying...');
            const res = await api.post('/cart/coupon', { coupon_code: code });
            resetButton(btn);

            if (res.success) {
                showToast('success', `Coupon "${code}" applied!`);
                await loadCart(); // Refresh totals
            } else {
                showToast('error', res.message ?? 'Invalid or expired coupon.');
            }
        });
    }
});

// ─── Load & Render Cart ───────────────────────────────────────────────────────

async function loadCart() {
    const res = await api.get('/cart');
    if (!res.success) return;

    updateCartBadge(res.cart?.items?.length ?? 0);

    const { items, summary } = res.cart ?? {};

    renderCartItems(items ?? []);
    renderOrderSummary(summary ?? {});
}

function renderCartItems(items) {
    const container = document.getElementById('cart-items-container');
    if (!container) return;

    if (items.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8xl">
                <span class="material-symbols-outlined text-[80px] text-on-surface-variant/20">shopping_cart</span>
                <h2 class="font-h3 text-h3 text-on-surface-variant mt-2xl">Your cart is empty</h2>
                <p class="font-body-default text-on-surface-variant mt-md mb-4xl">Looks like you haven't added anything yet.</p>
                <a href="/products" class="inline-flex items-center gap-xs px-6xl py-md bg-primary text-on-primary font-button rounded transition-colors">
                    Start Shopping <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                </a>
            </div>`;
        document.getElementById('cart-checkout-btn')?.classList.add('hidden');
        return;
    }

    document.getElementById('cart-checkout-btn')?.classList.remove('hidden');
    container.innerHTML = items.map(item => buildCartItemHTML(item)).join('');
    bindCartItemEvents(container);
}

function buildCartItemHTML(item) {
    return `
    <div class="flex flex-col sm:flex-row gap-3xl pb-3xl border-b border-outline-variant/30" data-cart-item-id="${item.id}">
        <div class="w-full sm:w-32 h-40 sm:h-32 bg-surface-container rounded-lg overflow-hidden flex-shrink-0 border border-outline-variant/20 shadow-sm">
            <img src="${item.product?.image_url ?? ''}" alt="${item.product?.name ?? ''}" class="w-full h-full object-cover" loading="lazy"/>
        </div>
        <div class="flex flex-col flex-grow justify-between py-xs">
            <div class="flex justify-between items-start gap-md">
                <div>
                    <h3 class="font-h5 text-h5 text-on-surface">${item.product?.name ?? ''}</h3>
                    ${item.variant ? `<p class="font-body-sm text-body-sm text-on-surface-variant mt-xs">${item.variant}</p>` : ''}
                </div>
                <p class="font-h5 text-h5 text-on-surface cart-item-total">$${(item.total_price ?? 0).toFixed(2)}</p>
            </div>
            <div class="flex justify-between items-center mt-3xl sm:mt-auto">
                <div class="flex items-center border border-outline-variant rounded-md overflow-hidden bg-surface h-10 shadow-sm">
                    <button class="qty-decrease px-md hover:bg-surface-container-high transition-colors h-full flex items-center justify-center text-on-surface-variant" aria-label="Decrease">
                        <span class="material-symbols-outlined text-sm">remove</span>
                    </button>
                    <input type="number" value="${item.quantity}" min="1" max="99"
                           class="qty-input font-body-default text-body-default px-md text-center min-w-[2.5rem] border-none focus:ring-0 bg-transparent"
                           aria-label="Quantity"/>
                    <button class="qty-increase px-md hover:bg-surface-container-high transition-colors h-full flex items-center justify-center text-on-surface-variant" aria-label="Increase">
                        <span class="material-symbols-outlined text-sm">add</span>
                    </button>
                </div>
                <div class="flex gap-lg">
                    <button class="move-to-wishlist font-button text-button text-on-surface-variant hover:text-primary transition-colors underline-offset-4 hover:underline">
                        Move to Wishlist
                    </button>
                    <button class="remove-item font-button text-button text-error hover:text-error/80 transition-colors underline-offset-4 hover:underline">
                        Remove
                    </button>
                </div>
            </div>
        </div>
    </div>`;
}

function bindCartItemEvents(container) {
    // Quantity changes (debounced)
    container.querySelectorAll('[data-cart-item-id]').forEach(row => {
        const id = row.dataset.cartItemId;
        const qtyInput = row.querySelector('.qty-input');
        let debounce;

        row.querySelector('.qty-decrease').addEventListener('click', () => {
            if (parseInt(qtyInput.value) > 1) { qtyInput.value = parseInt(qtyInput.value) - 1; updateQty(id, qtyInput.value); }
        });
        row.querySelector('.qty-increase').addEventListener('click', () => {
            qtyInput.value = parseInt(qtyInput.value) + 1; updateQty(id, qtyInput.value);
        });
        qtyInput.addEventListener('change', () => {
            clearTimeout(debounce);
            debounce = setTimeout(() => updateQty(id, qtyInput.value), 600);
        });

        row.querySelector('.remove-item').addEventListener('click', () => removeItem(id, row));
        row.querySelector('.move-to-wishlist').addEventListener('click', () => moveToWishlist(id, row));
    });
}

async function updateQty(cartItemId, quantity) {
    const res = await api.patch(`/cart/${cartItemId}`, { quantity: parseInt(quantity) });
    if (res.success) {
        renderOrderSummary(res.cart?.summary ?? {});
    } else {
        showToast('error', res.message ?? 'Could not update quantity.');
    }
}

async function removeItem(cartItemId, rowEl) {
    rowEl.style.opacity = '0.5';
    const res = await api.delete(`/cart/${cartItemId}`);
    if (res.success) {
        rowEl.remove();
        renderOrderSummary(res.cart?.summary ?? {});
        showToast('success', 'Item removed from cart.');
        updateCartBadge(document.querySelectorAll('[data-cart-item-id]').length);
    } else {
        rowEl.style.opacity = '1';
        showToast('error', 'Could not remove item.');
    }
}

async function moveToWishlist(cartItemId, rowEl) {
    const productId = rowEl.querySelector('[data-product-id]')?.dataset.productId;
    if (productId) await api.post(`/wishlist/${productId}`, {});
    await removeItem(cartItemId, rowEl);
    showToast('success', 'Moved to wishlist.');
}

function renderOrderSummary(summary) {
    const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
    set('summary-subtotal', `$${(summary.subtotal ?? 0).toFixed(2)}`);
    set('summary-shipping', summary.shipping > 0 ? `$${summary.shipping.toFixed(2)}` : 'Free');
    set('summary-tax',      `$${(summary.tax ?? 0).toFixed(2)}`);
    set('summary-discount', summary.discount > 0 ? `-$${summary.discount.toFixed(2)}` : '$0.00');
    set('summary-total',    `$${(summary.total ?? 0).toFixed(2)}`);
}

function updateCartBadge(count) {
    document.querySelectorAll('[data-cart-badge]').forEach(el => {
        el.textContent = count > 9 ? '9+' : count;
        el.style.display = count > 0 ? 'flex' : 'none';
    });
}
