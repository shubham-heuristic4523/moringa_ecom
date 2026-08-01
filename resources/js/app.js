/**
 * LUXE Ecommerce — App Entry Point
 * Lazy-loads page-specific modules based on DOM markers.
 */

// Auth pages
if (document.getElementById('login-form') || document.getElementById('register-form')) {
    import('./modules/auth.js');
}

// Cart page
if (document.getElementById('cart-page-root')) {
    import('./modules/cart.js');
}
