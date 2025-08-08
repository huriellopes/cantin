<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Tag extends Model
{
    /* @use HasFactory<\Database\Factories\TagFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $fillable = [
        'name',
        'slug',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function posts() : BelongsToMany
    {
        return $this->belongsToMany(Post::class);
    }
}
