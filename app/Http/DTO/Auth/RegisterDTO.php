<?php

namespace App\Http\DTO\Auth;

use Spatie\LaravelData\Dto;

class RegisterDTO extends Dto
{
    public function __construct(
        public string $name,
        public string $email,
        public string $password,
//        public string $password_confirmation,
    ){}
}
