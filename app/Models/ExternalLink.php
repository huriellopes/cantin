<?php

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class ExternalLink extends Model
{
    /* @use HasFactory<\Database\Factories\ExternalLinkFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $fillable = [
        'title',
        'url',
        'description',
        'status',
        'user_id',
        'type_external_link_id',
    ];

    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    #[\Override]
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model): void {
            if (! app()->runningInConsole()) {
                $model->user_id = auth()->user()->id;
            }
        });
    }

    public function type(): HasOne
    {
        return $this->hasOne(TypeExternalLink::class, 'id', 'type_external_link_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
