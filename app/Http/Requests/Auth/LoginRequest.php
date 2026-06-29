<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * @return array[]
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * @return string[]
     */
    #[\Override]
    public function messages(): array
    {
        return [
            'email.required' => 'O campo :attribute é obrigatório.',
            'email.string' => 'O campo :attribute é permitido apenas caracteres.',
            'email.email' => 'O campo :attribute é inválido.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.string' => 'O campo senha é permitido apenas caracteres.',
        ];
    }
}
