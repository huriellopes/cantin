<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Define o idioma da aplicação a partir da sessão (com fallback no padrão).
     *
     * @param  Closure(Request):Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $available = array_keys(config('app.available_locales', []));
        $locale = $request->session()->get('locale', config('app.locale'));

        if (in_array($locale, $available, true)) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
