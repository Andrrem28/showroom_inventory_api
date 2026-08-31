<?php

namespace App\Http\Requests\Product;

use App\DTOs\ProductDTO;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code'          => 'required|string|max:50|unique:products,code',
            'name'          => 'required|string|max:150',
            'color_shade'   => 'nullable|string|max:100',
            'cost_price'    => 'required|numeric|min:0',
            'sale_price'    => 'required|numeric|min:0',
            'current_stock' => 'required|integer|min:0',
            'minimum_stock' => 'required|integer|min:0',
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
