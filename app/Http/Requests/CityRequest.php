<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CityRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'state' => 'nullable|numeric|exists:states,id',
        ];
    }

    /**
     * @return string[]
     */
    public function messages() : array
    {
        return [
            'state.numeric' => 'O estado informado é inválido.',
            'state.exists' => 'O estado informado não existe.',
        ];
    }
}
