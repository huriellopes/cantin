<?php

namespace App\Livewire\Cantin\Pages;

use App\Models\CommonQuestion;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public object $commons;

    public string $image;

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->commons = Cache::remember('commons', 60 * 60 * 24,function () {
            return CommonQuestion::query()
                ->select('id', 'answer','question')
                ->active()
                ->get();
        });

        $this->image = Cache::remember('cantin-home', 60 * 60 * 24, function () {
            return asset('/assets/images/new/background-outro.png');
        });
    }
    public function render()
    {
        return view('livewire.cantin.pages.home');
    }
}
