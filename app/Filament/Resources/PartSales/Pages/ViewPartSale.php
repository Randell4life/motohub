<?php

namespace App\Filament\Resources\PartSales\Pages;

use App\Filament\Resources\PartSales\PartSaleResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartSale extends ViewRecord
{
    protected static string $resource = PartSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
