<?php

namespace App\Filament\Resources\PartSales;

use App\Filament\Resources\PartSales\Pages\CreatePartSale;
use App\Filament\Resources\PartSales\Pages\ListPartSales;
use App\Filament\Resources\PartSales\Pages\ViewPartSale;
use App\Filament\Resources\PartSales\Schemas\PartSaleForm;
use App\Filament\Resources\PartSales\Schemas\PartSaleInfolist;
use App\Filament\Resources\PartSales\Pages\EditPartSale;
use App\Filament\Resources\PartSales\Tables\PartSalesTable;
use App\Models\PartSale;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartSaleResource extends Resource
{
    protected static ?string $model = PartSale::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedReceiptPercent;

    protected static ?string $recordTitleAttribute = 'part_title';

    protected static ?string $navigationLabel = 'გაყიდვები';

    protected static ?string $modelLabel = 'გაყიდვა';

    protected static ?string $pluralModelLabel = 'გაყიდვები';

    public static function form(Schema $schema): Schema
    {
        return PartSaleForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartSaleInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartSalesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

public static function getPages(): array
{
    return [
        'index' => ListPartSales::route('/'),
        'create' => CreatePartSale::route('/create'),
        'view' => ViewPartSale::route('/{record}'),
        'edit' => EditPartSale::route('/{record}/edit'),
    ];
}
}
