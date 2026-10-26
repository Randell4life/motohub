<?php

namespace App\Filament\Resources\ServiceCenters\Tables;

use App\Models\ServiceCenter;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ServiceCentersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('სახელოსნო')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                TextColumn::make('owner.name')
                    ->label('მფლობელი')
                    ->searchable(),
                TextColumn::make('phone')
                    ->label('ტელეფონი')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('ელფოსტა')
                    ->searchable()
                    ->placeholder('მითითებული არ არის'),
                TextColumn::make('city')
                    ->label('ქალაქი')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('address')
                    ->label('მისამართი')
                    ->limit(35)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('description')
                    ->label('აღწერა')
                    ->limit(45)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('approval_status')
                    ->label('განაცხადის სტატუსი')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'approved' => 'დამტკიცებული',
                        'rejected' => 'უარყოფილი',
                        default => 'მოლოდინში',
                    })
                    ->color(fn ($state): string => match ($state) {
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'warning',
                    }),
                TextColumn::make('created_at')
                    ->label('რეგისტრაციის თარიღი')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('approval_status')
                    ->label('განაცხადის სტატუსი')
                    ->options([
                        'pending' => 'მოლოდინში',
                        'approved' => 'დამტკიცებული',
                        'rejected' => 'უარყოფილი',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('დამტკიცება')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('სახელოსნოს დამტკიცება')
                    ->modalDescription(fn (ServiceCenter $record): string => "დამტკიცდეს „{$record->name}“? ის გამოჩნდება საჯარო კატალოგში და მომხმარებლებს შეეძლებათ დაჯავშნა.")
                    ->visible(fn (ServiceCenter $record): bool => $record->approval_status !== 'approved' || ! $record->is_active)
                    ->action(function (ServiceCenter $record): void {
                        $record->update([
                            'approval_status' => 'approved',
                            'is_active' => true,
                            'is_verified' => true,
                        ]);

                        Notification::make()
                            ->title('სახელოსნო დამტკიცდა')
                            ->body('სახელოსნო საჯარო კატალოგში გამოჩნდება.')
                            ->success()
                            ->send();
                    }),
                Action::make('reject')
                    ->label('უარყოფა')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('სახელოსნოს განაცხადის უარყოფა')
                    ->modalDescription(fn (ServiceCenter $record): string => "უარყო „{$record->name}“-ის განაცხადი? სახელოსნო საჯარო კატალოგში აღარ გამოჩნდება.")
                    ->visible(fn (ServiceCenter $record): bool => $record->approval_status !== 'rejected' || $record->is_active)
                    ->action(function (ServiceCenter $record): void {
                        $record->update([
                            'approval_status' => 'rejected',
                            'is_active' => false,
                            'is_verified' => false,
                        ]);

                        Notification::make()
                            ->title('სახელოსნოს განაცხადი უარყოფილია')
                            ->danger()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
