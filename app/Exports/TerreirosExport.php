<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\Terreiro;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TerreirosExport implements FromQuery, WithHeadings, WithMapping
{
    public function query(): Builder
    {
        return Terreiro::query()->with(['nation', 'address'])->orderBy('id');
    }

    /** @return array<int, string> */
    public function headings(): array
    {
        return ['ID', 'Nome', 'Telefone', 'Nação', 'CEP', 'Endereço', 'Liderança', 'Cor da liderança', 'Criado em'];
    }

    /**
     * @param  Terreiro  $terreiro
     * @return array<int, string|int|null>
     */
    public function map($terreiro): array
    {
        return [
            $terreiro->id,
            $terreiro->name,
            $terreiro->phone,
            $terreiro->nation?->name,
            $terreiro->address?->zipcode,
            $terreiro->address?->address,
            $terreiro->leadership_orunko,
            $terreiro->color_of_leadership,
            $terreiro->created_at?->format('d/m/Y H:i'),
        ];
    }
}
