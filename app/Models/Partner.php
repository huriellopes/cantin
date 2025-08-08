<?php

namespace App\Models;

use App\Enum\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\DeletedModels\Models\Concerns\KeepsDeletedModels;

class Partner extends Model
{
    use KeepsDeletedModels;

    protected $table = "partners";

    protected $fillable = [
        'name',
        'email',
        'phone',
        'path_image',
        'user_id',
        'status'
    ];

    /**
     * @return string[]
     */
    public function casts() : array
    {
        return [
            'status' => Status::class,
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
