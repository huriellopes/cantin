<?php

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class StaticPage extends Model
{
    /* @use HasFactory<\Database\Factories\StaticPageFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'status',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'status' => Status::class,
        ];
    }

    /**
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
