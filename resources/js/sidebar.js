/**
 * Moringa Admin — Sidebar behavior
 * Handles collapse/expand (desktop), off-canvas open/close (mobile),
 * and accordion submenus. State persists across reloads via localStorage.
 */

const STORAGE_KEY = 'moringa-sidebar-state';
const body = document.body;

function init() {
    const collapseBtn = document.getElementById('sidebar-collapse-btn');
    const mobileBtn = document.getElementById('sidebar-mobile-btn');
    const overlay = document.getElementById('sidebar-overlay');
    const sidebar = document.getElementById('admin-sidebar');

    if (!sidebar) return;

    const saved = localStorage.getItem(STORAGE_KEY);
    if (saved === 'collapsed') {
        body.setAttribute('data-sidebar', 'collapsed');
    }

    collapseBtn?.addEventListener('click', () => {
        const collapsed = body.getAttribute('data-sidebar') === 'collapsed';
        const next = collapsed ? 'expanded' : 'collapsed';
        body.setAttribute('data-sidebar', next);
        localStorage.setItem(STORAGE_KEY, next);
    });

    mobileBtn?.addEventListener('click', () => {
        body.setAttribute('data-sidebar', 'mobile-open');
    });

    overlay?.addEventListener('click', closeMobileSidebar);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeMobileSidebar();
    });

    function closeMobileSidebar() {
        if (body.getAttribute('data-sidebar') === 'mobile-open') {
            body.setAttribute('data-sidebar', localStorage.getItem(STORAGE_KEY) || 'expanded');
        }
    }

    // Accordion submenus (e.g. Products)
    sidebar.querySelectorAll('[data-sidebar-toggle]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const group = btn.closest('.sidebar-group');
            const wasOpen = group.classList.contains('is-open');

            sidebar.querySelectorAll('.sidebar-group.is-open').forEach((g) => {
                if (g !== group) g.classList.remove('is-open');
            });

            group.classList.toggle('is-open', !wasOpen);
        });
    });
}

document.addEventListener('DOMContentLoaded', init);
