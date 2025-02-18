<?php

namespace App\Livewire\Cantin\Pages;

use App\Enum\Status;
use App\Models\CommonQuestion;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class Home extends Component
{
    public $commons;

    /**
     * @return void
     */
    public function mount(): void
    {
        $this->commons = Cache::remember('commons', 600,function () {
            return CommonQuestion::query()
                ->select('id', 'answer','question')
                ->where('status', '=', Status::ACTIVE)
                ->get();
        });
    }
    public function render()
    {
        return view('livewire.cantin.pages.home');
    }
}
