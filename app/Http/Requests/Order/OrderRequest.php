<?php

namespace App\Http\Requests\Order;

use App\Enums\OrderStatusEnum;
use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rules\Enum;

class OrderRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'products' => ['required', 'array'],
            'products.*.id' => ['required', 'exists:products,id'],
            'products.*.quantity' => ['required', 'numeric', 'min:1'],
            'products.*.price' => ['required', 'numeric'],
        ];
    }
}
