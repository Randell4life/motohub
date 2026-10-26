<?php

namespace App\Filament\Resources\RentalScooters\Pages;

use App\Filament\Resources\RentalScooters\RentalScooterResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRentalScooters extends ListRecords
{
    protected static string $resource = RentalScooterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
