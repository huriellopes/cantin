<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages;

use App\Enum\Status;
use App\Models\StaticPage as StaticPageModel;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class StaticPage extends Component
{
    public function render()
    {
        $page = Cache::remember('cantin-page-static-' . request()->route('staticPage'), 60 * 60 * 24, function () {
            return StaticPageModel::query()
                ->where('slug', '=', request()->route('staticPage'))
                ->where('status', '=', Status::ACTIVE)
                ->first();
        });

        return view('livewire.site.pages.static-page', [
            'page' => $page,
        ]);
    }
}
