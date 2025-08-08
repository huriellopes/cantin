<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use App\Traits\Utils;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    use Utils;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    public function messages() : array
    {
        return [
            'username.required' => 'O campo :attribute é obrigatório.',
            'username.string' => 'O campo :attribute é permitido apenas caracteres.',
        ];
    }

    /**
     * @return void
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        try {
            $login = $this->clear_tags(request()->input('username'));

            $this->validateEmail($login);

            $user = User::query()
                ->when(filter_var($login, FILTER_VALIDATE_EMAIL), function ($query) use ($login) {
                    $query->where('email', '=', $login);
                    }, function ($query) use ($login) {
                    $query->where('username', '=', $login);
                })->first();

            $this->ensureIsNotRateLimited();

            if(!Auth::attempt(['email' => $user->email, 'password' => request()->password]) ||
                !Auth::attempt(['username' => $user->username, 'password' => request()->password])) {
                RateLimiter::hit($this->throttleKey());

                throw ValidationException::withMessages([
                    'username' => trans('auth.failed'),
                ]);
            }

            RateLimiter::clear($this->throttleKey());
        } catch (\Exception $exception) {
            dd($exception->getMessage());
        }
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }
}
