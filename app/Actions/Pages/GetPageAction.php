<?php

namespace App\Actions\Pages;

use App\Enum\Status;
use App\Models\Page;
use Exception;

final class GetPageAction
{
    public static function handle(string $slug)
    {
        return Page::query()
            ->where('slug', '=', $slug)
            ->where('status', '=', Status::ACTIVE)
            ->first();
    }
}
