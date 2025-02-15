<?php

namespace App\Livewire\Cantin\Pages;

use App\Services\CommonQuestion\ListCommonQuestionService;
use Livewire\Component;

class Home extends Component
{
    public $commons;

    public function mount()
    {
        $this->commons = ListCommonQuestionService::list();
    }
    public function render()
    {
        return view('livewire.cantin.pages.home');
    }
}
