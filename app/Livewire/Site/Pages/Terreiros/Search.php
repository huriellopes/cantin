<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages\Terreiros;

use App\Models\Terreiro;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public $states;

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render(): Factory|View
    {
        // Busca case-insensitive e portável (sqlite/pgsql): compara em minúsculas
        // dos dois lados — LOWER(coluna) LIKE %termo_minusculo%.
        $like = '%' . mb_strtolower(mb_trim($this->search)) . '%';

        return view('livewire.site.pages.terreiros.search', [
            'terreiros' => Terreiro::query()
                ->with(['nation:id,name', 'address.city:id,name', 'address.state:id,name,abbr'])
                ->when(mb_trim($this->search) !== '', function ($query) use ($like): void {
                    // Nome do terreiro, nome da liderança e localização (cidade,
                    // estado por nome ou UF). Agrupado para não vazar o OR.
                    $query->where(function ($q) use ($like): void {
                        $q->whereRaw('LOWER(name) LIKE ?', [$like])
                            ->orWhereRaw('LOWER(leadership_orunko) LIKE ?', [$like])
                            ->orWhereHas('nation', function ($queryNation) use ($like): void {
                                $queryNation->whereRaw('LOWER(name) LIKE ?', [$like]);
                            })
                            ->orWhereHas('address', function ($queryAddress) use ($like): void {
                                $queryAddress->whereHas('state', function ($queryState) use ($like): void {
                                    $queryState->whereRaw('LOWER(name) LIKE ?', [$like])
                                        ->orWhereRaw('LOWER(abbr) LIKE ?', [$like])
                                        ->orWhereRaw('LOWER(slug) LIKE ?', [$like]);
                                })->orWhereHas('city', function ($queryCity) use ($like): void {
                                    $queryCity->whereRaw('LOWER(name) LIKE ?', [$like]);
                                });
                            });
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
