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
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\Customer\CustomerController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\Admin\OrderController as AdminOrderController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-registration-otp', [OtpController::class, 'verifyRegistrationOtp']);

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [AuthController::class, 'profile']);

    Route::post('/logout', [AuthController::class, 'logout']);

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
    Route::post('/', [ProductController::class, 'store']);         

    Route::get('/{id}', [ProductController::class, 'show']);       

    Route::put('/{id}', [ProductController::class, 'update']);     
    Route::patch('/{id}', [ProductController::class, 'update']);   

    Route::delete('/{id}', [ProductController::class, 'destroy']); 
});

Route::prefix('categories')->group(function () {

    Route::get('/', [CategoryController::class, 'index']);

    Route::post('/', [CategoryController::class, 'store']);

    Route::get('/{id}', [CategoryController::class, 'show']);

    Route::put('/{id}', [CategoryController::class, 'update']);

    Route::patch('/{id}', [CategoryController::class, 'update']);

    Route::delete('/{id}', [CategoryController::class, 'destroy']);

});

Route::prefix('brands')->group(function () {

    Route::get('/', [BrandController::class, 'index']);

    Route::post('/', [BrandController::class, 'store']);

    Route::get('/{id}', [BrandController::class, 'show']);

    Route::put('/{id}', [BrandController::class, 'update']);

    Route::patch('/{id}', [BrandController::class, 'update']);

    Route::delete('/{id}', [BrandController::class, 'destroy']);

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

Route::middleware(['auth:sanctum', 'role:user,admin'])->prefix('admin/orders')->group(function () {

    Route::get('/', [AdminOrderController::class, 'index']);

    Route::post('/', [AdminOrderController::class, 'store']);

    Route::get('/{id}', [AdminOrderController::class, 'show']);

    Route::put('/{id}', [AdminOrderController::class, 'update']);
    Route::patch('/{id}', [AdminOrderController::class, 'update']);

    Route::delete('/{id}', [AdminOrderController::class, 'destroy']);

});