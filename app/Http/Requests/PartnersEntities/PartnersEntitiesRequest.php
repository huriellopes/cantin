<?php

namespace App\Http\Requests\PartnersEntities;

use App\Validates\Validate;
use Illuminate\Foundation\Http\FormRequest;

class PartnersEntitiesRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'zipcode' => 'required|string',
            'address' => 'required|string',
            'number' => 'required|string',
            'complement' => 'nullable|string',
            'neighborhood' => 'required|string',
            'state_id' => 'required|int',
            'city_id' => 'required|int',
            'activity_carried_out' => 'required|string',
        ];
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return (new Validate())->getMessages();
    }
}
