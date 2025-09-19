<?php

namespace App\Livewire\Site\Pages;

use App\Enum\Status;
use App\Models\CommonQuestion;
use App\Models\PartnerEntity;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public function render()
    {
        $partners = Cache::remember('partners-entities-cantin', 60 * 60 * 24, function () {
            return PartnerEntity::query()
                ->select('id', 'name', 'path_image')
                ->where('status', '=', Status::ACTIVE)
                ->orderBy('id', 'asc')
                ->get()
                ->values();
        });

        $image = Cache::remember('cantin-home', 60 * 60 * 24, function () {
            return asset('/assets/images/new/background-outro.png');
        });

        $commons = Cache::remember('commons-questions-cantin', 60 * 60 * 24, function () {
            return CommonQuestion::query()
                ->select('id', 'answer','question')
                ->where('status', '=', Status::ACTIVE)
                ->orderBy('id', 'asc')
                ->get()
                ->values();
        });

        return view('livewire.site.pages.home', [
            'partners' => $partners,
            'image' => $image,
            'commons' => $commons,
        ]);
    }
}
