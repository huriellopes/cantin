<?php

namespace App\Archicture\Entities\CommonQuestion\Models;

use App\Archicture\Entities\Status\Models\Status;
use App\Archicture\Generics\Models\GenericModels;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class CommonQuestion
 * App\Archicture\Entities\CommonQuestion\Models\CommonQuestion
 * @property $question
 * @property $answer
 * @property $status_id
 */
class CommonQuestion extends GenericModels
{
    protected $table = 'common_questions';

    protected $fillable = [
        'question',
        'answer',
        'status_id'
    ];

    /**
     * @return BelongsTo
     */
    public function status() : BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
