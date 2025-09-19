<?php

namespace App\Filament\Admin\Resources\StaticPages;

use App\Filament\Admin\Resources\StaticPages\Pages\ListStaticPages;
use App\Filament\Admin\Resources\StaticPages\Schemas\StaticPageForm;
use App\Filament\Admin\Resources\StaticPages\Tables\StaticPagesTable;
use App\Models\StaticPage;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class StaticPageResource extends Resource
{
    protected static ?string $model = StaticPage::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::GlobeAlt;

    protected static ?string $navigationLabel = 'Páginas Estáticas';

    protected static ?string $modelLabel = 'Página Estática';

    protected static string | UnitEnum | null $navigationGroup = 'Site';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return StaticPageForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return StaticPagesTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStaticPages::route('/'),
        ];
    }
}
