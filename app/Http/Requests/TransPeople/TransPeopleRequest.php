<?php

namespace App\Http\Requests\TransPeople;

use App\Validates\Validate;
use Illuminate\Foundation\Http\FormRequest;

class TransPeopleRequest extends FormRequest
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
            'state_id' => 'required|integer',
            'city_id' => 'required|integer'
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
