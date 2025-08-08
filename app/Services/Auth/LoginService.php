<?php

namespace App\Services\Auth;

use App\Enum\Role;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Exception;

class LoginService
{
    /**
     * @param LoginRequest $request
     * @return User|null
     */
    public function HasLogin(LoginRequest $request) : ?User
    {
        $user = User::query()
            ->where('email', '=', $request->get('email'))
            ->first();

        if (!$user) {
            return $user;
        }

        $this->ensureIsNotRateLimited($request);

        if (!$user && !Hash::check($request->get('password'), $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        return $user;
    }

    public function HasRegister(RegisterRequest $request) : ?User
    {
        $validated = $request->validated();
        $userAlreadyExists = User::query()->where('email', '=', $validated['email'])->exists();

        if ($userAlreadyExists) {
            throw ValidationException::withMessages([
                'email' => [__('Email already exists')],
            ]);
        }

        try {
            $user = User::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']),
                'email' => $validated['email'],
                'email_verified_at' => Carbon::now(),
                'password' => Hash::make($validated['password']),
                'role_id' => Role::USER,
            ]);

            return $user;
        } catch (Exception $e) {
            Log::error('Error creating user: ' . $e->getMessage());
            return null;
        }
    }

    private function ensureIsNotRateLimited($request): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($request));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * @return string
     */
    private function throttleKey(): string
    {
        return Str::transliterate(Str::lower(request()->email).'|'.request()->ip);
    }
}
