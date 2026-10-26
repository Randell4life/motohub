<?php

namespace App\Filament\Resources\Motorcycles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MotorcyclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('ფოტო')
                    ->disk('public'),

                TextColumn::make('title')
                    ->label('განცხადება')
                    ->searchable()
                    ->sortable()
                    ->limit(35),

                TextColumn::make('brand')
                    ->label('მარკა')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('model')
                    ->label('მოდელი')
                    ->searchable(),

                TextColumn::make('year')
                    ->label('წელი')
                    ->numeric()
                    ->sortable(),

                TextColumn::make('price')
                    ->label('ფასი')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('mileage')
                    ->label('გარბენი')
                    ->numeric()
                    ->sortable()
                    ->suffix(' კმ'),

                TextColumn::make('condition')
                    ->label('მდგომარეობა')
                    ->badge()
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'new' => 'ახალი',
                            'used' => 'მეორადი',
                            default => $state,
                        }
                    ),

                TextColumn::make('status')
                    ->label('სტატუსი')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'published' => 'success',
                        'sold' => 'info',
                        'draft' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(
                        fn (string $state): string => match ($state) {
                            'draft' => 'მონახაზი',
                            'published' => 'გამოქვეყნებული',
                            'sold' => 'გაყიდული',
                            default => $state,
                        }
                    )
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('შექმნის თარიღი')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('სტატუსი')
                    ->options([
                        'draft' => 'მონახაზი',
                        'published' => 'გამოქვეყნებული',
                        'sold' => 'გაყიდული',
                    ]),

                SelectFilter::make('condition')
                    ->label('მდგომარეობა')
                    ->options([
                        'new' => 'ახალი',
                        'used' => 'მეორადი',
                    ]),

                SelectFilter::make('brand')
                    ->label('მარკა')
                    ->options(fn (): array => \App\Models\Motorcycle::query()
                        ->whereNotNull('brand')
                        ->distinct()
                        ->orderBy('brand')
                        ->pluck('brand', 'brand')
                        ->all()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
