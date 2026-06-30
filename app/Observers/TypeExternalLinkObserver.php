<?php

declare(strict_types=1);

namespace App\Observers;

use App\Models\TypeExternalLink;

class TypeExternalLinkObserver
{
    /**
     * Handle the TypeExternalLink "created" event.
     */
    public function created(TypeExternalLink $typeExternalLink): void
    {
        //
    }

    /**
     * Handle the TypeExternalLink "updated" event.
     */
    public function updated(TypeExternalLink $typeExternalLink): void
    {
        //
    }

    /**
     * Handle the TypeExternalLink "deleted" event.
     */
    public function deleted(TypeExternalLink $typeExternalLink): void
    {
        //
    }

    /**
     * Handle the TypeExternalLink "restored" event.
     */
    public function restored(TypeExternalLink $typeExternalLink): void
    {
        //
    }

    /**
     * Handle the TypeExternalLink "force deleted" event.
     */
    public function forceDeleted(TypeExternalLink $typeExternalLink): void
    {
        //
    }
}
