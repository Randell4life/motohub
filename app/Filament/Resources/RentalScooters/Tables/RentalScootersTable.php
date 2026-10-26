<?php

namespace App\Filament\Resources\RentalScooters\Tables;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RentalScootersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_path')
                    ->label('ფოტო')
                    ->disk('public')
                    ->square(),

                TextColumn::make('title')
                    ->label('სათაური')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('brand')
                    ->label('ბრენდი')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('model')
                    ->label('მოდელი')
                    ->searchable(),

                TextColumn::make('daily_rate')
                    ->label('დღიური ფასი')
                    ->money('GEL')
                    ->sortable(),

                TextColumn::make('location')
                    ->label('მდებარეობა')
                    ->searchable(),

                TextColumn::make('status')
                    ->label('სტატუსი')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'ხელმისაწვდომია',
                        'maintenance' => 'ტექნიკური მომსახურება',
                        'inactive' => 'გათიშულია',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'maintenance' => 'warning',
                        'inactive' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('სტატუსი')
                    ->options([
                        'active' => 'ხელმისაწვდომია',
                        'maintenance' => 'ტექნიკური მომსახურება',
                        'inactive' => 'გათიშულია',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->defaultSort('created_at', 'desc');
    }
}