<?php

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Comment extends Model
{
    /* @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory, KeepsDeletedModels;

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'ip_address',
        'post_id',
        'parent_id',
        'body',
        'status',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => Status::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')->orderBy('created_at', 'asc');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class); // Ou belongsToMany(User::class, 'likes');
    }

    /**
     * Relacionamento com os dislikes deste post.
     */
    public function dislikes(): HasMany
    {
        return $this->hasMany(Dislike::class); // Ou belongsToMany(User::class, 'dislikes');
    }
}
