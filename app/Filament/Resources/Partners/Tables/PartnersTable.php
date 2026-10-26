<?php

namespace App\Filament\Resources\Partners\Tables;

use App\Models\Partner;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PartnersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('მომხმარებელი')
                    ->searchable(),

                TextColumn::make('name')
                    ->label('სახელოსნო / პარტნიორი')
                    ->searchable(),

                TextColumn::make('type')
                    ->label('ტიპი')
                    ->searchable(),

                TextColumn::make('application_status')
                    ->label('განაცხადის სტატუსი')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => match ($state) {
                        'pending' => 'მოლოდინში',
                        'approved' => 'დამტკიცებული',
                        'rejected' => 'უარყოფილი',
                        default => $state ?? 'უცნობი',
                    })
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('phone')
                    ->label('ტელეფონი')
                    ->searchable(),

                TextColumn::make('email')
                    ->label('ელფოსტა')
                    ->searchable(),

                TextColumn::make('address')
                    ->label('მისამართი')
                    ->searchable(),

                IconColumn::make('is_active')
                    ->label('აქტიურია')
                    ->boolean(),

                TextColumn::make('created_at')
                    ->label('შექმნილია')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('application_status')
                    ->label('განაცხადის სტატუსი')
                    ->options([
                        'pending' => 'მოლოდინში',
                        'approved' => 'დამტკიცებული',
                        'rejected' => 'უარყოფილი',
                    ]),

                SelectFilter::make('type')
                    ->label('ტიპი')
                    ->options([
                        'workshop' => 'სახელოსნო',
                    ]),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('დამტკიცება')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(fn (Partner $record): bool =>
                        $record->type === 'workshop'
                        && $record->application_status === 'pending'
                    )
                    ->action(function (Partner $record): void {
                        $record->update([
                            'application_status' => 'approved',
                            'is_active' => true,
                        ]);

                        Notification::make()
                            ->title('სახელოსნო დამტკიცებულია')
                            ->success()
                            ->send();
                    }),

                Action::make('reject')
                    ->label('უარყოფა')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(fn (Partner $record): bool =>
                        $record->type === 'workshop'
                        && $record->application_status === 'pending'
                    )
                    ->action(function (Partner $record): void {
                        $record->update([
                            'application_status' => 'rejected',
                            'is_active' => false,
                        ]);

                        Notification::make()
                            ->title('განაცხადი უარყოფილია')
                            ->warning()
                            ->send();
                    }),

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
