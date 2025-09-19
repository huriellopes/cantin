<?php

namespace App\Services\Auth;

use App\Enum\Role;
use App\Enum\Status;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Str;

class RegisterService
{
    /**
     * @param RegisterRequest $request
     * @return User
     */
    public function store(RegisterRequest $request) : User
    {
        return User::create([
            ...$request->validated(),
            'username' => Str::slug($request->get('name')),
            'slug' => Str::slug($request->get('name')),
            'role_id' => Role::USER,
            'status' => Status::ACTIVE
        ]);
    }
}
