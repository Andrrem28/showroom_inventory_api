<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getAll(): LengthAwarePaginator
    {
        return Product::with(['category', 'brand', 'supplier', 'location'])
            ->orderBy('name')
            ->paginate(20);
    }

    public function getBelowMinimumStock(): LengthAwarePaginator
    {
        return Product::with(['category', 'brand', 'supplier', 'location'])
            ->whereColumn('current_stock', '<=', 'minimum_stock')
            ->where('is_active', true)
            ->orderBy('name')
            ->paginate(20);
    }

    public function findOrFail(int $id): Product
    {
        return Product::with(['category', 'brand', 'supplier', 'location'])
            ->findOrFail($id);
    }

    public function create(ProductDTO $dto): Product
    {
        return Product::create([
            'code'          => $dto->code,
            'name'          => $dto->name,
            'color_shade'   => $dto->color_shade,
            'cost_price'    => $dto->cost_price,
            'sale_price'    => $dto->sale_price,
            'current_stock' => $dto->current_stock,
            'minimum_stock' => $dto->minimum_stock,
            'category_id'   => $dto->category_id,
            'brand_id'      => $dto->brand_id,
            'supplier_id'   => $dto->supplier_id,
            'location_id'   => $dto->location_id,
            'notes'         => $dto->notes,
            'is_active'     => $dto->is_active,
        ]);
    }

    public function update(Product $product, ProductDTO $dto): Product
    {
        $product->update([
            'code'          => $dto->code,
            'name'          => $dto->name,
            'color_shade'   => $dto->color_shade,
            'cost_price'    => $dto->cost_price,
            'sale_price'    => $dto->sale_price,
            'current_stock' => $dto->current_stock,
            'minimum_stock' => $dto->minimum_stock,
            'category_id'   => $dto->category_id,
            'brand_id'      => $dto->brand_id,
            'supplier_id'   => $dto->supplier_id,
            'location_id'   => $dto->location_id,
            'notes'         => $dto->notes,
            'is_active'     => $dto->is_active,
        ]);

        return $product->fresh(['category', 'brand', 'supplier', 'location']);
    }

    public function delete(Product $product): void
    {
        // Soft delete lógico — desativa ao invés de apagar
        $product->update(['is_active' => false]);
    }
}
