<?php

namespace App\Livewire\Site\Pages;

use App\Enum\Status;
use App\Models\ExternalLink;
use App\Models\TypeExternalLink;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class ExternalLinks extends Component
{
    use WithPagination;

    #[Url]
    public string $search = '';

    public $selectedLinkType = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'selectedLinkType' => ['except' => '', 'as' => 'type'],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function selectLinkType($TypeLinkSlug): void
    {
        $this->selectedLinkType = $TypeLinkSlug;
        $this->resetPage();
    }

    public function clearLinkType(): void
    {
        $this->selectedLinkType = null;
        $this->queryString = [];
        $this->resetPage();
    }

    public function render()
    {
        $types = Cache::remember('types_external_links_cantin', 60 * 60 * 24, function () {
            return TypeExternalLink::query()
                ->withCount('links')
                ->where('status', '=', Status::ACTIVE)
                ->orderBy('created_at', 'asc')
                ->get();
        });

        return view('livewire.site.pages.external-links', [
            'links' => Cache::remember('external_links_cantin'.$this?->selectedLinkType, 60 * 60 * 24, function () {
                return ExternalLink::query()
                    ->with(['type', 'user'])
                    ->when($this->search, function ($query) {
                        $query->where('title', 'like', '%'.$this->search.'%')
                            ->orWhere('description', 'like', '%'.$this->search.'%');
                    })
                    ->where('status', '=', Status::ACTIVE)
                    ->when($this->selectedLinkType, function ($query) {
                        $query->whereHas('type', function ($queryType) {
                            $queryType->where('slug', '=', $this->selectedLinkType);
                        });
                    })
                    ->whereNotNull('url')
                    ->orderBy('created_at', 'asc')
                    ->paginate(10);
            }),
            'types' => $types,
        ]);
    }
}
