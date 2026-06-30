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
        return view('livewire.site.pages.terreiros.search', [
            'terreiros' => Terreiro::query()
                ->when($this->search, function ($query): void {
                    $query->where('name', 'like', '%' . mb_trim($this->search) . '%')
                        ->orWhereHas('address', function ($queryAddress): void {
                            $queryAddress->whereHas('state', function ($queryState): void {
                                $queryState->where('name', 'like', '%' . mb_trim($this->search) . '%')
                                    ->orWhere('slug', '=', mb_trim($this->search));
                            })->orWhereHas('city', function ($queryCity): void {
                                $queryCity->where('name', 'like', '%' . mb_trim($this->search) . '%');
                            });
                        });
                })->paginate(10),
        ]);
    }
}
