<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Override;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class NationsTerreiro extends Model implements AuditableContract
{
    use Auditable;
    use KeepsDeletedModels;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'slug',
    ];

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * @return string[]
     */
    #[Override]
    public function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }
}
