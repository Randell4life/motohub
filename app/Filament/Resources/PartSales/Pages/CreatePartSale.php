<?php

namespace App\Filament\Resources\PartSales\Pages;

use App\Filament\Resources\PartSales\PartSaleResource;
use App\Models\Part;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreatePartSale extends CreateRecord
{
    protected static string $resource = PartSaleResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $part = Part::query()
                ->whereKey($data['part_id'])
                ->lockForUpdate()
                ->first();

            if (! $part || ! $part->is_active) {
                throw ValidationException::withMessages([
                    'data.part_id' => 'არჩეული ნაწილი ვერ მოიძებნა ან კატალოგში აქტიური არ არის.',
                ]);
            }

            $quantity = (int) ($data['quantity'] ?? 0);

            if ($quantity < 1) {
                throw ValidationException::withMessages([
                    'data.quantity' => 'რაოდენობა უნდა იყოს მინიმუმ 1.',
                ]);
            }

            if ($part->stock < $quantity) {
                throw ValidationException::withMessages([
                    'data.quantity' => "მარაგში მხოლოდ {$part->stock} ცალია.",
                ]);
            }

            $unitPrice = (float) $part->price;
            $unitCost = (float) $part->cost_price;

            $sale = static::getModel()::query()->create([
                'part_id' => $part->id,
                'part_title' => $part->title,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'unit_cost' => $unitCost,
                'total_amount' => round($unitPrice * $quantity, 2),
                'total_cost' => round($unitCost * $quantity, 2),
                'sold_at' => $data['sold_at'] ?? now(),
                'notes' => $data['notes'] ?? null,
            ]);

            $part->decrement('stock', $quantity);

            return $sale;
        });
    }
}
