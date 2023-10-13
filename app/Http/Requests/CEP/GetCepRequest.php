<?php

namespace App\Http\Requests\CEP;

use App\Archicture\Entities\CEP\Validate\GetCepValidate;
use Illuminate\Foundation\Http\FormRequest;

class GetCepRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return (new GetCepValidate())->getRules();
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return (new GetCepValidate())->getMessages();
    }
}
