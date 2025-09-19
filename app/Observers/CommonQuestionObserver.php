<?php

namespace App\Observers;

use App\Models\CommonQuestion;
use Illuminate\Support\Facades\Cache;

class CommonQuestionObserver
{
    /**
     * Handle the CommonQuestion "created" event.
     */
    public function created(CommonQuestion $commonQuestion): void
    {
        Cache::forget('commons-questions-cantin');
    }

    /**
     * Handle the CommonQuestion "updated" event.
     */
    public function updated(CommonQuestion $commonQuestion): void
    {
        if ($commonQuestion->status === $commonQuestion->getOriginal('status')) {
            return;
        }

        Cache::forget('commons-questions-cantin');
    }

    /**
     * Handle the CommonQuestion "deleted" event.
     */
    public function deleted(CommonQuestion $commonQuestion): void
    {
        Cache::forget('commons-questions-cantin');
    }

    /**
     * Handle the CommonQuestion "restored" event.
     */
    public function restored(CommonQuestion $commonQuestion): void
    {
        Cache::forget('commons-questions-cantin');
    }

    /**
     * Handle the CommonQuestion "force deleted" event.
     */
    public function forceDeleted(CommonQuestion $commonQuestion): void
    {
        Cache::forget('commons-questions-cantin');
    }
}
