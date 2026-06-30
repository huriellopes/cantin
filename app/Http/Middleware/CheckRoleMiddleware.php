<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Verifica se o usuário está autenticado
        abort_unless(Auth::check(), 403, 'Acesso não autorizado.');

        $user = Auth::user();

        // Verifica se o usuário tem pelo menos uma das roles especificadas
        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return $next($request);
            }
        }

        // Se o usuário não tiver nenhuma das roles, retorna um erro 403
        abort(403, 'Ação não autorizada.');
    }
}
