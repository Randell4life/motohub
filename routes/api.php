<?php

use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileCatalogController;
use App\Http\Controllers\Api\MobileAccountController;
use App\Http\Controllers\Api\MobileBookingController;
use App\Http\Controllers\PartRequestController;
use App\Http\Middleware\AuthenticateMobileToken;
use Illuminate\Support\Facades\Route;

Route::prefix('mobile')->group(function () {
    Route::post('/register', [MobileAuthController::class, 'register'])->middleware('throttle:3,1');
    Route::post('/login', [MobileAuthController::class, 'login'])->middleware('throttle:5,1');
    Route::get('/motorcycles', [MobileCatalogController::class, 'motorcycles']);
    Route::get('/parts', [MobileCatalogController::class, 'parts']);
    Route::get('/part-listings', [MobileCatalogController::class, 'partListings']);
    Route::get('/rentals', [MobileCatalogController::class, 'rentals']);
    Route::get('/service-centers', [MobileCatalogController::class, 'serviceCenters']);

    Route::middleware(AuthenticateMobileToken::class)->group(function () {
        Route::get('/me', [MobileAccountController::class, 'show']);
        Route::patch('/me', [MobileAccountController::class, 'update'])->middleware('throttle:10,1');
        Route::post('/logout', [MobileAuthController::class, 'logout']);
        Route::get('/my-listings', [MobileAccountController::class, 'listings']);
        Route::post('/my-listings', [MobileAccountController::class, 'storeListing'])->middleware('throttle:5,1');
        Route::put('/my-listings/{motorcycle}', [MobileAccountController::class, 'updateListing'])->middleware('throttle:10,1');
        Route::delete('/my-listings/{motorcycle}', [MobileAccountController::class, 'deleteListing'])->middleware('throttle:10,1');
        Route::get('/my-bookings', [MobileAccountController::class, 'bookings']);
        Route::post('/rentals/{rentalScooter:slug}/bookings', [MobileBookingController::class, 'rentalBooking'])->middleware('throttle:5,1');
        Route::post('/service-centers/{serviceCenter:slug}/bookings', [MobileBookingController::class, 'serviceBooking'])->middleware('throttle:5,1');
    });
});

Route::post('/part-requests', [PartRequestController::class, 'storeApi'])->middleware('throttle:5,1');
