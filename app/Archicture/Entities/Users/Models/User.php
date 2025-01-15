<?php

namespace App\Archicture\Entities\Users\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Archicture\Entities\Levels\Enum\LevelEnum;
use App\Archicture\Entities\Levels\Models\Level;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property $name
 * @property $username
 * @property $email
 * @property $level_id
 * @property $password
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function casts(): array
    {
        return [
//            'level_id' => LevelEnum::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isSuperAdmin() : bool
    {
        return $this->level_id === LevelEnum::SUPER->value;
    }

    public function isAdmin() : bool
    {
        return $this->level_id === LevelEnum::ADMIN->value;
    }

    public function getVerifyStatusAttribute()
    {
        return $this->deleted_at ?? null;
    }

    public function level() : BelongsTo
    {
        return $this->belongsTo(Level::class);
    }
}
