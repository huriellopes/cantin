<?php

namespace App\Http\DTO\Users;

use Spatie\LaravelData\Data;

class CreateUsersDTO extends Data
{
    /**
     * @param string $name
     * @param string $email
     * @param int $level_id
     */
    public function __construct(
        public string $name,
        public string $email,
        public int $level_id,
    ){}
}
