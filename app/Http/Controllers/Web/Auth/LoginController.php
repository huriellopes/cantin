<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\Auth\LoginService;
use App\Traits\ThrottlesLogins;
use App\Traits\Utils;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use ThrottlesLogins, Utils;

    public function login(LoginRequest $request): ?RedirectResponse
    {
        // Anti força-bruta: bloqueia após muitas tentativas (lança e o Laravel
        // redireciona com a mensagem de throttle).
        $this->ensureIsNotRateLimited($request);

        if (empty($request->email) || empty($request->password)) {
            return back()
                ->withInput()
                ->withErrors(['message' => 'Preencha todos os campos!']);
        }

        try {
            $user = resolve(LoginService::class)->HasLogin($request);

            if (!$user) {
                $this->incrementLoginAttempts($request);

                return back()
                    ->withInput()
                    ->withErrors(['message' => 'Usuário não encontrado!']);
            }

            if (auth()->attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
                // Sucesso: zera o contador de tentativas.
                $this->clearLoginAttempts($request);

                // 2FA ativo: não conclui o login; guarda o pendente e desafia.
                if (auth()->user()->hasTwoFactorEnabled()) {
                    $pendingId = auth()->id();
                    $remember = $request->boolean('remember');

                    auth()->logout();

                    $request->session()->put('login.2fa', ['id' => $pendingId, 'remember' => $remember]);

                    return to_route('site.auth.two-factor');
                }

                // Registra o último acesso.
                auth()->user()->forceFill(['last_login_at' => now()])->save();

                // Senha padrão / temporária: obriga a troca antes de seguir.
                if (auth()->user()->password_change_required) {
                    return to_route('admin.password.change');
                }

                if (auth()->user()->hasRole('admin', 'super-admin')) {
                    return to_route('admin.dashboard');
                }

                return to_route('site.home');
            }

            // Credenciais inválidas: conta a tentativa.
            $this->incrementLoginAttempts($request);

            return back()
                ->withInput()
                ->withErrors(['message' => 'Credenciais inválidas!']);
        } catch (Exception $e) {
            self::botCantinbr($e);
            Log::error('Erro durante o login: ' . $e->getMessage());

            return back()
                ->withInput()
                ->withErrors(['message' => 'Erro ao tentar fazer login!']);
        }
    }
}
