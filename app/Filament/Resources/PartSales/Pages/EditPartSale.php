<?php

namespace App\Filament\Resources\PartSales\Pages;

use App\Filament\Resources\PartSales\PartSaleResource;
use App\Models\Part;
use App\Models\PartSale;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class EditPartSale extends EditRecord
{
    protected static string $resource = PartSaleResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        return DB::transaction(function () use ($record, $data) {
            $sale = PartSale::query()
                ->whereKey($record->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $oldPart = Part::query()
                ->whereKey($sale->part_id)
                ->lockForUpdate()
                ->first();

            $newPartId = (int) ($data['part_id'] ?? $sale->part_id);

            $newPart = Part::query()
                ->whereKey($newPartId)
                ->lockForUpdate()
                ->first();

            if (! $newPart || ! $newPart->is_active) {
                throw ValidationException::withMessages([
                    'data.part_id' => 'არჩეული ნაწილი ვერ მოიძებნა ან აქტიური არ არის.',
                ]);
            }

            $newQuantity = (int) ($data['quantity'] ?? 0);

            if ($newQuantity < 1) {
                throw ValidationException::withMessages([
                    'data.quantity' => 'რაოდენობა უნდა იყოს მინიმუმ 1.',
                ]);
            }

            $samePart = $oldPart && (int) $oldPart->id === (int) $newPart->id;

            // იგივე ნაწილის შემთხვევაში მარაგს ვუმატებთ ძველ გაყიდულ რაოდენობას,
            // რათა ხელახლა შევამოწმოთ ხელმისაწვდომი მარაგი.
            $availableStock = (int) $newPart->stock;

            if ($samePart) {
                $availableStock += (int) $sale->quantity;
            }

            if ($availableStock < $newQuantity) {
                throw ValidationException::withMessages([
                    'data.quantity' => "მარაგში საკმარისი რაოდენობა არ არის. ხელმისაწვდომია {$availableStock} ცალი.",
                ]);
            }

            // ძველი გაყიდვის რაოდენობა დავაბრუნოთ ძველი ნაწილის მარაგში.
            if ($oldPart) {
                $oldPart->increment('stock', (int) $sale->quantity);
            }

            // ახალი გაყიდვის რაოდენობა ჩამოვაკლოთ ახალი ნაწილის მარაგს.
            $newPart->refresh();

            if ($samePart) {
                // მარაგი უკვე შეიცავს ძველი გაყიდვის დაბრუნებულ რაოდენობას.
                $availableAfterReturn = (int) $newPart->stock;
            } else {
                $availableAfterReturn = (int) $newPart->stock;
            }

            if ($availableAfterReturn < $newQuantity) {
                throw ValidationException::withMessages([
                    'data.quantity' => 'მარაგში საკმარისი რაოდენობა არ არის.',
                ]);
            }

            $newPart->decrement('stock', $newQuantity);

            $sale->fill([
                'part_id' => $newPart->id,
                'part_title' => $newPart->title,
                'quantity' => $newQuantity,
                'unit_price' => (float) $newPart->price,
                'unit_cost' => (float) $newPart->cost_price,
                'total_amount' => round((float) $newPart->price * $newQuantity, 2),
                'total_cost' => round((float) $newPart->cost_price * $newQuantity, 2),
                'sold_at' => $data['sold_at'] ?? $sale->sold_at,
                'notes' => $data['notes'] ?? null,
            ]);

            $sale->save();

            return $sale;
        });
    }


 
protected function getHeaderActions(): array
{
    return [
        ViewAction::make(),

        DeleteAction::make()
            ->action(function (PartSale $record): void {
                DB::transaction(function () use ($record): void {
                    $sale = PartSale::query()
                        ->whereKey($record->getKey())
                        ->lockForUpdate()
                        ->firstOrFail();

                    $part = Part::query()
                        ->whereKey($sale->part_id)
                        ->lockForUpdate()
                        ->first();

                    if ($part) {
                        $part->increment('stock', (int) $sale->quantity);
                    }

                    $sale->delete();
                });
            }),
    ];
}

}