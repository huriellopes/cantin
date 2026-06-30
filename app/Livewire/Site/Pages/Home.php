<?php

declare(strict_types=1);

namespace App\Livewire\Site\Pages;

use App\Enum\Status;
use App\Models\CommonQuestion;
use App\Models\PartnerEntity;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public function render(): Factory|View
    {
        $partners = Cache::remember('partners-entities-cantin', 60 * 60 * 24, fn () => PartnerEntity::query()
            ->select('id', 'name', 'path_image')
            ->where('status', '=', Status::ACTIVE)
            ->orderBy('id', 'asc')
            ->get()
            ->values());

        // asset() não deve ser cacheado: a URL absoluta depende do scheme/host
        // da requisição e, se cacheada como http, quebra por mixed content em HTTPS.
        $image = asset('assets/images/new/background-outro.png');

        $commons = Cache::remember('commons-questions-cantin', 60 * 60 * 24, fn () => CommonQuestion::query()
            ->select('id', 'answer', 'question')
            ->where('status', '=', Status::ACTIVE)
            ->orderBy('id', 'asc')
            ->get()
            ->values());

        return view('livewire.site.pages.home', [
            'partners' => $partners,
            'image' => $image,
            'commons' => $commons,
        ]);
    }
}
