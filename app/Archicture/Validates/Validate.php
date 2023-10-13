<?php

namespace App\Archicture\Validates;

use App\Exceptions\SystemException;
use App\Traits\Requests;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class Validate
{
    use Requests;

    public array $rules = [];

    public array $messages = [
        'required' => 'O campo :attribute é obrigatório.',
        'integer' => 'O campo :attribute é permitido apenas caracteres numéricos.',
        'float' => 'O campo :attribute é permitido apenas caracteres com casas decimais.',
        'boolean' => 'O campo :attribute é permitido apenas true ou false.',
        'array' => 'O campo :attribute é permitido apenas array.',
        'string' => 'O campo :attribute é permitido apenas caracteres.'
    ];

    /**
     * @param object $params
     * @param string|null $message
     * @return void
     * @throws Throwable
     */
    public function validaParametros(object $params, string $message = null) : void
    {
        $validator = Validator::make((array) $params, $this->getRules(), $this->getMessages());

        if ($validator->fails()) {
            $this->shootExeception(new ValidationException($validator), $message);
        }
    }

    /**
     * @param int $id
     * @return bool
     * @throws SystemException
     */
    public function validateInt(int $id): bool
    {
        if (!is_int($id)) {
            throw new SystemException('Error validating integer.');
        }

        return true;
    }

    /**
     * @return array
     */
    public function getRules() : array
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function getMessages() : array
    {
        return $this->messages;
    }
}
