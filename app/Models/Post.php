<?php

namespace App\Models;

use App\Enum\StatusPost;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

/**
 * @class Post
 *
 * @property string $title
 * @property string $slug
 * @property string $content
 * @property string $main_image
 * @property string $published_at
 * @property StatusPost $status
 * @property int $likes
 * @property int $dislikes
 * @property int $views
 * @property User $user
 * @property Category $category
 */
class Post extends Model
{
    use HasFactory, KeepsDeletedModels;

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'slug',
        'content',
        'main_image',
        'published_at',
        'status',
        'views',
        'user_id',
        'category_id',
    ];

    /**
     * @return string[]
     */
    protected function casts(): array
    {
        return [
            'status' => StatusPost::class,
            'views' => 'integer',
            'published_at' => 'datetime',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished()
    {
        return $this->where('status', '=', StatusPost::PUBLISHED);
    }

    public function scopeSearch($query, ?string $search = null): void
    {
        if (! empty($search)) {
            collect(explode(' ', $search))
                ->filter()
                ->each(function ($term) use ($query) {
                    $term = $term.'%';

                    $query->where(function ($query) use ($term) {
                        $query->where('title', 'like', $term)
                            ->orWhere('content', 'like', $term)
                            ->orWhereIn('category_id', Category::query()
                                ->where('slug', 'like', $term)
                                ->pluck('id'));
                    });
                });
        }
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_id')
            ->orderBy('created_at', 'desc');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Relacionamento com os dislikes deste post.
     */
    public function dislikes(): HasMany
    {
        return $this->hasMany(Dislike::class); // Ou belongsToMany(User::class, 'dislikes');
    }
}
