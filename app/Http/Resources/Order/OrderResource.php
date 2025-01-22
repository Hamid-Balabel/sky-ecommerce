<?php

namespace App\Http\Resources\Order;

use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;


class OrderResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'products' => $this->whenLoaded('products', fn() => ProductResource::collection($this->products)),
            'customer' => $this->whenLoaded('customer', fn() => new UserResource($this->customer)),
            'status' => $this->status,
            'created_at' => $this->created_at,
        ];
    }
}
