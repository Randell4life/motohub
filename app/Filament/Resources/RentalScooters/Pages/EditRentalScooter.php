<?php

namespace App\Filament\Resources\RentalScooters\Pages;

use App\Filament\Resources\RentalScooters\RentalScooterResource;
use Filament\Actions\DeleteAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditRentalScooter extends EditRecord
{
    protected static string $resource = RentalScooterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->before(function (DeleteAction $action): void {
                    if ($this->record->bookings()->exists()) {
                        Notification::make()
                            ->title('მოპედის წაშლა შეუძლებელია')
                            ->body('ამ მოპედს უკვე აქვს გაქირავების ჯავშნები. ჯავშნების ისტორიის შესანარჩუნებლად მოპედის წაშლა აკრძალულია.')
                            ->danger()
                            ->send();

                        $action->halt();
                    }
                }),
        ];
    }
}