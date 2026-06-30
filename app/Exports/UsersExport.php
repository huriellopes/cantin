<?php

declare(strict_types=1);

namespace App\Exports;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromQuery, WithHeadings, WithMapping
{
    public function query(): Builder
    {
        return User::query()->with('role')->orderBy('id');
    }

    /** @return array<int, string> */
    public function headings(): array
    {
        return ['ID', 'Nome', 'E-mail', 'Perfil', 'Status', 'Criado em'];
    }

    /**
     * @param  User  $user
     * @return array<int, string|int|null>
     */
    public function map($user): array
    {
        return [
            $user->id,
            $user->name,
            $user->email,
            $user->role_id?->label(),
            $user->status?->label(),
            $user->created_at?->format('d/m/Y H:i'),
        ];
    }
}
