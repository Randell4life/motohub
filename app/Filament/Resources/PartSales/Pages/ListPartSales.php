<?php

namespace App\Filament\Resources\PartSales\Pages;

use App\Filament\Resources\PartSales\PartSaleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPartSales extends ListRecords
{
    protected static string $resource = PartSaleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
