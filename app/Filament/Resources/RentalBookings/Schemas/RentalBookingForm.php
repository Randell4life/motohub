<?php

namespace App\Filament\Resources\RentalBookings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RentalBookingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('rental_scooter_id')
                    ->label('მოპედი')
                    ->relationship('rentalScooter', 'title')
                    ->required(),

                Select::make('user_id')
                    ->label('მომხმარებელი')
                    ->relationship('user', 'name')
                    ->required(),

                DatePicker::make('start_date')
                    ->label('ქირაობის დაწყება')
                    ->required(),

                DatePicker::make('end_date')
                    ->label('ქირაობის დასრულება')
                    ->required()
                    ->afterOrEqual('start_date'),

                TextInput::make('days')
                    ->label('დღეების რაოდენობა')
                    ->required()
                    ->numeric()
                    ->minValue(1),

                TextInput::make('daily_rate')
                    ->label('დღიური ფასი (₾)')
                    ->required()
                    ->numeric()
                    ->minValue(0),

                TextInput::make('total_price')
                    ->label('სრული ღირებულება (₾)')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('₾'),

                TextInput::make('customer_name')
                    ->label('მომხმარებლის სახელი')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('ტელეფონი')
                    ->tel()
                    ->required()
                    ->maxLength(30),

                Textarea::make('notes')
                    ->label('მომხმარებლის შენიშვნა')
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('ჯავშნის სტატუსი')
                    ->options([
                        'pending' => 'მოლოდინში',
                        'confirmed' => 'დადასტურებული',
                        'rejected' => 'უარყოფილი',
                        'cancelled' => 'გაუქმებული',
                        'completed' => 'დასრულებული',
                    ])
                    ->required()
                    ->default('pending'),

                Textarea::make('admin_note')
                    ->label('ადმინისტრატორის შენიშვნა')
                    ->columnSpanFull(),
            ]);
    }
}
