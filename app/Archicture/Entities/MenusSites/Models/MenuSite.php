<?php

namespace App\Archicture\Entities\MenusSites\Models;

use App\Archicture\Entities\Users\Models\User;
use App\Archicture\Generics\Models\GenericModels;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MenuSite
 * @package App\Archicture\Entities\MenusSites\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $route
 * @property int $status_menus_sites_id
 * @property int $user_id
 */
class MenuSite extends GenericModels
{
    /**
     * @var string
     */
    protected $table = 'menus_sites';

    /**
     * @var string[]
     */
    protected $fillable = [
        'name',
        'description',
        'route',
        'status',
        'user_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'status' => Status::class,
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @param Builder $query
     * @return void
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('status', Status::ACTIVE);
    }

}
