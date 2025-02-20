<?php

namespace App\Http\Middleware;

use App\Models\Visit;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VisitsRegister
{
    /**
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        Visit::create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'url' => $request->url(),
            'page' => $request->path() === "/" ? "home" : $request->path(),
            'referer' => $request->header('referer'),
            'visited_at' => Carbon::now(),
        ]);

        return $next($request);
    }
}
