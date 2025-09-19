<?php

namespace App\Filament\Exports;

use App\Models\Terreiro;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class TerreiroExporter extends Exporter
{
    protected static ?string $model = Terreiro::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')
                ->label('Nome'),
            ExportColumn::make('phone')
                ->formatStateUsing(fn ($state) => '+55 ' . substr($state, 2, 11))
                ->label('Telefone'),
            ExportColumn::make('nation.name')->label('Nação'),
            ExportColumn::make('address.zipcode')->label('CEP'),
            ExportColumn::make('address.address')->label('Endereço'),
            ExportColumn::make('leadership_orunko')
                ->label('Orukó da Liderança'),
            ExportColumn::make('color_of_leadership')
                ->label('Cor da Liderança'),
            ExportColumn::make('created_at')
                ->formatStateUsing(fn ($state) => $state->format('d/m/Y H:i:s'))
                ->label('Criado em'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your terreiro export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
