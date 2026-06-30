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
     * Encerra a personificação e retorna ao usuário original.
     */
    public function leave(): RedirectResponse
    {
        $impersonatorId = session('impersonator_id');

        if (!$impersonatorId) {
            return to_route('site.home');
        }

        $original = User::query()->find($impersonatorId);
        session()->forget('impersonator_id');

        if (!$original) {
            Auth::logout();

            return to_route('site.auth.login');
        }

        $this->log($original->id, (int) Auth::id(), 'stopped');
        Auth::login($original);

        return to_route('admin.users.index');
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
