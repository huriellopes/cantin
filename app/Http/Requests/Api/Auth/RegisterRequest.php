<?php

namespace App\Http\Requests\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules() : array
    {
        return [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|confirmed:password',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return [
            'name.required' => __('The name field is required.'),
            'name.string' => __('The name must be a string.'),
            'email.required' => __('The email field is required.'),
            'email.string' => __('The email must be a string.'),
            'email.email' => __('The email field is invalid.'),
            'email.unique' => __('The email already exists.'),
            'password.required' => __('The password field is required.'),
            'password.string' => __('The password must be a string.'),
            'password.confirmed' => __('The password confirmation does not match the password field.'),
        ];
    }
}
