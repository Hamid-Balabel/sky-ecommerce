<?php

namespace App\Http\Requests\Product;

use App\Http\Requests\BaseRequest;
use Illuminate\Validation\Rule;

class ProductRequest extends BaseRequest
{

    public function rules(): array
    {
        return [
            'name' => ['required', Rule::unique('products', 'name')->ignore($this->product?->id)],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }
}
