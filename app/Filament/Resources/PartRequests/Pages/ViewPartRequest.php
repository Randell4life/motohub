<?php

namespace App\Filament\Resources\PartRequests\Pages;

use App\Filament\Resources\PartRequests\PartRequestResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewPartRequest extends ViewRecord
{
    protected static string $resource = PartRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
