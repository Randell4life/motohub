<?php

namespace App\Filament\Resources\PartRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('part_name')
                    ->label('სასურველი ნაწილი')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('motorcycle_brand')
                    ->label('ბრენდი')
                    ->searchable(),

                TextColumn::make('motorcycle_model')
                    ->label('მოდელი')
                    ->searchable(),

                TextColumn::make('motorcycle_year')
                    ->label('წელი')
                    ->sortable(),

                TextColumn::make('customer_name')
                    ->label('მომხმარებელი')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('phone')
                    ->label('ტელეფონი')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('ელფოსტა')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('სტატუსი')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'new' => 'ახალი',
                        'in_progress' => 'მუშავდება',
                        'completed' => 'შესრულებული',
                        'rejected' => 'უარყოფილი',
                        default => $state ?? 'უცნობი',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'new' => 'info',
                        'in_progress' => 'warning',
                        'completed' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('მიღების თარიღი')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('სტატუსი')
                    ->options([
                        'new' => 'ახალი',
                        'in_progress' => 'მუშავდება',
                        'completed' => 'შესრულებული',
                        'rejected' => 'უარყოფილი',
                    ]),
            ])
            ->recordActions([
                ViewAction::make()->label('ნახვა'),
                EditAction::make()->label('რედაქტირება'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make()->label('მონიშნულის წაშლა'),
                ]),
            ]);
    }
}
