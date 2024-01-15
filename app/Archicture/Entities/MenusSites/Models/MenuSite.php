<?php

namespace App\Archicture\Entities\MenusSites\Models;

use App\Archicture\Entities\StatusMenusSites\Enum\StatusMenuSiteEnum;
use App\Archicture\Entities\StatusMenusSites\Models\StatusMenuSite;
use App\Archicture\Generics\Models\GenericModels;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class MenuSite
 * @package App\Archicture\Entities\MenusSites\Models
 * @property int $id
 * @property string $name
 * @property string $description
 * @property string $url
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
        'url',
        'status_menus_sites_id',
        'user_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
//        'status_menus_sites_id' => StatusMenuSiteEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function statusMenuSite() : BelongsTo
    {
        return $this->belongsTo(StatusMenuSite::class, 'status_menus_sites_id', 'id');
    }
}
