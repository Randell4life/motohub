<?php

namespace App\Filament\Resources\Motorcycles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Str;

class MotorcycleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('მომხმარებელი')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload(),

                Select::make('partner_id')
                    ->label('პარტნიორი')
                    ->relationship('partner', 'name')
                    ->searchable()
                    ->preload(),

                TextInput::make('title')
                    ->label('განცხადების სათაური')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(
                        function (Set $set, ?string $state): void {
                            $set('slug', Str::slug($state ?? ''));
                        }
                    ),

                TextInput::make('slug')
                    ->label('URL-ის იდენტიფიკატორი')
                    ->required()
                    ->maxLength(255)
                    ->unique(ignoreRecord: true),

                TextInput::make('brand')
                    ->label('მარკა')
                    ->required()
                    ->maxLength(100),

                TextInput::make('model')
                    ->label('მოდელი')
                    ->required()
                    ->maxLength(100),

                TextInput::make('year')
                    ->label('გამოშვების წელი')
                    ->required()
                    ->numeric()
                    ->integer()
                    ->minValue(1900)
                    ->maxValue((int) date('Y') + 1),

                TextInput::make('price')
                    ->label('ფასი')
                    ->required()
                    ->numeric()
                    ->minValue(0)
                    ->prefix('$'),

                TextInput::make('mileage')
                    ->label('გარბენი (კმ)')
                    ->numeric()
                    ->integer()
                    ->minValue(0),

                TextInput::make('engine_cc')
                    ->label('ძრავის მოცულობა (cc)')
                    ->numeric()
                    ->integer()
                    ->minValue(1),

                Select::make('condition')
                    ->label('მდგომარეობა')
                    ->options([
                        'new' => 'ახალი',
                        'used' => 'მეორადი',
                    ])
                    ->required()
                    ->default('used'),

                TextInput::make('location')
                    ->label('მდებარეობა')
                    ->maxLength(255),

                Textarea::make('description')
                    ->label('აღწერა')
                    ->rows(5)
                    ->columnSpanFull(),

                FileUpload::make('image_path')
                    ->label('მოტოციკლის ფოტო')
                    ->image()
                    ->disk('public')
                    ->directory('motorcycles')
                    ->visibility('public')
                    ->imageEditor(),

                Select::make('status')
                    ->label('სტატუსი')
                    ->options([
                        'draft' => 'მონახაზი',
                        'published' => 'გამოქვეყნებული',
                        'sold' => 'გაყიდული',
                    ])
                    ->required()
                    ->default('draft'),

                Toggle::make('is_featured')
                    ->label('გამორჩეული განცხადება')
                    ->default(false),

                DateTimePicker::make('published_at')
                    ->label('გამოქვეყნების თარიღი'),
            ]);
    }
}
