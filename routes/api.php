<?php

use App\Http\Controllers\PartRequestController;
use App\Models\Motorcycle;
use App\Models\PartListing;
use App\Models\RentalScooter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/mobile/motorcycles', function () {
    $items = Motorcycle::query()
        ->where('status', 'published')
        ->orderByDesc('is_featured')
        ->orderByDesc('published_at')
        ->orderByDesc('created_at')
        ->limit(30)
        ->get(['id', 'title', 'slug', 'brand', 'model', 'year', 'price', 'mileage', 'engine_cc', 'condition', 'location', 'description', 'image_path', 'is_featured']);

    return response()->json([
        'data' => $items->map(fn ($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'brand' => $item->brand,
            'model' => $item->model,
            'year' => $item->year,
            'price' => $item->price,
            'mileage' => $item->mileage,
            'engine_cc' => $item->engine_cc,
            'condition' => $item->condition,
            'location' => $item->location,
            'description' => $item->description,
            'image_url' => $item->image_path ? url(Storage::url($item->image_path)) : null,
            'is_featured' => (bool) $item->is_featured,
        ]),
    ]);
})->middleware('throttle:60,1');

Route::get('/mobile/rentals', function () {
    $items = RentalScooter::query()
        ->where('status', 'active')
        ->latest()
        ->limit(30)
        ->get(['id', 'title', 'slug', 'brand', 'model', 'year', 'engine_cc', 'daily_rate', 'location', 'description', 'image_path']);

    return response()->json([
        'data' => $items->map(fn ($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'brand' => $item->brand,
            'model' => $item->model,
            'year' => $item->year,
            'engine_cc' => $item->engine_cc,
            'daily_rate' => $item->daily_rate,
            'location' => $item->location,
            'description' => $item->description,
            'image_url' => $item->image_path ? url(Storage::url($item->image_path)) : null,
        ]),
    ]);
})->middleware('throttle:60,1');

Route::get('/mobile/parts', function () {
    $items = PartListing::query()
        ->where('status', 'published')
        ->latest()
        ->limit(30)
        ->get(['id', 'title', 'slug', 'brand', 'category', 'condition', 'part_number', 'compatible_motorcycle', 'description', 'price', 'image_path']);

    return response()->json([
        'data' => $items->map(fn ($item) => [
            'id' => $item->id,
            'title' => $item->title,
            'slug' => $item->slug,
            'brand' => $item->brand,
            'category' => $item->category,
            'condition' => $item->condition,
            'part_number' => $item->part_number,
            'compatible_motorcycle' => $item->compatible_motorcycle,
            'description' => $item->description,
            'price' => $item->price,
            'image_url' => $item->image_path ? url(Storage::url($item->image_path)) : null,
        ]),
    ]);
})->middleware('throttle:60,1');

Route::post('/part-requests', [PartRequestController::class, 'storeApi'])
    ->middleware('throttle:5,1');
