<?php

namespace App\Filament\Resources\Partners\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PartnerForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('application_status')
    ->label('განაცხადის სტატუსი')
    ->options([
        'pending' => 'მოლოდინში',
        'approved' => 'დამტკიცებული',
        'rejected' => 'უარყოფილი',
    ])
    ->required()
    ->default('approved'),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                FileUpload::make('logo_path')
                    ->label('Partner logo')
                    ->image()
                    ->disk('public')
                    ->directory('partner-logos')
                    ->visibility('public')
                    ->imageEditor()
                    ->maxSize(2048),
                TextInput::make('name')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
Select::make('type')
    ->label('პარტნიორის ტიპი')
    ->options([
        'workshop' => 'მოტოციკლის სახელოსნო',
        'transport' => 'მოტოციკლის გადამზიდავი',
    ])
    ->required()
    ->default('workshop')
    ->native(false),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('address'),
                Textarea::make('description')
                    ->columnSpanFull(),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
