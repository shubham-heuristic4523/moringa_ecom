@extends('layouts.app')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('page-subtitle', 'Site branding shown on the public storefront')

@section('content')

<div class="admin-card" id="permissionNotice" style="display:none; margin-bottom:1.5rem; border-color:#dc2626;">
    <p style="margin:0; font-size:0.9rem;">
        <i class="fa-solid fa-triangle-exclamation" style="color:#dc2626; margin-right:0.4rem;"></i>
        Only admin accounts can change storefront branding.
    </p>
</div>

<div class="admin-card" id="storeUrlCard" style="display:none; margin-bottom:1.5rem;">
    <p class="card-title" style="margin-bottom:0.4rem;">Your Storefront</p>
    <p class="card-subtitle" style="margin-bottom:0.75rem;">This is your own public store — separate from every other admin's.</p>
    <a href="#" id="storeUrlLink" target="_blank" class="btn btn-ghost" style="display:inline-flex;"></a>
</div>

<form id="settingsForm">

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Site Identity</p>
                <p class="card-subtitle">Shown in the storefront header and browser tab</p>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group-half">
                <label class="form-label" for="site_name">Site Name<span class="form-required">*</span></label>
                <input type="text" id="site_name" name="site_name" class="form-input" required>
                <p class="form-error" data-error-for="site_name"></p>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="tagline">Tagline</label>
                <input type="text" id="tagline" name="tagline" class="form-input" placeholder="A short line shown under the site name">
                <p class="form-error" data-error-for="tagline"></p>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Logo</p>
                <p class="card-subtitle">PNG/JPG up to 2MB, shown in the storefront header</p>
            </div>
        </div>
        <div class="thumb-upload">
            <div class="thumb-preview" id="logoPreview"><i class="fa-solid fa-image"></i></div>
            <div style="flex:1;">
                <input type="file" id="logo" name="logo" accept="image/*" class="form-file">
                <p class="form-error" data-error-for="logo"></p>
                <button type="button" id="extractColorsBtn" class="btn btn-ghost" style="margin-top:0.6rem;" disabled>
                    <i class="fa-solid fa-wand-magic-sparkles"></i>
                    <span id="extractColorsBtnText">Extract Theme Colors from Logo</span>
                </button>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Colors</p>
                <p class="card-subtitle">Used across the public storefront theme — pick your own, or extract them from your logo above</p>
            </div>
        </div>
        <div class="color-grid">
            <div class="color-field">
                <label class="form-label" for="primary_color">Primary</label>
                <div class="color-input-row">
                    <input type="color" id="primary_color" name="primary_color" class="color-swatch">
                    <span class="color-hex" id="primary_color_hex"></span>
                </div>
                <p class="form-error" data-error-for="primary_color"></p>
            </div>
            <div class="color-field">
                <label class="form-label" for="secondary_color">Secondary</label>
                <div class="color-input-row">
                    <input type="color" id="secondary_color" name="secondary_color" class="color-swatch">
                    <span class="color-hex" id="secondary_color_hex"></span>
                </div>
                <p class="form-error" data-error-for="secondary_color"></p>
            </div>
            <div class="color-field">
                <label class="form-label" for="accent_color">Accent</label>
                <div class="color-input-row">
                    <input type="color" id="accent_color" name="accent_color" class="color-swatch">
                    <span class="color-hex" id="accent_color_hex"></span>
                </div>
                <p class="form-error" data-error-for="accent_color"></p>
            </div>
        </div>
    </div>

    <div class="admin-card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <div>
                <p class="card-title">Referral Rewards</p>
                <p class="card-subtitle">The coupon automatically given to a customer when someone they referred completes their first order</p>
            </div>
        </div>
        <div class="form-grid">
            <div class="form-group-half">
                <label class="form-label" for="referral_discount_type">Reward Type<span class="form-required">*</span></label>
                <select id="referral_discount_type" name="referral_discount_type" class="form-select">
                    <option value="percentage">Percentage</option>
                    <option value="flat">Flat Amount</option>
                </select>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="referral_discount_value">Reward Value<span class="form-required">*</span></label>
                <input type="number" step="0.01" min="0.01" id="referral_discount_value" name="referral_discount_value" class="form-input" required>
                <p class="form-error" data-error-for="referral_discount_value"></p>
            </div>
            <div class="form-group-half" id="referralMaxDiscountField">
                <label class="form-label" for="referral_max_discount_amount">Max Discount Amount</label>
                <input type="number" step="0.01" min="0.01" id="referral_max_discount_amount" name="referral_max_discount_amount" class="form-input" placeholder="Optional cap, e.g. 200">
                <p class="form-help">Caps how much a percentage reward can take off in rupees.</p>
                <p class="form-error" data-error-for="referral_max_discount_amount"></p>
            </div>
            <div class="form-group-half">
                <label class="form-label" for="referral_validity_days">Validity (Days)<span class="form-required">*</span></label>
                <input type="number" step="1" min="1" max="365" id="referral_validity_days" name="referral_validity_days" class="form-input" required>
                <p class="form-error" data-error-for="referral_validity_days"></p>
            </div>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" id="saveSettingsBtn" class="btn btn-primary">
            <span id="saveSettingsBtnText">Save Changes</span>
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

    .color-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; }
    @media (max-width: 640px) { .color-grid { grid-template-columns: 1fr; } }
    .color-input-row { display: flex; align-items: center; gap: 0.75rem; }
    .color-swatch {
        width: 3.5rem; height: 2.75rem; border-radius: 0.6rem; border: 1px solid color-mix(in srgb, var(--color-forest-900) 12%, transparent);
        cursor: pointer; background: none; padding: 0.15rem;
    }
    .color-hex { font-family: monospace; font-size: 0.85rem; color: var(--color-forest-900); text-transform: uppercase; }
    body[data-theme="dark"] .color-hex { color: var(--color-moringa-100); }
