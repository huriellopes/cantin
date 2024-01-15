<?php

namespace App\Http\Requests\Partners;

use App\Archicture\Entities\Partners\Validates\PartnersValidate;
use Illuminate\Foundation\Http\FormRequest;

class PartnersRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return true;
//    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return (new PartnersValidate())->getRules();
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return (new PartnersValidate())->getMessages();
    }
}
