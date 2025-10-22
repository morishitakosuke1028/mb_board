<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\OwnerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\UserController;

Route::options('/{any}', function () {
    return response()->noContent();
})->where('any', '.*');

Route::prefix('user')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::middleware('auth:user')->group(function () {
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



Route::prefix('admin')->middleware(['auth:admin'])->group(function () {
    Route::get('/owners', [OwnerController::class, 'index']);
    Route::delete('/owners/{owner}', [OwnerController::class, 'destroy']);
    Route::get('/users', [UserController::class, 'index']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);

    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
});
