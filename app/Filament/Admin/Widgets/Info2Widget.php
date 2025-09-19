<?php

namespace App\Filament\Admin\Widgets;

use App\Enum\Role;
use App\Enum\Status;
use App\Models\PartnerEntity;
use App\Models\TransPeople;
use App\Models\User;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class Info2Widget extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Usuários', $this->getUsers())
                ->color('success')
                ->description('Quantidade de usuários cadastrados')
                ->icon('heroicon-o-users')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Entidades Parceiras', $this->getPartnerEntities())
                ->color('success')
                ->description('Quantidade de entidades parceiras cadastradas')
                ->icon('heroicon-m-users')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),

            Stat::make('Pessoas Trans', $this->getTransPeople())
                ->color('success')
                ->description('Quantidade de pessoas trans cadastradas')
                ->icon('heroicon-m-users')
                ->descriptionIcon('heroicon-m-arrow-trending-up', IconPosition::Before),
        ];
    }

    private function getUsers() : int
    {
        $users = User::query();
        if (auth()->user()->hasRole('super-admin')) {
            $users->where('id', '!=', auth()->id());
        } else {
            $users->where('id', '!=', auth()->id())
                ->where('role_id', '=', Role::USER);
        }

        return $users->count();
    }

    private function getPartnerEntities() : int
    {
        return PartnerEntity::query()
            ->where('status', '=', Status::ACTIVE)
            ->count();
    }

    private function getTransPeople() : int
    {
        return TransPeople::query()
            ->count();
    }
}
