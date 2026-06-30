<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Export extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'disk',
        'path',
        'status',
        'notified',
        'downloaded_at',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function casts(): array
    {
        return [
            'notified' => 'boolean',
            'downloaded_at' => 'datetime',
        ];
    }
}
