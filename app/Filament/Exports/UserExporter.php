<?php

namespace App\Filament\Exports;

use App\Models\User;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;
use Illuminate\Support\Number;

class UserExporter extends Exporter
{
    protected static ?string $model = User::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('name')->label('Nome'),
            ExportColumn::make('email')
                ->label('E-mail'),
            ExportColumn::make('role.name')
                ->formatStateUsing(fn ($state) => $state->label())
                ->label('Perfil'),
            ExportColumn::make('status')
                ->formatStateUsing(fn ($state) => $state->label())
                ->label('Status'),
            ExportColumn::make('created_at')
                ->formatStateUsing(fn ($state) => $state->format('d/m/Y H:i:s'))
                ->label('Criado em'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your user export has completed and ' . Number::format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . Number::format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
