<?php

declare(strict_types=1);

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\ImpersonationLog;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ImpersonateController extends Controller
{
    /**
     * Inicia a personificação de um usuário (somente super admin).
     */
    public function start(User $user): RedirectResponse
    {
        $current = Auth::user();

        abort_unless($current?->isSuperAdmin() ?? false, 403);
        abort_if($user->id === $current->id, 403);

        // Já personificando? Mantém o impersonator original.
        $impersonatorId = session('impersonator_id', $current->id);

        $this->log((int) $impersonatorId, $user->id, 'started');

        session(['impersonator_id' => $impersonatorId]);
        Auth::login($user);

        return redirect()->route('site.home');
    }

    /**
     * Encerra a personificação e retorna ao usuário original.
     */
    public function leave(): RedirectResponse
    {
        $impersonatorId = session('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('site.home');
        }

        $original = User::find($impersonatorId);
        session()->forget('impersonator_id');

        if (!$original) {
            Auth::logout();

            return redirect()->route('site.auth.login');
        }

        $this->log($original->id, (int) Auth::id(), 'stopped');
        Auth::login($original);

        return redirect()->route('admin.users.index');
    }

    private function log(int $impersonatorId, int $impersonatedId, string $action): void
    {
        ImpersonationLog::query()->create([
            'impersonator_id' => $impersonatorId,
            'impersonated_id' => $impersonatedId,
            'action' => $action,
            'ip' => request()->ip(),
            'user_agent' => mb_substr((string) request()->userAgent(), 0, 255),
        ]);
    }
}
