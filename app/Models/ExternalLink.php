<?php

declare(strict_types=1);

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Override;
use OwenIt\Auditing\Auditable;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class ExternalLink extends Model implements AuditableContract
{
    use Auditable;

    /* @use HasFactory<\Database\Factories\ExternalLinkFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $fillable = [
        'title',
        'slug',
        'url',
        'description',
        'status',
        'user_id',
        'type_external_link_id',
    ];

    public function type(): HasOne
    {
        return $this->hasOne(TypeExternalLink::class, 'id', 'type_external_link_id');
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

    #[Override]
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            if (!app()->runningInConsole()) {
                $model->user_id = auth()->user()->id;
            }
        });
    }
}
