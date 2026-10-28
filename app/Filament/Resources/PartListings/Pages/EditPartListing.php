<?php

namespace App\Filament\Resources\PartListings\Pages;

use App\Filament\Resources\PartListings\PartListingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPartListing extends EditRecord
{
    protected static string $resource = PartListingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('განცხადების წაშლა'),
        ];
    }
}
