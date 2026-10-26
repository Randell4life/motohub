<?php

namespace App\Filament\Resources\RentalScooters;

use App\Filament\Resources\RentalScooters\Pages\CreateRentalScooter;
use App\Filament\Resources\RentalScooters\Pages\EditRentalScooter;
use App\Filament\Resources\RentalScooters\Pages\ListRentalScooters;
use App\Filament\Resources\RentalScooters\Pages\ViewRentalScooter;
use App\Filament\Resources\RentalScooters\Schemas\RentalScooterForm;
use App\Filament\Resources\RentalScooters\Schemas\RentalScooterInfolist;
use App\Filament\Resources\RentalScooters\Tables\RentalScootersTable;
use App\Models\RentalScooter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RentalScooterResource extends Resource
{
    protected static ?string $model = RentalScooter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'Garage33';

    public static function form(Schema $schema): Schema
    {
        return RentalScooterForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RentalScooterInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RentalScootersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRentalScooters::route('/'),
            'create' => CreateRentalScooter::route('/create'),
            'view' => ViewRentalScooter::route('/{record}'),
            'edit' => EditRentalScooter::route('/{record}/edit'),
        ];
    }
}
