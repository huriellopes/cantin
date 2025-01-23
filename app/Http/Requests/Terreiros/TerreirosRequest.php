<?php

namespace App\Http\Requests\Terreiros;

use App\Validates\Validate;
use Illuminate\Foundation\Http\FormRequest;

class TerreirosRequest extends FormRequest
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
            'phone' => 'required|string',
            'fundationed_at' => 'required|date',
            'nation_terreiro_id' => 'required|integer',
            'leadership_orunko' => 'required|string',
            'color_of_leadership' => 'required|string',
            'zipcode' => 'required|string',
            'address' => 'required|string',
            'complement' => 'nullable|string',
            'number' => 'required|string',
            'neighborhood' => 'required|string',
            'state_id' => 'required|string',
            'city_id' => 'required|string',
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
