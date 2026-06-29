<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\StaticPage;
use Illuminate\Support\Facades\Cache;

class StaticPageObserver
{
    /**
     * Handle the StaticPage "created" event.
     */
    public function created(StaticPage $staticPage): void
    {
        Cache::forget('cantin-page-static-'.$staticPage->slug);
    }

    /**
     * Handle the StaticPage "updated" event.
     */
    public function updated(StaticPage $staticPage): void
    {
        Cache::forget('cantin-page-static-'.$staticPage->slug);
    }

    /**
     * Handle the StaticPage "deleted" event.
     */
    public function deleted(StaticPage $staticPage): void
    {
        Cache::forget('cantin-page-static-'.$staticPage->slug);
    }

    /**
     * Handle the StaticPage "restored" event.
     */
    public function restored(StaticPage $staticPage): void
    {
        Cache::forget('cantin-page-static-'.$staticPage->slug);
    }

    /**
     * Handle the StaticPage "force deleted" event.
     */
    public function forceDeleted(StaticPage $staticPage): void
    {
        Cache::forget('cantin-page-static-'.$staticPage->slug);
    }
}
