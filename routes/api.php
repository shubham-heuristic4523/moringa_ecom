<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\CheckoutController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Api\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Api\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Api\Admin\ReferralController as AdminReferralController;
use App\Http\Controllers\Api\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Api\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Api\SettingController;
use App\Http\Controllers\Api\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Api\OfferController;
use App\Http\Controllers\Api\FlashSaleController;
use App\Http\Controllers\Api\Admin\FlashSaleController as AdminFlashSaleController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\NotificationController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-registration-otp', [OtpController::class, 'verifyRegistrationOtp']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'markRead']);
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllRead']);

});
Route::middleware([
    'auth:sanctum',
    'role:super_admin'
])->prefix('admins')->group(function () {

    Route::post('/', [AdminController::class, 'store']);

    Route::get('/', [AdminController::class, 'index']);

    Route::get('/{id}', [AdminController::class, 'show']);

    Route::put('/{id}', [AdminController::class, 'update']);

    Route::delete('/{id}', [AdminController::class, 'destroy']);

    Route::post(
    '/change-password',
    [PasswordController::class, 'changePassword']
);

});
Route::post(
    '/forgot-password',
    [PasswordController::class, 'forgotPassword']
);

Route::get('/reset-password/{token}', function (
    string $token,
    Request $request
) {
    return response()->json([
        'token' => $token,
        'email' => $request->email,
    ]);
})->name('password.reset');

Route::post(
    '/reset-password',
    [PasswordController::class, 'resetPassword']
);

Route::middleware('auth:sanctum')->group(function () {

    Route::post(
        '/change-password',
        [PasswordController::class, 'changePassword']
    );

});

//Gayatri
Route::prefix('products')->group(function () {

    Route::get('/', [ProductController::class, 'index']);
    Route::get('/{id}', [ProductController::class, 'show']);

    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/', [ProductController::class, 'store']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);
    });
});

Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);

    Route::post('/', [CategoryController::class, 'store']);

    Route::get('/{id}', [CategoryController::class, 'show']);

    Route::put('/{id}', [CategoryController::class, 'update']);

    Route::delete('/{id}', [CategoryController::class, 'destroy']);

});

Route::prefix('brands')->group(function () {

    Route::get('/', [BrandController::class, 'index']);

    Route::post('/', [BrandController::class, 'store']);

    Route::get('/{id}', [BrandController::class, 'show']);

    Route::put('/{id}', [BrandController::class, 'update']);

    Route::delete('/{id}', [BrandController::class, 'destroy']);

});

Route::middleware('auth:sanctum')->prefix('wishlists')->group(function () {

    Route::get('/', [WishlistController::class, 'index']);

    Route::post('/', [WishlistController::class, 'store']);

    Route::delete('/{id}', [WishlistController::class, 'destroy']);

});


Route::post(
    '/verify-registration-otp',
    [OtpController::class, 'verifyRegistrationOtp']
);
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/customer/profile', [CustomerController::class, 'index']);

});
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/customer/profile', [CustomerController::class, 'getProfile']);

    Route::put('/customer/profile', [CustomerController::class, 'updateProfile']);
    Route::post('/customer/address',[CustomerController::class,'storeAddress']);
    Route::get('/customer/addresses', [CustomerController::class, 'listAddresses']);
    Route::get('/customer/address/{id}', [CustomerController::class, 'getAddress']); 
    Route::put('/customer/address/{id}', [CustomerController::class, 'updateAddress']);                                                    
    Route::delete('/customer/address/{id}', [CustomerController::class, 'deleteAddress']);
});

Route::middleware('auth:sanctum')->prefix('orders')->group(function () {

    Route::get('/', [OrderController::class, 'index']);

    Route::post('/', [OrderController::class, 'store']);

    Route::get('/{id}', [OrderController::class, 'show']);

    Route::post('/{id}/cancel', [OrderController::class, 'cancel']);

});

Route::middleware('auth:sanctum')->post('/coupons/validate', [CouponController::class, 'validate']);
Route::middleware('auth:sanctum')->get('/my-referrals', [ReferralController::class, 'myReferrals']);

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/orders')->group(function () {

    Route::get('/', [AdminOrderController::class, 'index']);

    Route::post('/', [AdminOrderController::class, 'store']);

    Route::get('/{id}', [AdminOrderController::class, 'show']);

    Route::put('/{id}', [AdminOrderController::class, 'update']);
    Route::patch('/{id}', [AdminOrderController::class, 'update']);

    Route::delete('/{id}', [AdminOrderController::class, 'destroy']);

});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/customers')->group(function () {

    Route::get('/', [AdminCustomerController::class, 'index']);

    Route::get('/{id}', [AdminCustomerController::class, 'show']);

    Route::put('/{id}', [AdminCustomerController::class, 'update']);

});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/offers')->group(function () {

    Route::get('/', [AdminOfferController::class, 'index']);

    Route::post('/', [AdminOfferController::class, 'store']);

    Route::get('/{id}', [AdminOfferController::class, 'show']);

    Route::put('/{id}', [AdminOfferController::class, 'update']);

    Route::delete('/{id}', [AdminOfferController::class, 'destroy']);

});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/referrals')->group(function () {

    Route::get('/', [AdminReferralController::class, 'index']);

    Route::get('/{id}', [AdminReferralController::class, 'show']);

});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/flash-sales')->group(function () {

    Route::get('/', [AdminFlashSaleController::class, 'index']);

    Route::post('/', [AdminFlashSaleController::class, 'store']);

    Route::get('/{id}', [AdminFlashSaleController::class, 'show']);

    Route::put('/{id}', [AdminFlashSaleController::class, 'update']);

    Route::delete('/{id}', [AdminFlashSaleController::class, 'destroy']);

});

Route::get('/settings', [SettingController::class, 'show']);
Route::get('/offers/active', [OfferController::class, 'active']);
Route::get('/flash-sales/active', [FlashSaleController::class, 'active']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::get('/admin/settings', [AdminSettingController::class, 'show']);
    Route::put('/admin/settings', [AdminSettingController::class, 'update']);
    Route::get('/admin/dashboard/stats', [AdminDashboardController::class, 'stats']);
});

Route::middleware(['auth:sanctum', 'role:admin'])->prefix('admin/reports')->group(function () {
    Route::get('/best-sellers', [AdminReportController::class, 'bestSellers']);
});