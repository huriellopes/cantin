<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Override;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Category extends Model implements AuditableContract
{
    use Auditable;
    use KeepsDeletedModels;

    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
