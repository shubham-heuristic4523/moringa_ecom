<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Admin\View\AdminController;


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

Route::view('/', 'storefront.home')->name('home');

Route::get('/store/{slug}', function (string $slug) {
    return view('storefront.home', ['storeSlug' => $slug]);
})->name('store');

Route::get('/product/{id}', function (int $id) {
    return view('storefront.product', ['productId' => $id]);
})->name('product.show');

Route::view('/cart', 'storefront.cart')->name('cart');
Route::view('/checkout', 'storefront.checkout')->name('checkout');
Route::view('/order-success/{order}', 'storefront.order-success')->name('order.success');
Route::view('/account', 'storefront.account')->name('account');

Route::get('/admin', [AdminController::class, 'index'])
    ->name('welcome');

Route::get('admin/list', [AdminController::class, 'list'])
    ->name('admin.list');

Route::get('admin/form', [AdminController::class, 'form'])
    ->name('admin.form');

Route::get('admin/form/{product}', [AdminController::class, 'form'])
    ->name('admin.form.edit');

Route::get('admin/orders', [AdminController::class, 'orders'])
    ->name('admin.orders');

Route::get('admin/customers', [AdminController::class, 'customers'])
    ->name('admin.customers');

Route::get('admin/offers', [AdminController::class, 'offers'])
    ->name('admin.offers');

Route::get('admin/flash-sales', [AdminController::class, 'flashSales'])
    ->name('admin.flash-sales');

Route::get('admin/referrals', [AdminController::class, 'referrals'])
    ->name('admin.referrals');

Route::get('admin/settings', [AdminController::class, 'settings'])
    ->name('admin.settings');

Route::get('admin/admins', [AdminController::class, 'admins'])
    ->name('admin.admins');

Route::get('admin/reports', [AdminController::class, 'reports'])
    ->name('admin.reports');
// ─── Utility ─────────────────────────────────────────────────────────────────

Route::get('/test-mail', function () {
    Mail::raw('Laravel Email Test', fn($m) => $m->to(config('mail.from.address'))->subject('Test Mail'));
    return 'Mail Sent';
});

Route::get('/test-view', function () {
    return view('components.navigation.admin-sidebar', ['message' => 'This is a test message.']);
});

//login and registration view routes
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::view('/dashboard', 'admin.dashboard')
    
    ->name('dashboard');
