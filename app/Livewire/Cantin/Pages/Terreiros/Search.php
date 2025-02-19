<?php

namespace App\Livewire\Cantin\Pages\Terreiros;

use App\Models\State;
use App\Models\Terreiro;
use App\Services\States\ListStatesService;
use App\Services\Terreiros\SearchTerreiroForUFService;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class Search extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';
    public $states;

    /**
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * @param string|null $slug
     * @return void
     */
    public function mount(string $slug = null) : void
    {
        $this->search = $slug ?? request()->query('search', '');

        $this->states = Cache::remember('states_search', 60 * 60 * 24, function () {
            return State::query()
                ->select('id', 'name', 'slug')
                ->get();
        });
    }

    public function render()
    {
        return view('livewire.cantin.pages.terreiros.search', [
            'terreiros' => Terreiro::query()
                ->when($this->search, function ($query) {
                    $query->whereHas('address', function ($queryAddress) {
                        $queryAddress->whereHas('state', function ($queryState) {
                            $queryState->where('slug', '=', trim($this->search));
                        });
                    });
                })->paginate(10)
        ]);
    }
}
