<?php

namespace App\Filament\Resources\PartListings\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartListingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('განცხადების ინფორმაცია')
                ->schema([
                    TextInput::make('title')
                        ->label('დასახელება')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('brand')
                        ->label('ბრენდი')
                        ->maxLength(255),

                    TextInput::make('category')
                        ->label('კატეგორია')
                        ->maxLength(255),

                    Select::make('condition')
                        ->label('მდგომარეობა')
                        ->options([
                            'new' => 'ახალი',
                            'used' => 'მეორადი',
                        ])
                        ->required(),

                    Select::make('part_type')
                        ->label('ნაწილის ტიპი')
                        ->options(['oem' => 'ორიგინალი (OEM)', 'aftermarket' => 'Aftermarket', 'used' => 'მეორადი / მოხსნილი'])
                        ->searchable(),

                    TextInput::make('motorcycle_brand')->label('მოტოციკლის ბრენდი')->maxLength(100),
                    TextInput::make('motorcycle_model')->label('მოტოციკლის მოდელი')->maxLength(120),
                    TextInput::make('motorcycle_year')->label('გამოშვების წელი')->numeric()->minValue(1900)->maxValue(2100),
                    TextInput::make('engine_cc')->label('ძრავის მოცულობა (cc)')->numeric()->minValue(1)->maxValue(10000),

                    TextInput::make('part_number')
                        ->label('ნაწილის კოდი')
                        ->maxLength(255),

                    TextInput::make('compatible_motorcycle')
                        ->label('თავსებადი მოტოციკლი')
                        ->maxLength(255),

                    TextInput::make('price')
                        ->label('ფასი (₾)')
                        ->numeric()
                        ->minValue(0)
                        ->required(),

                    Select::make('status')
                        ->label('სტატუსი')
                        ->options([
                            'pending' => 'შემოწმების პროცესში',
                            'published' => 'გამოქვეყნებული',
                            'rejected' => 'უარყოფილი',
                            'draft' => 'მონახაზი',
                        ])
                        ->required(),

                    FileUpload::make('image_path')
                        ->label('ფოტო')
                        ->image()
                        ->disk('public')
                        ->directory('part-listings')
                        ->visibility('public')
                        ->maxSize(5120),

                    Textarea::make('description')
                        ->label('აღწერა')
                        ->rows(5)
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }
}
