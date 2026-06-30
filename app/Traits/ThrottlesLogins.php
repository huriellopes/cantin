<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * Rate limiting de tentativas de login (anti força-bruta). A chave combina o
 * e-mail informado + IP, então um atacante não trava a conta de terceiros
 * apenas pelo e-mail. Fluxo de uso no controller:
 *
 *   $this->ensureIsNotRateLimited($request);   // antes de tentar autenticar
 *   ... em falha:  $this->incrementLoginAttempts($request);
 *   ... em sucesso: $this->clearLoginAttempts($request);
 */
trait ThrottlesLogins
{
    /** Máximo de tentativas antes do bloqueio temporário. */
    protected int $maxLoginAttempts = 5;

    /** Janela do bloqueio, em segundos. */
    protected int $loginDecaySeconds = 60;

    protected function loginThrottleKey(Request $request): string
    {
        return Str::transliterate(Str::lower((string) $request->input('email')) . '|' . $request->ip());
    }

    /**
     * Lança ValidationException (com mensagem de throttle) se excedeu o limite.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!RateLimiter::tooManyAttempts($this->loginThrottleKey($request), $this->maxLoginAttempts)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->loginThrottleKey($request));

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ]),
        ]);
    }

    /** Registra uma tentativa falha. */
    protected function incrementLoginAttempts(Request $request): void
    {
        RateLimiter::hit($this->loginThrottleKey($request), $this->loginDecaySeconds);
    }

    /** Zera o contador após login bem-sucedido. */
    protected function clearLoginAttempts(Request $request): void
    {
        RateLimiter::clear($this->loginThrottleKey($request));
    }
}
