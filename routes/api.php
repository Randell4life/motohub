<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PartRequestController;

Route::post('/part-requests', [PartRequestController::class, 'storeApi'])
    ->middleware('throttle:5,1');
