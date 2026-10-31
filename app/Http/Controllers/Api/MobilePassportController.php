<?php

namespace App\\Http\\Controllers\\Api;

use App\\Http\\Controllers\\Controller;
use App\\Models\\Motorcycle;

class MobilePassportController extends Controller
{
    public function show(int $motorcycle)
    {
        $bike = Motorcycle::query()
            ->where('status', 'published')
            ->with(['serviceRecords' => function ($query) {
                $query->with('serviceCenter')->orderByDesc('service_date');
            }])
            ->findOrFail($motorcycle);

        return response()->json([
            'motorcycle' => [
                'id' => $bike->id,
                'title' => $bike->title,
                'brand' => $bike->brand,
                'model' => $bike->model,
                'year' => $bike->year,
                'mileage' => $bike->mileage,
                'engine_cc' => $bike->engine_cc,
                'condition' => $bike->condition,
                'image_url' => $bike->image_path
                    ? (str_starts_with($bike->image_path, 'http://') || str_starts_with($bike->image_path, 'https://')
                        ? $bike->image_path
                        : request()->getSchemeAndHttpHost() . '/storage/' . ltrim(str_replace('public/', '', $bike->image_path), '/'))
                    : null,
            ],
            'service_records' => $bike->serviceRecords->map(fn ($record) => [
                'id' => $record->id,
                'service_date' => $record->service_date?->toDateString(),
                'mileage' => $record->mileage,
                'category' => $record->category,
                'title' => $record->title,
                'description' => $record->description,
                'cost' => $record->cost,
                'verification_status' => $record->verification_status,
                'is_verified' => $record->verification_status === 'verified' && (bool) $record->serviceCenter,
                'service_center' => $record->verification_status === 'verified' && $record->serviceCenter
                    ? $record->serviceCenter->name
                    : null,
                'verification_note' => $record->verification_note,
                'notes' => $record->notes,
            ])->values(),
        ]);
    }
}
