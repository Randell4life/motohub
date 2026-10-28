<?php

namespace App\Filament\Resources\PartListings;

use App\Filament\Resources\PartListings\Pages\EditPartListing;
use App\Filament\Resources\PartListings\Pages\ListPartListings;
use App\Filament\Resources\PartListings\Schemas\PartListingForm;
use App\Filament\Resources\PartListings\Tables\PartListingsTable;
use App\Models\PartListing;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartListingResource extends Resource
{
    protected static ?string $model = PartListing::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $navigationLabel = 'מודერაცია ნაწილების';

    protected static ?string $modelLabel = 'ნაწილის განცხადება';

    protected static ?string $pluralModelLabel = 'ნაწილების განცხადებები';

    protected static string|\UnitEnum|null $navigationGroup = 'MotoHub Marketplace';

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Schema $schema): Schema
    {
        return PartListingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartListingsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartListings::route('/'),
            'edit' => EditPartListing::route('/{record}/edit'),
        ];
    }
}
