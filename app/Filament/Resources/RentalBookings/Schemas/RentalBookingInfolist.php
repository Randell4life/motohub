<?php

namespace App\Filament\Resources\RentalBookings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RentalBookingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('rentalScooter.id')
                    ->label('Rental scooter'),
                TextEntry::make('user.name')
                    ->label('User'),
                TextEntry::make('start_date')
                    ->date(),
                TextEntry::make('end_date')
                    ->date(),
                TextEntry::make('days')
                    ->numeric(),
                TextEntry::make('daily_rate')
                    ->numeric(),
                TextEntry::make('total_price')
                    ->money(),
                TextEntry::make('customer_name'),
                TextEntry::make('phone'),
                TextEntry::make('notes')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('admin_note')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
