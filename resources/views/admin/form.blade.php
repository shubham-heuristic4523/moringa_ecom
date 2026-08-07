@extends('layouts.app')

@section('title', $productId ? 'Edit Product' : 'Add Product')
@section('page-title', $productId ? 'Edit Product' : 'Add Product')
@section('page-subtitle', $productId ? 'Update this product’s details' : 'Create a new product in your catalog')

@section('content')

<form id="productForm" novalidate>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Basic Information</p>
                <p class="card-subtitle">Core product details</p>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group-half">
                <label class="form-label" for="name">Product Name<span class="form-required">*</span></label>
                <input type="text" id="name" name="name" class="form-input" required>
                <p class="form-error" data-error-for="name"></p>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="slug">Slug</label>
                <input type="text" id="slug" name="slug" class="form-input" placeholder="Auto-generated from name">
                <p class="form-help">Leave blank to auto-generate from the name.</p>
                <p class="form-error" data-error-for="slug"></p>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="sku">SKU</label>
                <input type="text" id="sku" name="sku" class="form-input">
                <p class="form-error" data-error-for="sku"></p>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="status">Status<span class="form-required">*</span></label>
                <select id="status" name="status" class="form-select" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="category_id">Category</label>
                <select id="category_id" name="category_id" class="form-select">
                    <option value="">Select category</option>
                </select>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="brand_id">Brand</label>
                <select id="brand_id" name="brand_id" class="form-select">
                    <option value="">Select brand</option>
                </select>
            </div>
            <div class="form-group-full">
                <label class="form-label" for="short_description">Short Description</label>
                <textarea id="short_description" name="short_description" class="form-textarea" rows="2"></textarea>
            </div>
            <div class="form-group-full">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" class="form-textarea" rows="5"></textarea>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Pricing</p>
                <p class="card-subtitle">Base price shown when no variant is selected</p>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group-half">
                <label class="form-label" for="regular_price">Regular Price<span class="form-required">*</span></label>
                <input type="number" step="0.01" min="0" id="regular_price" name="regular_price" class="form-input" required>
                <p class="form-error" data-error-for="regular_price"></p>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="sale_price">Sale Price</label>
                <input type="number" step="0.01" min="0" id="sale_price" name="sale_price" class="form-input">
                <p class="form-help">Must be less than or equal to the regular price.</p>
                <p class="form-error" data-error-for="sale_price"></p>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Media</p>
                <p class="card-subtitle">Thumbnail and gallery images</p>
            </div>
        </div>

        <div class="form-group-full" style="margin-bottom:1.5rem;">
            <label class="form-label">Thumbnail</label>
            <div class="thumb-upload">
                <div class="thumb-preview" id="thumbnailPreview"><i class="fa-solid fa-image"></i></div>
                <div style="flex:1;">
                    <input type="file" id="thumbnailInput" name="thumbnail" accept="image/*" class="form-file">
                    <p class="form-help">Main image shown in the product list. PNG/JPG up to 2MB.</p>
                    <p class="form-error" data-error-for="thumbnail"></p>
                </div>
            </div>
        </div>

        <div class="form-group-full">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:0.75rem;">
                <label class="form-label" style="margin-bottom:0;">Gallery Images</label>
                <button type="button" id="addImageRow" class="btn btn-ghost"><i class="fa-solid fa-plus"></i> Add Image</button>
            </div>
            <div class="gallery-grid" id="galleryContainer"></div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Variants</p>
                <p class="card-subtitle">Unit sizes with their own price and stock</p>
            </div>
            <button type="button" id="addVariantRow" class="btn btn-ghost"><i class="fa-solid fa-plus"></i> Add Variant</button>
        </div>
        <div class="repeater-list" id="variantsContainer"></div>
        <p class="form-help" id="variantsEmptyHint">No variants yet — add one, or leave empty to sell at the base price only.</p>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Benefits</p>
                <p class="card-subtitle">Key selling points shown on the product page</p>
            </div>
            <button type="button" id="addBenefitRow" class="btn btn-ghost"><i class="fa-solid fa-plus"></i> Add Benefit</button>
        </div>
        <div class="repeater-list" id="benefitsContainer"></div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">FAQs</p>
                <p class="card-subtitle">Common questions about this product</p>
            </div>
            <button type="button" id="addFaqRow" class="btn btn-ghost"><i class="fa-solid fa-plus"></i> Add FAQ</button>
        </div>
        <div class="repeater-list" id="faqsContainer"></div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header"><div><p class="card-title">Additional Details</p></div></div>
        <div class="form-grid">
            <div class="form-group-full">
                <label class="form-label" for="ingredients">Ingredients</label>
                <textarea id="ingredients" name="ingredients" class="form-textarea" rows="3"></textarea>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="who_can_use">Who Can Use</label>
                <textarea id="who_can_use" name="who_can_use" class="form-textarea" rows="3"></textarea>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="how_to_use">How To Use</label>
                <textarea id="how_to_use" name="how_to_use" class="form-textarea" rows="3"></textarea>
            </div>
            <div class="form-group-full">
                <label class="form-label" for="product_features">Product Features</label>
                <textarea id="product_features" name="product_features" class="form-textarea" rows="3"></textarea>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header"><div><p class="card-title">Visibility</p></div></div>
        <div class="form-checkbox-row" style="margin-bottom:0.85rem;">
            <input type="checkbox" id="is_featured" name="is_featured" value="1" class="form-checkbox">
            <label for="is_featured">Featured product</label>
        </div>
        <div class="form-checkbox-row" style="margin-bottom:0.85rem;">
            <input type="checkbox" id="is_best_seller" name="is_best_seller" value="1" class="form-checkbox">
            <label for="is_best_seller">Best seller</label>
        </div>
        <div class="form-checkbox-row">
            <input type="checkbox" id="is_new" name="is_new" value="1" class="form-checkbox">
            <label for="is_new">New arrival</label>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header"><div><p class="card-title">SEO</p></div></div>
        <div class="form-grid">
            <div class="form-group-full">
                <label class="form-label" for="meta_title">Meta Title</label>
                <input type="text" id="meta_title" name="meta_title" class="form-input">
            </div>
            <div class="form-group-full">
                <label class="form-label" for="meta_keywords">Meta Keywords</label>
                <input type="text" id="meta_keywords" name="meta_keywords" class="form-input">
            </div>
            <div class="form-group-full">
                <label class="form-label" for="meta_description">Meta Description</label>
                <textarea id="meta_description" name="meta_description" class="form-textarea" rows="2"></textarea>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <a href="{{ route('admin.list') }}" class="btn btn-ghost">Cancel</a>
        <button type="submit" id="submitBtn" class="btn btn-primary">
            <span id="submitBtnText">{{ $productId ? 'Update Product' : 'Save Product' }}</span>
        </button>
    </div>

