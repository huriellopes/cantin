<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Override;

class Authenticate extends Middleware
{
    #[Override]
    protected function redirectTo(Request $request)
    {
        if (!$request->expectsJson()) {
            return route('site.auth.login');
        }

        return route('site.auth.login');
    }
}
