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
        $term = mb_trim($this->search);

        return view('livewire.site.pages.terreiros.search', [
            'terreiros' => Terreiro::query()
                ->with(['nation:id,name', 'address.city:id,name', 'address.state:id,name,abbr'])
                ->when($term !== '', function ($query) use ($term): void {
                    // Busca por: nome do terreiro, nome da liderança e localização
                    // (cidade, estado por nome ou UF). Agrupado para não vazar o OR.
                    $query->where(function ($q) use ($term): void {
                        $q->where('name', 'like', "%{$term}%")
                            ->orWhere('leadership_orunko', 'like', "%{$term}%")
                            ->orWhereHas('address', function ($queryAddress) use ($term): void {
                                $queryAddress->whereHas('state', function ($queryState) use ($term): void {
                                    $queryState->where('name', 'like', "%{$term}%")
                                        ->orWhere('abbr', 'like', "%{$term}%")
                                        ->orWhere('slug', 'like', "%{$term}%");
                                })->orWhereHas('city', function ($queryCity) use ($term): void {
                                    $queryCity->where('name', 'like', "%{$term}%");
                                });
                            });
                    });
                })
                ->latest()
                ->paginate(10),
        ]);
    }
}
