/**
 * Moringa Admin — Navbar behavior
 * Handles dark/light theme toggle (persisted) and dropdown panels
 * (notifications, profile) with outside-click / escape dismissal.
 */

const THEME_KEY = 'moringa-theme';
const body = document.body;

function initTheme() {
    const toggleBtn = document.getElementById('theme-toggle-btn');
    const lightIcon = document.getElementById('theme-icon-light');
    const darkIcon = document.getElementById('theme-icon-dark');

    const saved = localStorage.getItem(THEME_KEY);
    if (saved === 'dark') applyTheme('dark');

    toggleBtn?.addEventListener('click', () => {
        const isDark = body.getAttribute('data-theme') === 'dark';
        applyTheme(isDark ? 'light' : 'dark');
    });

    function applyTheme(theme) {
        body.setAttribute('data-theme', theme);
        localStorage.setItem(THEME_KEY, theme);
        lightIcon?.classList.toggle('hidden', theme === 'dark');
        darkIcon?.classList.toggle('hidden', theme === 'light');
    }
}

function initDropdowns() {
    const roots = document.querySelectorAll('[data-dropdown-root]');

    function closeAll(except) {
        roots.forEach((root) => {
            if (root === except) return;
            root.querySelector('[data-dropdown-panel]')?.classList.remove('is-open');
            root.querySelector('[data-dropdown-chevron]')?.classList.remove('is-open');
        });
    }

    roots.forEach((root) => {
        const toggle = root.querySelector('[data-dropdown-toggle]');
        const panel = root.querySelector('[data-dropdown-panel]');
        const chevron = root.querySelector('[data-dropdown-chevron]');

        toggle?.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = panel.classList.contains('is-open');
            closeAll(root);
            panel.classList.toggle('is-open', !isOpen);
            chevron?.classList.toggle('is-open', !isOpen);
        });
    });

    document.addEventListener('click', () => closeAll(null));
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeAll(null);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    initTheme();
    initDropdowns();
});
