<?php

namespace App\Livewire\Admin;

use App\Enum\Status;
use App\Models\Comment;
use App\Models\PartnerEntity;
use App\Models\Terreiro;
use App\Models\TransPeople;
use App\Models\User;
use App\Models\Visit;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.admin')]
#[Title('Painel')]
class Dashboard extends Component
{
    public function render()
    {
        $stats = [
            ['label' => 'Visitas no site', 'value' => Visit::count(), 'icon' => 'eye', 'color' => 'sky'],
            ['label' => 'Terreiros', 'value' => Terreiro::count(), 'icon' => 'home', 'color' => 'violet'],
            ['label' => 'Comentários', 'value' => Comment::query()->whereNull('parent_id')->count(), 'icon' => 'chat', 'color' => 'amber'],
            ['label' => 'Usuários', 'value' => User::count(), 'icon' => 'users', 'color' => 'emerald'],
            ['label' => 'Entidades parceiras', 'value' => PartnerEntity::query()->where('status', Status::ACTIVE)->count(), 'icon' => 'star', 'color' => 'rose'],
            ['label' => 'Pessoas trans', 'value' => TransPeople::count(), 'icon' => 'user', 'color' => 'indigo'],
        ];

        return view('livewire.admin.dashboard', [
            'stats' => $stats,
            'recentTerreiros' => Terreiro::query()->latest()->take(6)->get(['id', 'name', 'created_at']),
        ]);
    }
}
