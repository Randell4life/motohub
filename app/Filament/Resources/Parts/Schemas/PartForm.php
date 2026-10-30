<?php

namespace App\Filament\Resources\Parts\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PartForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('ნაწილის ინფორმაცია')
                    ->description('შეავსე სათადარიგო ნაწილის ძირითადი მონაცემები')
                    ->schema([
                        TextInput::make('title')
                            ->label('ნაწილის დასახელება')
                            ->placeholder('მაგ. Yamaha R1-ის ზეთის ფილტრი')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('slug')
                            ->label('URL იდენტიფიკატორი (slug)')
                            ->helperText('უნიკალური ლათინური ტექსტი, მაგ. yamaha-r1-oil-filter')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),

                        TextInput::make('brand')
                            ->label('ბრენდი')
                            ->placeholder('მაგ. Yamaha, NGK, Brembo')
                            ->maxLength(255),

                        Select::make('category')
                            ->label('კატეგორია')
                            ->options([
                                'engine' => 'ძრავი',
                                'brakes' => 'მუხრუჭები',
                                'filters' => 'ფილტრები',
                                'electrical' => 'ელექტროობა',
                                'suspension' => 'სავალი ნაწილი',
                                'body' => 'ძარის ნაწილები',
                                'transmission' => 'გადაცემათა სისტემა',
                                'tires' => 'საბურავები',
                                'accessories' => 'აქსესუარები',
                                'other' => 'სხვა',
                            ])
                            ->searchable(),

                        Select::make('part_type')
                            ->label('ნაწილის ტიპი')
                            ->options(['oem' => 'ორიგინალი (OEM)', 'aftermarket' => 'Aftermarket', 'used' => 'მეორადი / მოხსნილი'])
                            ->searchable(),

                        TextInput::make('motorcycle_brand')->label('მოტოციკლის ბრენდი')->maxLength(100),
                        TextInput::make('motorcycle_model')->label('მოტოციკლის მოდელი')->maxLength(120),
                        TextInput::make('motorcycle_year')->label('გამოშვების წელი')->numeric()->minValue(1900)->maxValue(2100),
                        TextInput::make('engine_cc')->label('ძრავის მოცულობა (cc)')->numeric()->minValue(1)->maxValue(10000),

                        TextInput::make('part_number')
                            ->label('ნაწილის ნომერი')
                            ->maxLength(255),

                        TextInput::make('compatible_motorcycle')
                            ->label('თავსებადი მოტოციკლი')
                            ->placeholder('მაგ. Yamaha R1 2015-2020')
                            ->maxLength(255),

                        Textarea::make('description')
                            ->label('აღწერა')
                            ->placeholder('მიუთითე მდგომარეობა, მახასიათებლები და სხვა დეტალები...')
                            ->rows(4)
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('ფასი და მარაგი')
                    ->description('შესყიდვისა და გაყიდვის ფასები ლარში')
                    ->schema([
                        TextInput::make('cost_price')
                            ->label('შესყიდვის ფასი')
                            ->numeric()
                            ->prefix('₾')
                            ->required()
                            ->minValue(0)
                            ->step(0.01),

                        TextInput::make('price')
                            ->label('გასაყიდი ფასი')
                            ->numeric()
                            ->prefix('₾')
                            ->required()
                            ->minValue(0)
                            ->step(0.01),

                        TextInput::make('stock')
                            ->label('მარაგის რაოდენობა')
                            ->numeric()
                            ->integer()
                            ->required()
                            ->default(0)
                            ->minValue(0),

                        Toggle::make('is_active')
                            ->label('ნაწილი გამოჩნდეს კატალოგში')
                            ->default(true)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),

                Section::make('ნაწილის ფოტო')
                    ->schema([
                        FileUpload::make('image_path')
                            ->label('ფოტოს ატვირთვა')
                            ->image()
                            ->disk('public')
                            ->directory('parts')
                            ->visibility('public')
                            ->imageEditor()
                            ->maxSize(5120)
                            ->helperText('ატვირთე JPG, PNG ან WebP ფოტო. მაქსიმალური ზომა — 5 MB.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
