<?php

use App\\Http\\Controllers\\AuthController;
use App\\Http\\Controllers\\RewardsController;
use App\\Http\\Controllers\\RewardsAdminController;
use App\\Http\\Controllers\\CommunityController;
use App\\Http\\Controllers\\MessageController;
use App\\Http\\Controllers\\MotoAssistantController;
use App\\Http\\Controllers\\MotorcyclePassportController;
use App\\Http\\Controllers\\MotorcycleAccountController;
use App\\Http\\Controllers\\MotorcycleCatalogController;
use App\\Http\\Controllers\\PartController;
use App\\Http\\Controllers\\StoreCheckoutController;
use App\\Http\\Controllers\\PartRequestController;
use App\\Http\\Controllers\\RentalController;
use App\\Http\\Controllers\\Workshop1BookingController;
use App\\Http\\Controllers\\Workshop1Controller;
use App\\Http\\Controllers\\WorkshopApplicationController;
use App\\Http\\Controllers\\WorkshopBookingController;
use App\\Http\\Controllers\\WorkshopCatalogController;
use App\\Http\\Controllers\\WorkshopCustomerController;
use App\\Http\\Controllers\\WorkshopDashboardController;
use App\\Http\\Controllers\\MotorcycleServiceRecordController;
use App\\Http\\Controllers\\WorkshopMotorcycleController;
use App\\Http\\Controllers\\WorkshopServiceRecordController;
use App\\Models\\Motorcycle;
use Illuminate\\Http\\Request;
use Illuminate\\Support\\Facades\\Route;
use App\\Http\\Controllers\\MyServiceCenterController;
use App\\Http\\Controllers\\ServiceBookingController;
use App\\Http\\Controllers\\ServiceCenterController;
use App\\Http\\Controllers\\MyServiceBookingsController;
use App\\Http\\Controllers\\TransportController;
use App\\Http\\Controllers\\TransportPartnerController;
use App\\Http\\Controllers\\TransportAccountController;
use App\\Http\\Controllers\\PartListingController;
use App\\Models\\PartListing;
use App\\Models\\Part;



/*
|--------------------------------------------------------------------------
| бѓ›бѓ—бѓђбѓ•бѓђбѓ бѓ бѓ’бѓ•бѓ”бѓ бѓ“бѓ
|--------------------------------------------------------------------------
*/


Route::get('/invite/{code}', function (Request $request, string $code) {
    $request->session()->put('referral_code', strtoupper($code));
    return redirect()->route('register');
})->where('code', '[A-Za-z0-9]{4,20}')->name('referral.invite');

Route::middleware('auth')->group(function () {
    Route::get('/rewards', [RewardsController::class, 'index'])->name('rewards.index');
    Route::post('/rewards/redeem', [RewardsController::class, 'redeem'])->middleware('throttle:5,1')->name('rewards.redeem');
    Route::get('/admin/rewards', [RewardsAdminController::class, 'index'])->name('admin.rewards.index');
    Route::patch('/admin/rewards/{redemption}', [RewardsAdminController::class, 'update'])->name('admin.rewards.update');
    Route::post('/admin/rewards/users/{user}/adjust', [RewardsAdminController::class, 'adjust'])->middleware('throttle:30,1')->name('admin.rewards.adjust');
    Route::post('/admin/rewards/settings', [RewardsAdminController::class, 'settings'])->name('admin.rewards.settings');
});
