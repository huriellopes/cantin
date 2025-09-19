<?php

namespace App\Observers;

use App\Models\Page;
use Illuminate\Support\Facades\Cache;

class PageObserver
{
    /**
     * Handle the Page "created" event.
     */
    public function created(Page $page): void
    {
        Cache::forget('pages_cantin');
    }

    /**
     * Handle the Page "updated" event.
     */
    public function updated(Page $page): void
    {
        Cache::forget('pages_cantin');
    }

    /**
     * Handle the Page "deleted" event.
     */
    public function deleted(Page $page): void
    {
        Cache::forget('pages_cantin');
    }

    /**
     * Handle the Page "restored" event.
     */
    public function restored(Page $page): void
    {
        Cache::forget('pages_cantin');
    }

    /**
     * Handle the Page "force deleted" event.
     */
    public function forceDeleted(Page $page): void
    {
        Cache::forget('pages_cantin');
    }
}
