<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\Role as RoleEnum;
use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Override;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class User extends Authenticatable
{
    /* @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, KeepsDeletedModels, Notifiable;

    /**
     * Senha padrão atribuída a usuários criados pelo super-admin. O usuário é
     * obrigado a trocá-la no primeiro login (password_change_required).
     */
    public const string DEFAULT_PASSWORD = 'Cantin#2026';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'email',
        'email_verified_at',
        'password',
        'role_id',
        'status',
        'password_change_required',
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

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function hasRole(string ...$roles): bool
    {
        return in_array($this->role?->slug, $roles, true);
    }

    /**
     * Verificação robusta via enum (não depende da tabela roles/slug).
     */
    public function isSuperAdmin(): bool
    {
        return $this->role_id === RoleEnum::SUPER;
    }

    public function isAdmin(): bool
    {
        return in_array($this->role_id, [RoleEnum::SUPER, RoleEnum::ADMIN], true);
    }

    /**
     * @return array<string, string>
     */
    #[Override]
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'role_id' => RoleEnum::class,
            'status' => Status::class,
            'password_change_required' => 'boolean',
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
