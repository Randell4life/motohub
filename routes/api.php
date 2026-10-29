<?php

use App\\Http\\Controllers\\Api\\MobileCatalogController;
use App\\Http\\Controllers\\PartRequestController;
use Illuminate\\Support\\Facades\\Route;

Route::get('/mobile/motorcycles', [MobileCatalogController::class, 'motorcycles']);
Route::get('/mobile/parts', [MobileCatalogController::class, 'parts']);
Route::get('/mobile/part-listings', [MobileCatalogController::class, 'partListings']);
Route::get('/mobile/rentals', [MobileCatalogController::class, 'rentals']);
Route::get('/mobile/service-centers', [MobileCatalogController::class, 'serviceCenters']);

Route::post('/part-requests', [PartRequestController::class, 'storeApi'])
    ->middleware('throttle:5,1');
