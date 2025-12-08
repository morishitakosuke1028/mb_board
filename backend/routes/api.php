<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\OwnerAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CourseImportController;
use App\Http\Controllers\Admin\OwnerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\User\AttendanceController as UserAttendanceController;
use App\Http\Controllers\User\ProfileController as UserProfileController;
use App\Http\Controllers\Public\CourseController as PublicCourseController;


Route::get('/courses/latest', [PublicCourseController::class, 'latest'])->name('courses.latest');
Route::get('/courses', [PublicCourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{id}', [PublicCourseController::class, 'show'])->name('courses.show');

Route::options('/{any}', function () {
    return response()->noContent();
})->where('any', '.*');

Route::prefix('user')->group(function () {
    Route::post('/login', [UserAuthController::class, 'login']);
    Route::middleware('auth:user')->group(function () {
        Route::get('/me', [UserAuthController::class, 'me']);
        Route::post('/logout', [UserAuthController::class, 'logout']);

        Route::get('/attendances', [UserAttendanceController::class, 'index']);
        Route::delete('/attendances/{attendance}', [UserAttendanceController::class, 'destroy']);

        Route::post('/attendances', [UserAttendanceController::class, 'store']);
        Route::get('/attendances/check/{courseId}', [UserAttendanceController::class, 'check']);

        Route::get('/profile/{user}/edit', [UserProfileController::class, 'edit']);
        Route::put('/profile/{user}', [UserProfileController::class, 'update']);
        Route::delete('/profile/{user}', [UserProfileController::class, 'destroy']);
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

    Route::get('/courses', [AdminCourseController::class, 'index']);
    Route::post('/courses', [AdminCourseController::class, 'store']);
    Route::get('/courses/{course}/edit', [AdminCourseController::class, 'edit']);
    Route::put('/courses/{course}', [AdminCourseController::class, 'update']);
    Route::delete('/courses/{course}', [AdminCourseController::class, 'destroy']);

    Route::get('/import/sample', [CourseImportController::class, 'downloadSample']);
    Route::post('/import', [CourseImportController::class, 'import']);
});
