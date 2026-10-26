<?php

namespace App\Filament\Resources\RentalScooters\Pages;

use App\Filament\Resources\RentalScooters\RentalScooterResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRentalScooter extends ViewRecord
{
    protected static string $resource = RentalScooterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
