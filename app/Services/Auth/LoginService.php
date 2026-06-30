<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Enum\Status;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;

class LoginService
{
    /**
     * Retorna o usuário ATIVO correspondente ao e-mail, ou null.
     * A verificação de senha e o rate limiting ficam no controller
     * (auth()->attempt + trait ThrottlesLogins).
     */
    public function HasLogin(LoginRequest $request): ?User
    {
        return User::query()
            ->where('email', '=', $request->get('email'))
            ->where('status', '=', Status::ACTIVE)
            ->first();
    }
}
