<?php

namespace App\Filament\Resources\RentalBookings;

use App\Filament\Resources\RentalBookings\Pages\CreateRentalBooking;
use App\Filament\Resources\RentalBookings\Pages\EditRentalBooking;
use App\Filament\Resources\RentalBookings\Pages\ListRentalBookings;
use App\Filament\Resources\RentalBookings\Schemas\RentalBookingForm;
use App\Filament\Resources\RentalBookings\Tables\RentalBookingsTable;
use App\Models\RentalBooking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RentalBookingResource extends Resource
{
    protected static ?string $model = RentalBooking::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'php artisan tinker --execute="dump(\Illuminate\Support\Facades\Schema::hasTable(\'rental_scooters\')); dump(\Illuminate\Support\Facades\Schema::getColumnListing(\'rental_scooters\'));"';

    public static function form(Schema $schema): Schema
    {
        return RentalBookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RentalBookingsTable::configure($table);
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
            'index' => ListRentalBookings::route('/'),
            'create' => CreateRentalBooking::route('/create'),
            'edit' => EditRentalBooking::route('/{record}/edit'),
        ];
    }
}
