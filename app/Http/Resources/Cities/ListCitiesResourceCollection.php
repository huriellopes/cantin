<?php

namespace App\Http\Resources\Cities;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ListCitiesResourceCollection extends ResourceCollection
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
