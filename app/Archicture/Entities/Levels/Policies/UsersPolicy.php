<?php

namespace App\Archicture\Entities\Levels\Policies;

use App\Archicture\Entities\Levels\Enum\LevelEnum;
use App\Archicture\Entities\Users\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Auth;

class UsersPolicy
{
    use HandlesAuthorization;

    public function __construct(
        protected User $user,
    ){}

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user): bool
    {
        return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user): bool
    {
        return $user->level_id === LevelEnum::SUPER->value && Auth::user()->level_id === $user->level_id;
    }
}
