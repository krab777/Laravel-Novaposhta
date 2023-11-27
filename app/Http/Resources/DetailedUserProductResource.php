<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DetailedUserProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'amount' => number_format($this->products->sum('price'), 2),
            'avatar' => $this->avatar ? $this->avatar : 'avatars/default.png',
            'products' => ProductResourceWithPrice::collection($this->products),
        ];
    }
}
