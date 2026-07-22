<?php
use App\Models\User;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\PasswordController;
use Illuminate\Http\Request;

Route::post('/register', [AuthController::class, 'register']);

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