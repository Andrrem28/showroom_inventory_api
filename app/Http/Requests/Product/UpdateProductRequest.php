<?php

namespace App\Http\Requests\Product;

use App\DTOs\ProductDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => [
                'sometimes',
                'string',
                'max:50',
                Rule::unique('products', 'code')->ignore($this->route('product')),
            ],
            'name'          => 'sometimes|string|max:150',
            'color_shade'   => 'nullable|string|max:100',
            'cost_price'    => 'sometimes|numeric|min:0',
            'sale_price'    => 'sometimes|numeric|min:0',
            'current_stock' => 'sometimes|integer|min:0',
            'minimum_stock' => 'sometimes|integer|min:0',
            'category_id'   => 'nullable|exists:categories,id',
            'brand_id'      => 'nullable|exists:brands,id',
            'supplier_id'   => 'nullable|exists:suppliers,id',
            'location_id'   => 'nullable|exists:storage_locations,id',
            'notes'         => 'nullable|string',
            'is_active'     => 'boolean',
        ];
    }

    public function toDTO(): ProductDTO
    {
        return ProductDTO::fromRequest($this->validated());
    }
}
