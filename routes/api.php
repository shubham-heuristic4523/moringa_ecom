<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\OtpController;

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
Route::post(
    '/verify-registration-otp',
    [OtpController::class, 'verifyRegistrationOtp']
);
