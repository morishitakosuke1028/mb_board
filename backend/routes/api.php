<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\OwnerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;

Route::options('/{any}', function () {
    return response()->noContent();
})->where('any', '.*');

Route::prefix('user')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [UserAuthController::class, 'me']);
        Route::post('/logout', [UserAuthController::class, 'logout']);
    });
});

Route::prefix('owner')->group(function () {
    Route::post('/login', [OwnerAuthController::class, 'login']);
    Route::middleware('auth:owner')->group(function () {
        Route::get('/me', [OwnerAuthController::class, 'me']);
        Route::post('/logout', [OwnerAuthController::class, 'logout']);
    });
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::middleware('auth:admin')->group(function () {
        Route::get('/me', [AdminAuthController::class, 'me']);
        Route::post('/logout', [AdminAuthController::class, 'logout']);
    });
});
