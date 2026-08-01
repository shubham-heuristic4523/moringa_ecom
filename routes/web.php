<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| Web Routes — LUXE Ecommerce
|--------------------------------------------------------------------------
|
| All routes return Blade views. API calls are handled separately in api.php.
| Authentication is handled by Sanctum tokens stored in localStorage.
|
*/

// ─── Public Storefront ──────────────────────────────────────────────────────

Route::get('/', fn() => view('customer.home'))->name('home');

Route::get('/products', fn() => view('customer.products.index'))->name('products.index');

Route::get('/products/{slug}', fn($slug) => view('customer.products.show', compact('slug')))->name('products.show');

Route::get('/search', fn() => view('customer.search'))->name('search');

Route::get('/cart', fn() => view('customer.cart'))->name('customer.cart');

Route::get('/wishlist', fn() => view('customer.wishlist'))->name('customer.wishlist');

// ─── Auth — Customer ─────────────────────────────────────────────────────────

Route::get('/login', fn() => view('auth.customer.login'))->name('auth.login');
Route::get('/register', fn() => view('auth.customer.login'))->name('auth.register');

// Password reset (web view only — API handles the logic)
Route::get('/forgot-password', fn() => view('auth.customer.login'))->name('auth.password.request');
Route::get('/reset-password/{token}', fn($token) => view('auth.customer.reset-password', compact('token')))->name('password.reset');

// Social OAuth redirect (handled by controller)
Route::get('/auth/{provider}', fn($provider) => redirect("/api/auth/{$provider}"))->name('auth.social');

// ─── Customer Account (Protected — JS checks token, redirects to /login if missing) ──

Route::prefix('account')->name('customer.')->group(function () {
    Route::get('/',              fn() => view('customer.dashboard.index'))->name('dashboard');
    Route::get('/orders',        fn() => view('customer.orders.index'))->name('orders');
    Route::get('/orders/{id}',   fn($id) => view('customer.orders.show', compact('id')))->name('orders.show');
    Route::get('/addresses',     fn() => view('customer.addresses.index'))->name('addresses');
    Route::get('/profile',       fn() => view('customer.profile'))->name('profile');
    Route::get('/security',      fn() => view('customer.security'))->name('security');
    Route::get('/notifications', fn() => view('customer.notifications'))->name('notifications');
    Route::get('/support',       fn() => view('customer.support'))->name('support');
});

// ─── Checkout ─────────────────────────────────────────────────────────────────

Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/information', fn() => view('checkout.information'))->name('information');
    Route::get('/shipping',    fn() => view('checkout.shipping'))->name('shipping');
    Route::get('/payment',     fn() => view('checkout.payment'))->name('payment');
});

Route::get('/order/{id}/success', fn($id) => view('checkout.success', compact('id')))->name('checkout.success');

// ─── Auth — Admin ────────────────────────────────────────────────────────────

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('/login', fn() => view('auth.admin.login'))->name('login');

    // Admin panel (protected — JS checks token + role)
    Route::get('/',           fn() => view('admin.dashboard'))->name('dashboard');
    Route::get('/products',   fn() => view('admin.products.index'))->name('products.index');
    Route::get('/products/create', fn() => view('admin.products.create'))->name('products.create');
    Route::get('/products/{id}/edit', fn($id) => view('admin.products.edit', compact('id')))->name('products.edit');
    Route::get('/orders',     fn() => view('admin.orders.index'))->name('orders.index');
    Route::get('/orders/{id}', fn($id) => view('admin.orders.show', compact('id')))->name('orders.show');
    Route::get('/customers',  fn() => view('admin.customers.index'))->name('customers.index');
    Route::get('/customers/{id}', fn($id) => view('admin.customers.show', compact('id')))->name('customers.show');
    Route::get('/categories', fn() => view('admin.categories.index'))->name('categories.index');
    Route::get('/analytics',  fn() => view('admin.analytics.index'))->name('analytics.index');
    Route::get('/marketing',  fn() => view('admin.marketing.index'))->name('marketing.index');
    Route::get('/support',    fn() => view('admin.support.index'))->name('support.index');
    Route::get('/media',      fn() => view('admin.media.index'))->name('media.index');
    Route::get('/settings',   fn() => view('admin.settings.index'))->name('settings.index');

    // Stubs for auth flows (API handles actual logic)
    Route::get('/2fa',        fn() => view('auth.admin.login'))->name('auth.2fa');
});

// ─── Utility ─────────────────────────────────────────────────────────────────

Route::get('/test-mail', function () {
    Mail::raw('Laravel Email Test', fn($m) => $m->to(config('mail.from.address'))->subject('Test Mail'));
    return 'Mail Sent';
});

<<<<<<< HEAD
Route::get('/test-view', function () {
    return view('components.navigation.admin-sidebar', ['message' => 'This is a test message.']);
});
=======
>>>>>>> ad2070541beb5fcbae5c0885b66bddaf5aa35664
