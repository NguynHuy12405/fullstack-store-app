<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'        => $this->id,
            'name'      => $this->name,
            'slug'      => $this->slug,
            'thumbnail' => $this->thumbnail,
            'rating'    => $this->rating,

            // Giá thấp nhất (đã eager load bằng withMin)
            'price'     => $this->variants_min_price,

            // Category & Brand gọn gàng cho FE
            'category'  => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
                'slug' => $this->category?->slug,
            ],
            'brand' => [
                'id'   => $this->brand?->id,
                'name' => $this->brand?->name,
                'slug' => $this->brand?->slug,
            ],
        ];
    }
}
