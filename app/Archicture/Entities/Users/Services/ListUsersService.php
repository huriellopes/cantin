<?php

namespace App\Archicture\Entities\Users\Services;

use App\Archicture\Entities\Users\Interfaces\IListUsersService;
use App\Archicture\Entities\Users\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class ListUsersService implements IListUsersService
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
