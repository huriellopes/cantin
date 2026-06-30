<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckImpersonateMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($id = session()->get('impersonate_as')) {
            Auth::onceUsingId(['id' => $id]);
            session()->regenerate();
        }

        return $next($request);
    }
}
