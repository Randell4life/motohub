<?php

namespace App\Filament\Resources\RentalScooters\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RentalScooterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('მოპედის ინფორმაცია')
                    ->columns(2)
                    ->schema([
                        TextInput::make('title')
                            ->label('სათაური')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->label('URL მისამართი')
                            ->helperText('მაგალითი: honda-dio-2023')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('brand')
                            ->label('ბრენდი')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('model')
                            ->label('მოდელი')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('year')
                            ->label('გამოშვების წელი')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue((int) date('Y') + 1)
                            ->nullable(),

                        TextInput::make('engine_cc')
                            ->label('ძრავის მოცულობა (cc)')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(65535)
                            ->nullable(),

                        TextInput::make('daily_rate')
                            ->label('დღიური ფასი')
                            ->numeric()
                            ->prefix('₾')
                            ->minValue(0.01)
                            ->required(),

                        TextInput::make('location')
                            ->label('მდებარეობა')
                            ->maxLength(255)
                            ->nullable(),

                        Select::make('status')
                            ->label('სტატუსი')
                            ->options([
                                'active' => 'ხელმისაწვდომია',
                                'maintenance' => 'ტექნიკურ მომსახურებაზეა',
                                'inactive' => 'გათიშულია',
                            ])
                            ->default('active')
                            ->required(),

                        FileUpload::make('image_path')
                            ->label('მოპედის ფოტო')
                            ->image()
                            ->disk('public')
                            ->directory('rental-scooters')
                            ->visibility('public')
                            ->maxSize(5120)
                            ->columnSpanFull(),

                        Textarea::make('description')
                            ->label('აღწერა')
                            ->rows(5)
                            ->maxLength(10000)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}