<?php

declare(strict_types=1);

namespace App\Livewire\Site\Components;

use App\Enum\Status;
use App\Models\StaticPage;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Footer extends Component
{
    public $logo;

    public object $static_pages;

    public function mount(): void
    {
        $this->logo = asset('assets/images/CANTIn.png');

        $this->static_pages = Cache::remember('static_pages_footer', 60 * 60 * 24, fn () => StaticPage::query()
            ->where('status', '=', Status::ACTIVE)
            ->get());
    }

    public function render(): Factory|View
    {
        return view('livewire.site.components.footer');
    }
}
