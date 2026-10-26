<?php

namespace App\Filament\Resources\RentalBookings\Pages;

use App\Filament\Resources\RentalBookings\RentalBookingResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewRentalBooking extends ViewRecord
{
    protected static string $resource = RentalBookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
