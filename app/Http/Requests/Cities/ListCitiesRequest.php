<?php

namespace App\Http\Requests\Cities;

use App\Validates\ListCitiesValidate;
use Illuminate\Foundation\Http\FormRequest;

class ListCitiesRequest extends FormRequest
{
    /**
     * @return bool
     */
    public function authorize() : bool
    {
        return true;
    }

    /**
     * @return array
     */
    public function rules() : array
    {
        return (new ListCitiesValidate())->getRules();
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return (new ListCitiesValidate())->getMessages();
    }
}
