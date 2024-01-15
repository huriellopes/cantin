<?php

namespace App\Http\Requests\Terreiros;

use App\Archicture\Entities\TerreirosQuestions\Validates\QuestionValidate;
use Illuminate\Foundation\Http\FormRequest;

class QuestionRequest extends FormRequest
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
        return (new QuestionValidate())->getRules();
    }

    /**
     * @return array
     */
    public function messages() : array
    {
        return (new QuestionValidate())->getMessages();
    }
}
