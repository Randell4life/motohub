<?php

namespace App\Filament\Resources\PartRequests;

use App\Filament\Resources\PartRequests\Pages\CreatePartRequest;
use App\Filament\Resources\PartRequests\Pages\EditPartRequest;
use App\Filament\Resources\PartRequests\Pages\ListPartRequests;
use App\Filament\Resources\PartRequests\Pages\ViewPartRequest;
use App\Filament\Resources\PartRequests\Schemas\PartRequestForm;
use App\Filament\Resources\PartRequests\Schemas\PartRequestInfolist;
use App\Filament\Resources\PartRequests\Tables\PartRequestsTable;
use App\Models\PartRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PartRequestResource extends Resource
{
    protected static ?string $model = PartRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'part_name';

    protected static ?string $navigationLabel = 'ნაწილის მოთხოვნები';

    protected static ?string $modelLabel = 'ნაწილის მოთხოვნა';

    protected static ?string $pluralModelLabel = 'ნაწილის მოთხოვნები';

    protected static string|\UnitEnum|null $navigationGroup = 'MotoHub';

    public static function form(Schema $schema): Schema
    {
        return PartRequestForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return PartRequestInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PartRequestsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPartRequests::route('/'),
            'create' => CreatePartRequest::route('/create'),
            'view' => ViewPartRequest::route('/{record}'),
            'edit' => EditPartRequest::route('/{record}/edit'),
        ];
    }
}
