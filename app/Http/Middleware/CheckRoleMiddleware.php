<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles) : Response
    {
        // Verifica se o usuário está autenticado
        if (!Auth::check()) {
            abort(403, 'Acesso não autorizado.');
        }

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
