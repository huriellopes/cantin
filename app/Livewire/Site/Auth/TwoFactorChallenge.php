<?php

declare(strict_types=1);

namespace App\Livewire\Site\Auth;

use App\Models\User;
use App\Support\TwoFactor;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

/**
 * Segunda etapa do login: pede o código TOTP (ou um código de recuperação)
 * do usuário que já passou por e-mail/senha e tem 2FA ativo. O id pendente
 * fica na sessão ('login.2fa'); nada é logado até o código conferir.
 */
#[Layout('components.layouts.app')]
#[Title('Verificação em duas etapas')]
class TwoFactorChallenge extends Component
{
    public string $code = '';

    public bool $useRecovery = false;

    public string $recovery_code = '';

    public function mount(): void
    {
        if (!session()->has('login.2fa')) {
            $this->redirectRoute('site.auth.login', navigate: true);
        }
    }

    public function toggleRecovery(): void
    {
        $this->useRecovery = !$this->useRecovery;
        $this->reset('code', 'recovery_code');
        $this->resetErrorBag();
    }

    public function verify(): void
    {
        /** @var array{id: int, remember: bool}|null $pending */
        $pending = session('login.2fa');

        if (!$pending) {
            $this->redirectRoute('site.auth.login', navigate: true);

            return;
        }

        $user = User::query()->find($pending['id']);

        if (!$user instanceof User) {
            session()->forget('login.2fa');
            $this->redirectRoute('site.auth.login', navigate: true);

            return;
        }

        $valid = $this->useRecovery
            ? $user->useRecoveryCode(mb_trim($this->recovery_code))
            : TwoFactor::verify((string) $user->two_factor_secret, $this->code);

        if (!$valid) {
            $this->addError($this->useRecovery ? 'recovery_code' : 'code', __('two_factor.invalid_code'));

            return;
        }

        session()->forget('login.2fa');
        Auth::loginUsingId($user->id, $pending['remember']);
        $user->forceFill(['last_login_at' => now()])->save();

        if ($user->password_change_required) {
            $this->redirectRoute('admin.password.change', navigate: true);

            return;
        }

        $this->redirectRoute($user->hasRole('admin', 'super-admin') ? 'admin.dashboard' : 'site.home', navigate: true);
    }

    public function render(): Factory|View
    {
        return view('livewire.site.pages.auth.two-factor-challenge');
    }
}
