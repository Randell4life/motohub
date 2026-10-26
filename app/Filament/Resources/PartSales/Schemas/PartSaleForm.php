<?php

namespace App\Filament\Resources\PartSales\Schemas;

use App\Models\Part;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;

class PartSaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('გაყიდვის ინფორმაცია')
                    ->schema([
                        Select::make('part_id')
                            ->label('ნაწილი')
                            ->relationship(
                                name: 'part',
                                titleAttribute: 'title',
                                modifyQueryUsing: fn ($query) => $query
                                    ->where('is_active', true)
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, Set $set) {
                                $part = Part::find($state);

                                if (! $part) {
                                    return;
                                }

                                $set('part_title', $part->title);
                                $set('unit_price', $part->price);
                                $set('unit_cost', $part->cost_price);
                                $set('quantity', 1);
                                $set('total_amount', $part->price);
                                $set('total_cost', $part->cost_price);
                            })
                            ->columnSpanFull(),

                        TextInput::make('part_title')
                            ->label('ნაწილის დასახელება')
                            ->required()
                            ->readOnly(),

                        TextInput::make('quantity')
                            ->label('რაოდენობა')
                            ->required()
                            ->numeric()
                            ->integer()
                            ->minValue(1)
                            ->default(1)
                            ->live()
                            ->afterStateUpdated(function ($state, Get $get, Set $set) {
                                $quantity = max(0, (int) ($state ?? 0));
                                $set(
                                    'total_amount',
                                    round($quantity * (float) ($get('unit_price') ?? 0), 2)
                                );
                                $set(
                                    'total_cost',
                                    round($quantity * (float) ($get('unit_cost') ?? 0), 2)
                                );
                            }),

                        TextInput::make('unit_price')
                            ->label('გასაყიდი ფასი (ერთეული)')
                            ->required()
                            ->numeric()
                            ->prefix('₾')
                            ->minValue(0)
                            ->step(0.01)
                            ->readOnly(),

                        TextInput::make('unit_cost')
                            ->label('თვითღირებულება (ერთეული)')
                            ->required()
                            ->numeric()
                            ->prefix('₾')
                            ->minValue(0)
                            ->step(0.01)
                            ->readOnly(),

                        TextInput::make('total_amount')
                            ->label('გაყიდვის ჯამი')
                            ->required()
                            ->numeric()
                            ->prefix('₾')
                            ->readOnly(),

                        TextInput::make('total_cost')
                            ->label('თვითღირებულების ჯამი')
                            ->required()
                            ->numeric()
                            ->prefix('₾')
                            ->readOnly(),

                        DateTimePicker::make('sold_at')
                            ->label('გაყიდვის თარიღი')
                            ->required()
                            ->default(now()),
                    ])
                    ->columns(2),

                Section::make('დამატებითი ინფორმაცია')
                    ->schema([
                        Textarea::make('notes')
                            ->label('შენიშვნა')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
