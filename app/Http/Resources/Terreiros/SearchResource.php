<?php

namespace App\Http\Resources\Terreiros;

use App\Traits\Utils;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SearchResource extends JsonResource
{
    use Utils;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'nation' => $this->nation->nation,
            'phone' => $this->maskPhone($this->phone),
            'orunko' => $this->leadership_orunko,
            'fundation' => Carbon::parse($this->fundationed_at)->format('d/m/Y'),
            'leadership' => $this->color_of_leadership,
            'state' => $this->address->state->description,
            'city' => $this->address->city->city_name,
            'created_at' => Carbon::parse($this->created_at)->format('d/m/Y'),
            'updated_at' => Carbon::parse($this->updated_at)->format('d/m/Y')
        ];
    }
}
