<?php

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeExternalLink extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'status',
    ];

    #[\Override]
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function links(): HasMany
    {
        return $this->hasMany(ExternalLink::class);
    }
}
