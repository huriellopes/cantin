<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Visit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Symfony\Component\HttpFoundation\Response;

class VisitsRegister
{
    public function handle(Request $request, Closure $next): Response
    {
        Visit::query()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'page' => $request->path() === '/' ? 'home' : $request->path(),
            'referer' => $request->header('referer'),
            'visited_at' => Date::now(),
        ]);

        return $next($request);
    }
}
