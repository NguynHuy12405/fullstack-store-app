<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class VariantResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'         => $this->id,
            'price'      => $this->price,
            'sale_price' => $this->sale_price,
            'quantity'   => $this->quantity,
            'color'      => $this->color,
            'size'       => $this->size,
        ];
    }
}