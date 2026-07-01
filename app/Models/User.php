<?php

declare(strict_types=1);

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enum\Role as RoleEnum;
use App\Enum\Status;
use App\Models\Concerns\HasTwoFactorAuthentication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;
use Override;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @property bool $password_change_required
 * @property Carbon|null $last_login_at
 * @property string|null $two_factor_secret
 * @property array<int, string>|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 */
class User extends Authenticatable implements AuditableContract
{
    use Auditable;

    /* @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, HasTwoFactorAuthentication, KeepsDeletedModels, Notifiable;

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
        'last_login_at',
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
            'last_login_at' => 'datetime',
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
