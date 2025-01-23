<?php

namespace App\Models;

use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Partner
 * App\Models\Partner
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $path_image
 * @property int $user_id
 * @property int $status_id
 */
class Partner extends GenericModels
{
    protected $table = "partners";

    protected $fillable = [
        'name',
        'email',
        'phone',
        'path_image',
        'user_id',
        'status_id'
    ];

    protected $casts = [
//        'status_id' => StatusEnum::class,
    ];

    /**
     * @return BelongsTo
     */
    public function status() : BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id')
            ->select('id','name','description');
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
