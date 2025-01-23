<?php

namespace App\Models;

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
