<?php

namespace App\Observers;

use App\Models\PartnerEntity;
use Illuminate\Support\Facades\Cache;

class PartnerEntityObserver
{
    /**
     * Handle the PartnerEntity "created" event.
     */
    public function created(PartnerEntity $partnerEntity): void
    {
        Cache::forget('partners-entities-cantin');
    }

    /**
     * Handle the PartnerEntity "updated" event.
     */
    public function updated(PartnerEntity $partnerEntity): void
    {
        Cache::forget('partners-entities-cantin');
    }

    /**
     * Handle the PartnerEntity "deleted" event.
     */
    public function deleted(PartnerEntity $partnerEntity): void
    {
        Cache::forget('partners-entities-cantin');
    }

    /**
     * Handle the PartnerEntity "restored" event.
     */
    public function restored(PartnerEntity $partnerEntity): void
    {
        Cache::forget('partners-entities-cantin');
    }

    /**
     * Handle the PartnerEntity "force deleted" event.
     */
    public function forceDeleted(PartnerEntity $partnerEntity): void
    {
        Cache::forget('partners-entities-cantin');
    }
}
