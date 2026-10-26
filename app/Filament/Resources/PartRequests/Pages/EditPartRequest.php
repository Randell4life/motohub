<?php

namespace App\Filament\Resources\PartRequests\Pages;

use App\Filament\Resources\PartRequests\PartRequestResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPartRequest extends EditRecord
{
    protected static string $resource = PartRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