</style>
@endpush

@push('scripts')
<script>
(function () {
    const form = document.getElementById('settingsForm');
    const saveBtn = document.getElementById('saveSettingsBtn');
    const saveBtnText = document.getElementById('saveSettingsBtnText');
    const permissionNotice = document.getElementById('permissionNotice');
    const logoInput = document.getElementById('logo');
    const logoPreview = document.getElementById('logoPreview');
    const extractColorsBtn = document.getElementById('extractColorsBtn');
    const extractColorsBtnText = document.getElementById('extractColorsBtnText');

    function authHeaders() {
        const token = localStorage.getItem('token');
        return token ? { 'Authorization': 'Bearer ' + token } : {};
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

    ['primary_color', 'secondary_color', 'accent_color'].forEach(name => {
        const input = document.getElementById(name);
        const hex = document.getElementById(name + '_hex');
        input.addEventListener('input', () => { hex.textContent = input.value; });
    });

    // ─── Extract theme colors from the logo ─────────────────────────────────
    // Pure client-side: sample the logo onto a canvas, bucket similar pixels
    // together, and surface the most prominent/vivid colors as swatches. The
    // manual color pickers above are untouched — this just pre-fills them.
    function rgbToHex(r, g, b) {
        return '#' + [r, g, b].map(v => Math.round(v).toString(16).padStart(2, '0')).join('');
    }

    function colorDistance(a, b) {
        return Math.sqrt((a.r - b.r) ** 2 + (a.g - b.g) ** 2 + (a.b - b.b) ** 2);
    }

    function extractPalette(img, count) {
        const size = 80;
        const canvas = document.createElement('canvas');
        canvas.width = size;
        canvas.height = size;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, size, size);

        let pixels;
        try {
            pixels = ctx.getImageData(0, 0, size, size).data;
        } catch (err) {
            return null; // canvas got tainted (cross-origin logo) — can't read pixels
        }

        const step = 24; // quantization step per channel — groups near-identical colors
        const buckets = new Map();

        for (let i = 0; i < pixels.length; i += 4) {
            const r = pixels[i], g = pixels[i + 1], b = pixels[i + 2], a = pixels[i + 3];
            if (a < 200) continue; // skip transparent pixels

            const max = Math.max(r, g, b), min = Math.min(r, g, b);
            const lightness = (max + min) / 2;
            const saturation = max === min ? 0 : (max - min) / (255 - Math.abs(2 * lightness - 255));

            if (lightness > 245 || lightness < 12) continue; // skip near-white/near-black background

            const key = [Math.round(r / step), Math.round(g / step), Math.round(b / step)].join(',');
            const entry = buckets.get(key) || { r: 0, g: 0, b: 0, n: 0, sat: 0 };
            entry.r += r; entry.g += g; entry.b += b; entry.n += 1; entry.sat += saturation;
            buckets.set(key, entry);
        }

        const swatches = Array.from(buckets.values())
            .map(e => ({ r: e.r / e.n, g: e.g / e.n, b: e.b / e.n, count: e.n, avgSat: e.sat / e.n }))
            // weight by frequency and saturation, so a vivid brand color beats
            // a larger patch of dull anti-aliasing pixels
            .sort((a, b) => (b.count * (0.4 + b.avgSat)) - (a.count * (0.4 + a.avgSat)));

        const picked = [];
        for (const swatch of swatches) {
            if (picked.length >= count) break;
            if (picked.some(p => colorDistance(p, swatch) < 60)) continue; // too similar to one already picked
            picked.push(swatch);
        }

        if (!picked.length) return null; // logo had no usable color (e.g. pure black/white)

        while (picked.length < count) picked.push(picked[picked.length - 1]);

        return picked.map(s => rgbToHex(s.r, s.g, s.b));
    }

    function updateExtractButtonState() {
        extractColorsBtn.disabled = !logoPreview.querySelector('img');
    }

    extractColorsBtn.addEventListener('click', () => {
        const img = logoPreview.querySelector('img');
        if (!img) return;

        const run = () => {
            const palette = extractPalette(img, 3);

            if (!palette) {
                alert('Could not extract colors from this logo — try picking them manually instead.');
                return;
            }

            ['primary_color', 'secondary_color', 'accent_color'].forEach((name, i) => {
                document.getElementById(name).value = palette[i];
                document.getElementById(name + '_hex').textContent = palette[i];
            });

            extractColorsBtnText.textContent = 'Extracted!';
            setTimeout(() => { extractColorsBtnText.textContent = 'Extract Theme Colors from Logo'; }, 1600);
        };

        if (img.complete && img.naturalWidth) run(); else img.onload = run;
    });

    const referralDiscountType = document.getElementById('referral_discount_type');
    function syncReferralFieldVisibility() {
        document.getElementById('referralMaxDiscountField').style.display =
            referralDiscountType.value === 'percentage' ? '' : 'none';
    }
    referralDiscountType.addEventListener('change', syncReferralFieldVisibility);

    async function loadSettings() {
        try {
            const response = await fetch('/api/admin/settings', { headers: authHeaders() });
            const payload = await response.json();
            const settings = payload.data;

            document.getElementById('site_name').value = settings.site_name ?? '';
            document.getElementById('tagline').value = settings.tagline ?? '';

            ['primary_color', 'secondary_color', 'accent_color'].forEach(name => {
                const value = settings[name] ?? '#000000';
                document.getElementById(name).value = value;
                document.getElementById(name + '_hex').textContent = value;
            });

            if (settings.logo_url) {
                logoPreview.innerHTML = `<img src="${settings.logo_url}" alt="">`;
            }
            updateExtractButtonState();

            referralDiscountType.value = settings.referral_discount_type ?? 'percentage';
            document.getElementById('referral_discount_value').value = settings.referral_discount_value ?? 10;
            document.getElementById('referral_max_discount_amount').value = settings.referral_max_discount_amount ?? '';
            document.getElementById('referral_validity_days').value = settings.referral_validity_days ?? 30;
            syncReferralFieldVisibility();
        } catch (err) {
            alert('Could not load settings.');
        }
    }

    async function loadProfile() {
        try {
            const response = await fetch('/api/profile', { headers: authHeaders() });
            const payload = await response.json();
            const user = payload.user;
            const storeUrlCard = document.getElementById('storeUrlCard');
            const storeUrlLink = document.getElementById('storeUrlLink');

            if (!user || !['admin', 'super_admin'].includes(user.role)) {
                permissionNotice.style.display = 'block';
                saveBtn.disabled = true;
                return;
            }

            if (user.store_slug) {
                const url = '/store/' + user.store_slug;
                storeUrlLink.href = url;
                storeUrlLink.innerHTML = `<i class="fa-solid fa-arrow-up-right-from-square"></i> ${window.location.origin}${url}`;
                storeUrlCard.style.display = 'block';
            }
        } catch (err) {
            // If we can't tell, leave the form enabled — the API itself enforces the real permission.
        }
    }

    logoInput.addEventListener('change', () => {
        const file = logoInput.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = e => {
            logoPreview.innerHTML = `<img src="${e.target.result}" alt="">`;
            updateExtractButtonState();
        };
        reader.readAsDataURL(file);
    });

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (saveBtn.disabled) return;

        clearErrors();
        saveBtn.disabled = true;
        saveBtnText.textContent = 'Saving…';

        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        try {
            const response = await fetch('/api/admin/settings', {
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
                alert(data.message || 'Could not save settings.');
                return;
            }

            alert('Settings saved.');
        } catch (err) {
            alert('Something went wrong. Please try again.');
        } finally {
            saveBtn.disabled = false;
            saveBtnText.textContent = 'Save Changes';
        }
    });

    loadSettings();
    loadProfile();
})();
</script>
@endpush
