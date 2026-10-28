<?php

namespace App\Filament\Resources\PartListings\Pages;

use App\Filament\Resources\PartListings\PartListingResource;
use Filament\Resources\Pages\ListRecords;

class ListPartListings extends ListRecords
{
    protected static string $resource = PartListingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
