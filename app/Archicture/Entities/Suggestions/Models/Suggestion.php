<?php

namespace App\Archicture\Entities\Suggestions\Models;

use App\Archicture\Generics\Models\GenericModels;

/**
 * @property $type_suggestion
 * @property $description
 */
class Suggestion extends GenericModels
{
    protected $table = "suggestions";

    protected $fillable = [
        'type_suggestion',
        'description'
    ];
}
