<?php

namespace App\Services\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ListUsersService
{
    /**
     * @return Collection
     */
    public function list(): Collection
    {
        return User::withTrashed()
            ->with('level')
            ->where('id', '<>', Auth::user()->id)
            ->get();
    }
}
