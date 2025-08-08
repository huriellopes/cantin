<?php

namespace App\Http\Controllers\Web\Auth;

use App\Enum\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Services\Auth\LoginService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $user = app(LoginService::class)->HasLogin($request);

        if (!$user) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['email' => 'Usuário não encontrado!']);
        }

        if (auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin')) {
                return redirect()->route('filament.admin.pages.dashboard');
            } else {
                return redirect()->route('filament.userCommon.pages.dashboard');
            }
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['password' => 'Senha incorreta!']);
        }
    }

    public function register(RegisterRequest $request)
    {
        try {
            $user = app(LoginService::class)->HasRegister($request);

            if ($user) {
                Auth::login($user);

                return redirect()->route('filament.userCommon.pages.dashboard');
            }
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            Log::error('Erro durante o cadastro: ' . $e->getMessage());
        }

        return back()
            ->withInput()
            ->with('error', 'Ocorreu um erro ao tentar criar sua conta.');
    }
}
