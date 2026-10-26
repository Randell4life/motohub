<?php

namespace App\Filament\Resources\ServiceCenters\Pages;

use App\Filament\Resources\ServiceCenters\ServiceCenterResource;
use Filament\Resources\Pages\ListRecords;

class ListServiceCenters extends ListRecords
{
    protected static string $resource = ServiceCenterResource::class;

    protected function getHeaderActions(): array
    {
        // Workshops are registered by their owners on the public site.
        // Admins review them here rather than creating workshop records manually.
        return [];
    }
}
