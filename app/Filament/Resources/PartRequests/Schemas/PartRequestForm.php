<?php

namespace App\Filament\Resources\PartRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PartRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('part_name')
                    ->label('სასურველი ნაწილი')
                    ->required()
                    ->maxLength(255),

                TextInput::make('motorcycle_brand')
                    ->label('მოტოციკლის ბრენდი')
                    ->maxLength(255),

                TextInput::make('motorcycle_model')
                    ->label('მოტოციკლის მოდელი')
                    ->maxLength(255),

                TextInput::make('motorcycle_year')
                    ->label('გამოშვების წელი')
                    ->numeric()
                    ->minValue(1900)
                    ->maxValue(2100),

                TextInput::make('customer_name')
                    ->label('მომხმარებლის სახელი')
                    ->required()
                    ->maxLength(255),

                TextInput::make('phone')
                    ->label('ტელეფონი')
                    ->tel()
                    ->required()
                    ->maxLength(50),

                TextInput::make('email')
                    ->label('ელფოსტა')
                    ->email()
                    ->maxLength(255),

                Textarea::make('details')
                    ->label('დამატებითი ინფორმაცია')
                    ->rows(4)
                    ->columnSpanFull(),

                Select::make('status')
                    ->label('მოთხოვნის სტატუსი')
                    ->options([
                        'new' => 'ახალი',
                        'in_progress' => 'მუშავდება',
                        'completed' => 'შესრულებული',
                        'rejected' => 'უარყოფილი',
                    ])
                    ->required()
                    ->default('new')
                    ->native(false),
            ]);
    }
}
