<?php

namespace App\Filament\Resources\ServiceCenters\Tables;

use App\Models\ServiceCenter;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
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
                TextColumn::make('subscription_plan')
                    ->label('პაკეტი')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => strtoupper((string) $state)),
                TextColumn::make('subscription_price')
                    ->label('ფასი / თვე')
                    ->money('GEL')
                    ->sortable(),
                TextColumn::make('subscription_requested_plan')
                    ->label('მოთხოვნილი პაკეტი')
                    ->badge()
                    ->placeholder('—')
                    ->formatStateUsing(fn ($state): string => $state ? strtoupper((string) $state) : '—'),
                TextColumn::make('subscription_requested_at')
                    ->label('მოთხოვნის თარიღი')
                    ->dateTime('d.m.Y H:i')
                    ->placeholder('—'),
                TextColumn::make('subscription_status')
                    ->label('გამოწერის სტატუსი')
                    ->badge()
                    ->formatStateUsing(fn ($state): string => match ($state) {
                        'active' => 'აქტიური',
                        'pending_payment' => 'გადახდის მოლოდინში',
                        'past_due' => 'გადახდა ვადაგადაცილებულია',
                        'expired' => 'ვადაგასული',
                        'cancelled' => 'გაუქმებული',
                        default => 'უცნობი',
                    })
                    ->color(fn ($state): string => $state === 'active' ? 'success' : 'warning'),
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
                Action::make('subscription')
                    ->label('პაკეტის მართვა')
                    ->icon('heroicon-o-credit-card')
                    ->color('info')
                    ->fillForm(fn (ServiceCenter $record): array => [
                        'subscription_plan' => $record->subscription_plan ?: 'start',
                        'subscription_status' => $record->subscription_status ?: 'active',
                        'subscription_requested_plan' => $record->subscription_requested_plan,
                        'subscription_request_note' => $record->subscription_request_note,
                    ])
                    ->form([
                        Select::make('subscription_plan')
                            ->label('პაკეტი')
                            ->options(collect(ServiceCenter::subscriptionPlans())->mapWithKeys(fn ($plan, $key) => [$key => $plan['name'] . ' — ' . $plan['price'] . ' ₾ / თვე'])->all())
                            ->required(),
                        Textarea::make('subscription_request_note')->label('მოთხოვნის შენიშვნა')->disabled()->dehydrated(false),
                        Select::make('subscription_status')
                            ->label('სტატუსი')
                            ->options([
                                'active' => 'აქტიური',
                                'pending_payment' => 'გადახდის მოლოდინში',
                                'past_due' => 'გადახდა ვადაგადაცილებულია',
                                'expired' => 'ვადაგასული',
                                'cancelled' => 'გაუქმებული',
                            ])
                            ->required(),
                    ])
                    ->action(function (ServiceCenter $record, array $data): void {
                        $plan = ServiceCenter::subscriptionPlans()[$data['subscription_plan']];
                        $record->update([
                            'subscription_plan' => $data['subscription_plan'],
                            'subscription_status' => $data['subscription_status'],
                            'subscription_price' => $plan['price'],
                            'subscription_started_at' => $data['subscription_status'] === 'active' ? now() : $record->subscription_started_at,
                            'subscription_ends_at' => $data['subscription_status'] === 'active' && $plan['price'] > 0 ? now()->addMonth() : null,
                            'subscription_requested_plan' => null,
                            'subscription_request_note' => null,
                            'subscription_requested_at' => null,
                        ]);
                        Notification::make()->title('გამოწერა განახლდა')->success()->send();
                    }),
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
