<?php

namespace App\Filament\Resources\PartListings\Tables;

use App\Models\PartListing;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class PartListingsTable
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
                    ->label('დასახელება')
                    ->searchable()
                    ->sortable()
                    ->limit(35),

                TextColumn::make('user.name')
                    ->label('გამყიდველი')
                    ->searchable()
                    ->placeholder('უცნობი'),

                TextColumn::make('brand')
                    ->label('ბრენდი')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('condition')
                    ->label('მდგომარეობა')
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'new' => 'ახალი',
                        'used' => 'მეორადი',
                        default => (string) $state,
                    })
                    ->badge(),

                TextColumn::make('price')
                    ->label('ფასი')
                    ->money('GEL')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('სტატუსი')
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'pending' => 'შემოწმების პროცესში',
                        'published' => 'გამოქვეყნებული',
                        'rejected' => 'უარყოფილი',
                        'draft' => 'მონახაზი',
                        default => (string) $state,
                    })
                    ->badge()
                    ->color(fn ($state): string => match ($state) {
                        'published' => 'success',
                        'pending' => 'warning',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('შექმნილია')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('სტატუსი')
                    ->options([
                        'pending' => 'შემოწმების პროცესში',
                        'published' => 'გამოქვეყნებული',
                        'rejected' => 'უარყოფილი',
                        'draft' => 'მონახაზი',
                    ]),

                SelectFilter::make('condition')
                    ->label('მდგომარეობა')
                    ->options([
                        'new' => 'ახალი',
                        'used' => 'მეორადი',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('დამტკიცება')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('განცხადების გამოქვეყნება')
                    ->modalDescription('განცხადება გახდება საჯაროდ ხილული.')
                    ->visible(fn (PartListing $record): bool => $record->status !== 'published')
                    ->action(function (PartListing $record): void {
                        $record->update(['status' => 'published']);

                        Notification::make()
                            ->title('განცხადება გამოქვეყნდა')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('უარყოფა')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('განცხადების უარყოფა')
                    ->visible(fn (PartListing $record): bool => $record->status !== 'rejected')
                    ->action(function (PartListing $record): void {
                        $record->update(['status' => 'rejected']);

                        Notification::make()
                            ->title('განცხადება უარყოფილია')
                            ->warning()
                            ->send();
                    }),

                EditAction::make()
                    ->label('რედაქტირება'),
            ]);
    }
}
