<?php

namespace App\Filament\Resources\PartRequests\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PartRequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('part_name'),
                TextEntry::make('motorcycle_brand')
                    ->placeholder('-'),
                TextEntry::make('motorcycle_model')
                    ->placeholder('-'),
                TextEntry::make('motorcycle_year')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('customer_name'),
                TextEntry::make('phone'),
                TextEntry::make('email')
                    ->label('Email address')
                    ->placeholder('-'),
                TextEntry::make('details')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
