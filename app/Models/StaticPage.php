<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Override;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class StaticPage extends Model implements AuditableContract
{
    use Auditable;

    /* @use HasFactory<\Database\Factories\StaticPageFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'status',
        'user_id',
    ];

    #[Override]
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }
}
