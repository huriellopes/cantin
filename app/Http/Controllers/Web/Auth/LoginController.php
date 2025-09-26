<?php

namespace App\Http\Controllers\Web\Auth;

use Illuminate\Http\RedirectResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\LoginService;
use App\Traits\Utils;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use Utils;

    public function login(LoginRequest $request): ?RedirectResponse
    {
        try {
            if (empty($request->email) || empty($request->password)) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['message' => 'Preencha todos os campos!']);
            }

            $user = app(LoginService::class)->HasLogin($request);

            if (!$user) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors(['message' => 'Usuário não encontrado!']);
            }

            if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
                if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')) {
                    return redirect()->route('filament.admin.pages.dashboard');
                }

                return redirect()->route('filament.userCommon.pages.dashboard');
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => 'Credenciais inválidas!']);
        } catch (Exception $e) {
            self::botCantinbr($e, null);
            Log::error('Erro durante o login: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['message' => 'Erro ao tentar fazer login!']);
        }
    }
}
