<?php

namespace App\Filament\Resources\PartSales\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class PartSalesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('part_title')
                    ->label('ნაწილი')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->label('რაოდენობა')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('unit_price')
                    ->label('ერთეულის ფასი')
                    ->money('GEL')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('შემოსავალი')
                    ->money('GEL')
                    ->sortable(),

                TextColumn::make('total_cost')
                    ->label('თვითღირებულება')
                    ->money('GEL')
                    ->sortable(),

                TextColumn::make('profit')
                    ->label('მოგება')
                    ->state(fn ($record): float =>
                        (float) $record->total_amount
                        - (float) $record->total_cost
                    )
                    ->money('GEL')
                    ->color(fn ($state): string =>
                        (float) $state >= 0 ? 'success' : 'danger'
                    ),

                TextColumn::make('sold_at')
                    ->label('გაყიდვის თარიღი')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),

                TextColumn::make('notes')
                    ->label('შენიშვნა')
                    ->limit(30)
                    ->toggleable(),
            ])
            
            ->filters([
                Filter::make('sold_at')
                    ->label('გაყიდვის თარიღი')
                    ->form([
                        DatePicker::make('from')
                            ->label('თარიღიდან'),
                        DatePicker::make('until')
                            ->label('თარიღამდე'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'] ?? null,
                                fn (Builder $query, $date): Builder =>
                                    $query->whereDate('sold_at', '>=', $date)
                            )
                            ->when(
                                $data['until'] ?? null,
                                fn (Builder $query, $date): Builder =>
                                    $query->whereDate('sold_at', '<=', $date)
                            );
                    }),
            ])

->recordActions([
    ViewAction::make()
        ->label('ნახვა'),

    EditAction::make()
        ->label('რედაქტირება'),
]);
    }
}
