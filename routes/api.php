<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\Admin\LaboratoryController;
use App\Http\Controllers\Admin\LabSampleTypeController;
use App\Http\Controllers\Admin\LabTestController;
use App\Http\Controllers\Admin\PharmacyController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\Admin\RegistrationReviewController;
use App\Http\Controllers\Admin\StaffAccountController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegistrationLookupController;
use Illuminate\Support\Facades\Route;

/*
| تسجيل الـ alias:
|  Laravel 11+ (bootstrap/app.php):
|     ->withMiddleware(fn ($m) => $m->alias(['role' => \App\Http\Middleware\EnsureRole::class]))
|  Laravel 10 (app/Http/Kernel.php) ضيف بـ $middlewareAliases:
|     'role' => \App\Http\Middleware\EnsureRole::class,
*/

Route::prefix('auth')->group(function () {
    Route::post('register',   [AuthController::class, 'register'])->middleware('throttle:5,1');
    Route::post('login',      [AuthController::class, 'login'])->middleware('throttle:10,1');
    Route::post('verify-otp', [AuthController::class, 'verifyOtp'])->middleware('throttle:10,1');
    Route::post('resend-otp', [AuthController::class, 'resendOtp'])->middleware('throttle:3,1');

    Route::middleware(['auth:sanctum', 'role'])->post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('addresses', AddressController::class);
});
Route::prefix('admin')
    ->middleware(['auth:sanctum']) // + middleware الأدوار عندك (admin / super_admin)
    ->group(function () {
        // الصيدلية
        Route::apiResource('product-categories', ProductCategoryController::class)
            ->parameters(['product-categories' => 'product_category']);
        Route::apiResource('products', ProductController::class);
        Route::apiResource('pharmacies', PharmacyController::class);
        Route::apiResource('laboratories', LaboratoryController::class);
        // المخبر
        Route::apiResource('lab-sample-types', LabSampleTypeController::class)
            ->parameters(['lab-sample-types' => 'sample_type']);
        Route::apiResource('lab-tests', LabTestController::class)
            ->parameters(['lab-tests' => 'lab_test']);
        Route::apiResource('regions', RegionController::class);

        Route::get('registrations', [RegistrationReviewController::class, 'index']);
        Route::post('registrations/{user}/approve', [RegistrationReviewController::class, 'approve']);
        Route::post('registrations/{user}/reject', [RegistrationReviewController::class, 'reject']);

            Route::post('pharmacies/{pharmacy}/staff',   [StaffAccountController::class, 'storePharmacyStaff']);
            Route::post('laboratories/{laboratory}/staff', [StaffAccountController::class, 'storeLabEmployee']);

    });

// قوائم شاشة التسجيل (مناطق، اختصاصات، أنواع إنتاج، صيدليات، مخابر)
Route::get('lookups/registration', RegistrationLookupController::class)->middleware('throttle:30,1');


