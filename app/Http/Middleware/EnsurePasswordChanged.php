<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bloqueia o acesso enquanto o usuário precisar trocar a senha (ex.: criado
 * com a senha padrão pelo super-admin), redirecionando para a página de troca.
 * A própria página de troca e o logout ficam liberados para evitar loop.
 */
class EnsurePasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (
            $user
            && $user->password_change_required
            && !$request->routeIs('admin.password.change')
            && !$request->routeIs('admin.logout')
        ) {
            return to_route('admin.password.change');
        }

        return $next($request);
    }
}
