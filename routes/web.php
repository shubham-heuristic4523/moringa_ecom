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


Route::get('/admin', [AdminController::class, 'index'])
    ->name('welcome');

Route::get('admin/list', [AdminController::class, 'list'])
    ->name('admin.list');

Route::get('admin/form', [AdminController::class, 'form'])
    ->name('admin.form');
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
