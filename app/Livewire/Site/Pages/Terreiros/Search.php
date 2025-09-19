<?php

namespace App\Livewire\Site\Pages\Terreiros;

use App\Models\State;
use App\Models\Terreiro;
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

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    /**
     * @return void
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.site.pages.terreiros.search', [
            'terreiros' => Terreiro::query()
                ->when($this->search, function ($query) {
                    $query->where('name', 'like', '%' . trim($this->search) . '%')
                        ->orWhereHas('address', function ($queryAddress) {
                            $queryAddress->whereHas('state', function ($queryState) {
                                $queryState->where('name', 'like', '%' . trim($this->search) . '%')
                                    ->orWhere('slug', '=', trim($this->search));
                            })->orWhereHas('city', function ($queryCity) {
                                $queryCity->where('name', 'like', '%' . trim($this->search) . '%');
                            });
                        });
                })->paginate(10)
        ]);
    }
}