</form>

@endsection

@push('styles')
<style>
    .thumb-upload { display: flex; gap: 1rem; align-items: center; }
    .thumb-preview {
        width: 5rem; height: 5rem; border-radius: 0.75rem; overflow: hidden; flex-shrink: 0;
        display: flex; align-items: center; justify-content: center;
        background: color-mix(in srgb, var(--color-moringa-200) 40%, transparent);
        color: var(--color-moringa-600); font-size: 1.25rem;
    }
    .thumb-preview img { width: 100%; height: 100%; object-fit: cover; }

    .gallery-grid { display: flex; flex-wrap: wrap; gap: 1rem; }
    .gallery-card {
        width: 9rem; padding: 0.75rem; border-radius: 0.75rem; position: relative;
        border: 1px solid color-mix(in srgb, var(--color-forest-900) 10%, transparent);
        display: flex; flex-direction: column; gap: 0.5rem; align-items: center;
    }
    body[data-theme="dark"] .gallery-card { border-color: color-mix(in srgb, var(--color-moringa-200) 12%, transparent); }
    .gallery-preview {
        width: 100%; aspect-ratio: 1; border-radius: 0.6rem; overflow: hidden;
        display: flex; align-items: center; justify-content: center;
        background: color-mix(in srgb, var(--color-moringa-200) 35%, transparent);
        color: var(--color-moringa-600);
    }
    .gallery-preview img { width: 100%; height: 100%; object-fit: cover; }
    .gallery-card .form-file { font-size: 0.7rem; width: 100%; padding: 0.35rem; }
    .gallery-primary { display: flex; align-items: center; gap: 0.35rem; font-size: 0.72rem; color: var(--color-forest-700); }
    body[data-theme="dark"] .gallery-primary { color: var(--color-moringa-200); }
    .gallery-card .remove-row-btn { position: absolute; top: 0.35rem; right: 0.35rem; width: 1.6rem; height: 1.6rem; }

    .repeater-row {
        display: flex; align-items: flex-start; gap: 0.75rem;
        padding: 1rem 0; border-top: 1px solid color-mix(in srgb, var(--color-forest-900) 8%, transparent);
    }
    body[data-theme="dark"] .repeater-row { border-top-color: color-mix(in srgb, var(--color-moringa-200) 10%, transparent); }
    .repeater-row:first-child { border-top: none; padding-top: 0; }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const productId = @json($productId);
    const form = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');

    let variantIndex = 0;
    let faqIndex = 0;

    function authHeaders() {
        const token = localStorage.getItem('token');
        return token ? { 'Authorization': 'Bearer ' + token } : {};
    }

    function escapeHtml(value) {
        const div = document.createElement('div');
        div.textContent = value ?? '';
        return div.innerHTML;
    }

    function escapeAttr(value) {
        return escapeHtml(value).replace(/"/g, '&quot;');
    }

    function clearErrors() {
        form.querySelectorAll('.form-error').forEach(el => el.textContent = '');
    }

    function showErrors(errors) {
        let unmatchedShown = false;

        Object.entries(errors).forEach(([field, messages]) => {
            const el = form.querySelector(`[data-error-for="${field}"]`);
            if (el) {
                el.textContent = messages[0];
            } else if (!unmatchedShown) {
                alert(messages[0]);
                unmatchedShown = true;
            }
        });
    }

    // ─── Category / Brand selects ───────────────────────────────────────────
    async function loadOptions(url, select) {
        try {
            const response = await fetch(url, { headers: authHeaders() });
            const payload = await response.json();

            (payload.data?.data ?? []).forEach(item => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                select.appendChild(option);
            });
        } catch (err) {
            // Non-critical — dropdown just stays empty
        }
    }

    // ─── Thumbnail preview ───────────────────────────────────────────────────
    const thumbnailInput = document.getElementById('thumbnailInput');
    const thumbnailPreview = document.getElementById('thumbnailPreview');

    thumbnailInput.addEventListener('change', () => {
        const file = thumbnailInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => { thumbnailPreview.innerHTML = `<img src="${e.target.result}" alt="">`; };
        reader.readAsDataURL(file);
    });

    // ─── Gallery images repeater ─────────────────────────────────────────────
    const galleryContainer = document.getElementById('galleryContainer');

    function addGalleryRow(existing = null) {
        const row = document.createElement('div');
        row.className = 'gallery-card';

        if (existing) {
            row.innerHTML = `
                <div class="gallery-preview"><img src="${existing.image_url}" alt=""></div>
                <input type="hidden" name="existing_image_ids[]" value="${existing.id}">
                <label class="gallery-primary">
                    <input type="radio" name="primaryImageChoice" data-type="existing" data-id="${existing.id}" ${existing.is_primary ? 'checked' : ''}>
                    Primary
                </label>
                <button type="button" class="btn-icon btn-icon-danger remove-row-btn" title="Remove"><i class="fa-solid fa-xmark"></i></button>
            `;
        } else {
            row.innerHTML = `
                <div class="gallery-preview" data-preview><i class="fa-solid fa-image"></i></div>
                <input type="file" name="images[]" accept="image/*" class="form-file" data-file-input>
                <label class="gallery-primary">
                    <input type="radio" name="primaryImageChoice" data-type="new">
                    Primary
                </label>
                <button type="button" class="btn-icon btn-icon-danger remove-row-btn" title="Remove"><i class="fa-solid fa-xmark"></i></button>
            `;
        }

        galleryContainer.appendChild(row);
        row.querySelector('.remove-row-btn').addEventListener('click', () => row.remove());

        if (!existing) {
            const fileInput = row.querySelector('[data-file-input]');
            const preview = row.querySelector('[data-preview]');

            fileInput.addEventListener('change', () => {
                const file = fileInput.files[0];
                if (!file) { preview.innerHTML = '<i class="fa-solid fa-image"></i>'; return; }

                const reader = new FileReader();
                reader.onload = e => { preview.innerHTML = `<img src="${e.target.result}" alt="">`; };
                reader.readAsDataURL(file);
            });
        }
    }

    document.getElementById('addImageRow').addEventListener('click', () => addGalleryRow());

    // ─── Variants repeater ───────────────────────────────────────────────────
    const variantsContainer = document.getElementById('variantsContainer');
    const variantsEmptyHint = document.getElementById('variantsEmptyHint');

    function toggleVariantsHint() {
        variantsEmptyHint.style.display = variantsContainer.children.length ? 'none' : 'block';
    }

    function addVariantRow(data = {}) {
        const idx = variantIndex++;
        const row = document.createElement('div');
        row.className = 'repeater-row';
        row.innerHTML = `
            <div class="form-grid" style="flex:1; grid-template-columns: repeat(5, 1fr); gap:0.75rem;">
                <div>
                    <label class="form-label">Unit</label>
                    <input type="text" name="variants[${idx}][unit]" class="form-input" placeholder="e.g. 250g" value="${escapeAttr(data.unit)}">
                </div>
                <div>
                    <label class="form-label">Regular Price</label>
                    <input type="number" step="0.01" min="0" name="variants[${idx}][regular_price]" class="form-input" value="${escapeAttr(data.regular_price)}">
                </div>
                <div>
                    <label class="form-label">Sale Price</label>
                    <input type="number" step="0.01" min="0" name="variants[${idx}][sale_price]" class="form-input" value="${escapeAttr(data.sale_price)}">
                </div>
                <div>
                    <label class="form-label">Stock</label>
                    <input type="number" min="0" name="variants[${idx}][stock]" class="form-input" value="${escapeAttr(data.stock ?? 0)}">
                </div>
                <div>
                    <label class="form-label">SKU</label>
                    <input type="text" name="variants[${idx}][sku]" class="form-input" value="${escapeAttr(data.sku)}">
                </div>
            </div>
            <div class="form-checkbox-row" style="margin: 1.75rem 0.75rem 0;">
                <input type="checkbox" name="variants[${idx}][status]" value="1" class="form-checkbox" ${(data.status ?? true) ? 'checked' : ''}>
                <label>Active</label>
            </div>
            <button type="button" class="btn-icon btn-icon-danger remove-row-btn" style="margin-top:1.75rem;" title="Remove variant"><i class="fa-solid fa-xmark"></i></button>
        `;
        variantsContainer.appendChild(row);
        row.querySelector('.remove-row-btn').addEventListener('click', () => { row.remove(); toggleVariantsHint(); });
        toggleVariantsHint();
    }

    document.getElementById('addVariantRow').addEventListener('click', () => addVariantRow());

    // ─── Benefits repeater ───────────────────────────────────────────────────
    const benefitsContainer = document.getElementById('benefitsContainer');

    function addBenefitRow(value = '') {
        const row = document.createElement('div');
        row.className = 'repeater-row';
        row.innerHTML = `
            <input type="text" name="benefits[]" class="form-input" placeholder="e.g. Rich in antioxidants" value="${escapeAttr(value)}" style="flex:1;">
            <button type="button" class="btn-icon btn-icon-danger remove-row-btn" title="Remove"><i class="fa-solid fa-xmark"></i></button>
        `;
        benefitsContainer.appendChild(row);
        row.querySelector('.remove-row-btn').addEventListener('click', () => row.remove());
    }

    document.getElementById('addBenefitRow').addEventListener('click', () => addBenefitRow());

    // ─── FAQs repeater ───────────────────────────────────────────────────────
    const faqsContainer = document.getElementById('faqsContainer');

    function addFaqRow(data = {}) {
        const idx = faqIndex++;
        const row = document.createElement('div');
        row.className = 'repeater-row';
        row.innerHTML = `
            <div style="flex:1; display:flex; flex-direction:column; gap:0.5rem;">
                <input type="text" name="faqs[${idx}][question]" class="form-input" placeholder="Question" value="${escapeAttr(data.question)}">
                <textarea name="faqs[${idx}][answer]" class="form-textarea" placeholder="Answer" rows="2">${escapeHtml(data.answer)}</textarea>
            </div>
            <button type="button" class="btn-icon btn-icon-danger remove-row-btn" title="Remove"><i class="fa-solid fa-xmark"></i></button>
        `;
        faqsContainer.appendChild(row);
        row.querySelector('.remove-row-btn').addEventListener('click', () => row.remove());
    }

    document.getElementById('addFaqRow').addEventListener('click', () => addFaqRow());

    // ─── Prefill for edit mode ───────────────────────────────────────────────
    async function loadProduct() {
        if (!productId) return;

        submitBtn.disabled = true;
        submitBtnText.textContent = 'Loading…';

        try {
            const response = await fetch('/api/products/' + productId, { headers: authHeaders() });
            const payload = await response.json();

            if (!payload.status) {
                alert(payload.message || 'Product not found.');
                window.location = '{{ route('admin.list') }}';
                return;
            }

            const product = payload.data;

            [
                'name', 'slug', 'sku', 'category_id', 'brand_id', 'status',
                'short_description', 'description', 'regular_price', 'sale_price',
                'ingredients', 'who_can_use', 'how_to_use', 'product_features',
                'meta_title', 'meta_keywords', 'meta_description',
            ].forEach(field => {
                const el = form.elements[field];
                if (el) el.value = product[field] ?? '';
            });

            form.elements['is_featured'].checked = !!product.is_featured;
            form.elements['is_best_seller'].checked = !!product.is_best_seller;
            form.elements['is_new'].checked = !!product.is_new;

            if (product.thumbnail_url) {
                thumbnailPreview.innerHTML = `<img src="${product.thumbnail_url}" alt="">`;
            }

            (product.images ?? []).forEach(image => addGalleryRow(image));
            (product.variants ?? []).forEach(variant => addVariantRow(variant));
            (product.benefits ?? []).forEach(benefit => addBenefitRow(benefit.benefit));
            (product.faqs ?? []).forEach(faq => addFaqRow(faq));
        } catch (err) {
            alert('Could not load product.');
        } finally {
            submitBtn.disabled = false;
            submitBtnText.textContent = productId ? 'Update Product' : 'Save Product';
        }
    }

    async function init() {
        await Promise.all([
            loadOptions('/api/categories', document.getElementById('category_id')),
            loadOptions('/api/brands', document.getElementById('brand_id')),
        ]);
        await loadProduct();
    }

    init();

    // ─── Submit ──────────────────────────────────────────────────────────────
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        if (submitBtn.disabled) return;

        clearErrors();

        // Disable empty gallery file inputs so they aren't sent as blank entries
        const emptyFileInputs = Array.from(galleryContainer.querySelectorAll('[data-file-input]'))
            .filter(input => !input.files.length);
        emptyFileInputs.forEach(input => input.disabled = true);

        // Resolve which image (existing or newly-added) should become primary
        const hiddenPrimaryFields = [];
        const checkedRadio = form.querySelector('[name="primaryImageChoice"]:checked');

        if (checkedRadio) {
            const hidden = document.createElement('input');
            hidden.type = 'hidden';

            if (checkedRadio.dataset.type === 'existing') {
                hidden.name = 'primary_image_id';
                hidden.value = checkedRadio.dataset.id;
            } else {
                const enabledFileInputs = Array.from(galleryContainer.querySelectorAll('[data-file-input]'))
                    .filter(input => !input.disabled);
                const fileInput = checkedRadio.closest('.gallery-card').querySelector('[data-file-input]');

                hidden.name = 'primary_image_index';
                hidden.value = enabledFileInputs.indexOf(fileInput);
            }

            form.appendChild(hidden);
            hiddenPrimaryFields.push(hidden);
        }

        submitBtn.disabled = true;
        submitBtnText.textContent = productId ? 'Updating…' : 'Saving…';

        const formData = new FormData(form);
        if (productId) formData.append('_method', 'PUT');

        try {
            const url = productId ? '/api/products/' + productId : '/api/products';
            const response = await fetch(url, {
                method: 'POST',
                headers: { 'Accept': 'application/json', ...authHeaders() },
                body: formData,
            });

            const data = await response.json();

            if (response.status === 422) {
                showErrors(data.errors || {});
                return;
            }

            if (!data.status) {
                alert(data.message || 'Could not save product.');
                return;
            }

            window.location = '{{ route('admin.list') }}';
        } catch (err) {
            alert('Something went wrong. Please try again.');
        } finally {
            submitBtn.disabled = false;
            submitBtnText.textContent = productId ? 'Update Product' : 'Save Product';
            emptyFileInputs.forEach(input => input.disabled = false);
            hiddenPrimaryFields.forEach(el => el.remove());
        }
    });
})();
</script>
@endpush
