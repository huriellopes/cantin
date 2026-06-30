<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\ImpersonationLog;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ImpersonationLogsExport implements FromQuery, WithHeadings, WithMapping
{
    public function query(): Builder
    {
        return ImpersonationLog::query()
            ->with(['impersonator:id,name,email', 'impersonated:id,name,email'])
            ->orderByDesc('id');
    }

    /** @return array<int, string> */
    public function headings(): array
    {
        return ['ID', 'Personificador', 'E-mail', 'Personificado', 'E-mail', 'Ação', 'IP', 'Data'];
    }

    /**
     * @param  ImpersonationLog  $row
     * @return array<int, string|int|null>
     */
    public function map($row): array
    {
        return [
            $row->id,
            $row->impersonator?->name,
            $row->impersonator?->email,
            $row->impersonated?->name,
            $row->impersonated?->email,
            $row->action === 'started' ? 'Iniciou' : 'Encerrou',
            $row->ip,
            $row->created_at?->format('d/m/Y H:i:s'),
        ];
    }
}
