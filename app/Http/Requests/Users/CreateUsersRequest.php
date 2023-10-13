<?php

namespace App\Http\Requests\Users;

use App\Archicture\Entities\Users\Validates\CreateUsersValidate;
use Illuminate\Foundation\Http\FormRequest;

class CreateUsersRequest extends FormRequest
{
    /**
     * @return bool
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
        return (new CreateUsersValidate())->getRules();
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return (new CreateUsersValidate())->getMessages();
    }
}
