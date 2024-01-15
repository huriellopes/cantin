<?php

namespace App\Http\Requests\Terreiros;

use App\Archicture\Validates\Validate;
use Illuminate\Foundation\Http\FormRequest;

class SearchTerreiroForUFRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'state_id' => 'nullable|int|exists:states,id',
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return (new Validate())->getMessages();
    }
}
