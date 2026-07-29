/**
 * LUXE Ecommerce — API Client
 * Single source for all fetch calls. Handles auth tokens, errors, and redirects.
 */

const BASE_URL = '/api';
const TOKEN_KEY = 'luxe_token';

// ─── Token Helpers ──────────────────────────────────────────────────────────

export const getToken = () => localStorage.getItem(TOKEN_KEY);
export const setToken = (token) => localStorage.setItem(TOKEN_KEY, token);
export const clearToken = () => localStorage.removeItem(TOKEN_KEY);
export const isAuthenticated = () => !!getToken();

// ─── Core Request ───────────────────────────────────────────────────────────

export async function apiRequest(endpoint, options = {}) {
    const token = getToken();

    const headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
        ...(options.headers ?? {}),
    };

    const config = {
        ...options,
        headers,
    };

    try {
        const response = await fetch(`${BASE_URL}${endpoint}`, config);

        // Handle no-content responses
        if (response.status === 204) return { success: true };

        const data = await response.json();

        // Global error handling
        if (response.status === 401) {
            clearToken();
            window.location.href = '/login';
            return;
        }

        if (response.status === 403) {
            showToast('error', 'You do not have permission to perform this action.');
            return { success: false, status: 403 };
        }

        if (response.status === 404) {
            return { success: false, status: 404, message: data.message ?? 'Not found.' };
        }

        if (response.status === 422) {
            return { success: false, status: 422, errors: data.errors ?? {}, message: data.message ?? 'Validation failed.' };
        }

        if (response.status === 429) {
            showToast('error', 'Too many requests. Please wait a moment and try again.');
            return { success: false, status: 429 };
        }

        if (response.status >= 500) {
            showToast('error', 'A server error occurred. Please try again.');
            return { success: false, status: response.status };
        }

        return { success: response.ok, status: response.status, ...data };

    } catch (err) {
        showToast('error', 'Network error. Please check your connection.');
        return { success: false, message: 'Network error', error: err };
    }
}

// ─── Convenience Methods ─────────────────────────────────────────────────────

export const api = {
    get:    (url, opts = {}) => apiRequest(url, { method: 'GET', ...opts }),
    post:   (url, body, opts = {}) => apiRequest(url, { method: 'POST', body: JSON.stringify(body), ...opts }),
    put:    (url, body, opts = {}) => apiRequest(url, { method: 'PUT', body: JSON.stringify(body), ...opts }),
    patch:  (url, body, opts = {}) => apiRequest(url, { method: 'PATCH', body: JSON.stringify(body), ...opts }),
    delete: (url, opts = {}) => apiRequest(url, { method: 'DELETE', ...opts }),

    // Multipart (file uploads)
    upload: (url, formData, opts = {}) => {
        const token = getToken();
        return fetch(`${BASE_URL}${url}`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                ...(token ? { 'Authorization': `Bearer ${token}` } : {}),
            },
            body: formData,
            ...opts,
        }).then(r => r.json());
    },
};

// ─── Toast Notification ──────────────────────────────────────────────────────

export function showToast(type, message) {
    const container = document.getElementById('flash-container')
        ?? (() => {
            const el = document.createElement('div');
            el.id = 'flash-container';
            el.className = 'fixed top-4 right-4 z-[100] flex flex-col gap-2 max-w-sm w-full';
            document.body.appendChild(el);
            return el;
        })();

    const colorMap = {
        success: 'bg-secondary-container text-on-secondary-container border-secondary/20',
        error:   'bg-error-container text-on-error-container border-error/20',
        warning: 'bg-tertiary-fixed text-on-tertiary-container border-tertiary/20',
        info:    'bg-surface-container text-on-surface border-outline-variant/50',
    };
    const iconMap = {
        success: 'check_circle',
        error:   'error',
        warning: 'warning',
        info:    'info',
    };

    const toast = document.createElement('div');
    toast.className = `flex items-start gap-3 p-3 rounded-lg shadow-md border ${colorMap[type] ?? colorMap.info} animate-in slide-in-from-right duration-300`;
    toast.innerHTML = `
        <span class="material-symbols-outlined shrink-0 mt-px fill">${iconMap[type]}</span>
        <p class="text-sm flex-1">${message}</p>
        <button onclick="this.closest('div').remove()" class="opacity-60 hover:opacity-100 shrink-0">
            <span class="material-symbols-outlined text-[18px]">close</span>
        </button>`;

    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);
}

// ─── Form Helpers ─────────────────────────────────────────────────────────────

/** Display 422 validation errors under their respective fields */
export function displayFieldErrors(errors) {
    // Clear previous errors
    document.querySelectorAll('[data-error-for]').forEach(el => {
        el.textContent = '';
        el.classList.add('hidden');
    });

    Object.entries(errors).forEach(([field, messages]) => {
        // Match by data-error-for="field_name"
        const errorEl = document.querySelector(`[data-error-for="${field}"]`);
        if (errorEl) {
            errorEl.textContent = messages[0];
            errorEl.classList.remove('hidden');
        }
        // Also highlight the input border
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            input.classList.add('border-error', 'focus:border-error');
            input.classList.remove('border-outline', 'focus:border-primary');
        }
    });
}

/** Clear all field error states */
export function clearFieldErrors() {
    document.querySelectorAll('[data-error-for]').forEach(el => {
        el.textContent = ''; el.classList.add('hidden');
    });
    document.querySelectorAll('input, textarea, select').forEach(el => {
        el.classList.remove('border-error', 'focus:border-error');
    });
}

/** Set a button into loading state */
export function setButtonLoading(btn, loadingText = 'Loading...') {
    btn.dataset.originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = `
        <div class="w-4 h-4 border-2 border-current/30 border-t-current rounded-full animate-spin"></div>
        <span class="opacity-70">${loadingText}</span>`;
}

/** Restore button from loading state */
export function resetButton(btn) {
    btn.disabled = false;
    if (btn.dataset.originalContent) {
        btn.innerHTML = btn.dataset.originalContent;
    }
}
