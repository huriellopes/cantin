<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Override;

class CityRequest extends FormRequest
{
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
            'state' => ['nullable', 'numeric', 'exists:states,id'],
        ];
    }

    /**
     * @return string[]
     */
    #[Override]
    public function messages(): array
    {
        return [
            'state.numeric' => 'O estado informado é inválido.',
            'state.exists' => 'O estado informado não existe.',
        ];
    }
}
