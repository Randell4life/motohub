<?php

namespace App\Filament\Resources\Parts\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PartsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->label('ნაწილის დასახელება')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('brand')
                    ->label('ბრენდი')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('category')
                    ->label('კატეგორია')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('part_number')
                    ->label('კატალოგის ნომერი')
                    ->searchable(),

                TextColumn::make('compatible_motorcycle')
                    ->label('თავსებადი მოტოციკლი')
                    ->searchable()
                    ->toggleable(),

                ImageColumn::make('image_path')
                    ->label('ფოტო'),

                TextColumn::make('price')
                    ->label('გასაყიდი ფასი')
                    ->money('GEL')
                    ->sortable(),

                TextColumn::make('cost_price')
                    ->label('თვითღირებულება')
                    ->money('GEL')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('stock')
                    ->label('მარაგი')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        (int) $state === 0 => 'danger',
                        (int) $state <= 3 => 'warning',
                        default => 'success',
                    })
                    ->description(fn ($state): ?string => match (true) {
                        (int) $state === 0 => 'მარაგი ამოწურულია',
                        (int) $state <= 3 => 'მარაგი დაბალია',
                        default => null,
                    }),

                IconColumn::make('is_active')
                    ->label('აქტიურია')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('შექმნის თარიღი')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->label('განახლების თარიღი')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('stock', 'asc')
            ->filters([])
            ->recordActions([
                ViewAction::make()->label('ნახვა'),
                EditAction::make()->label('რედაქტირება'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('წაშლა'),
                ]),
            ]);
    }
}
