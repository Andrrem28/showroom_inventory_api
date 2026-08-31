<?php

namespace App\DTOs;

class ProductDTO
{
    public function __construct(
        public readonly string  $code,
        public readonly string  $name,
        public readonly float   $cost_price,
        public readonly float   $sale_price,
        public readonly int     $current_stock,
        public readonly int     $minimum_stock,
        public readonly ?string $color_shade   = null,
        public readonly ?int    $category_id   = null,
        public readonly ?int    $brand_id      = null,
        public readonly ?int    $supplier_id   = null,
        public readonly ?int    $location_id   = null,
        public readonly ?string $notes         = null,
        public readonly bool    $is_active     = true,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            code:          $data['code'],
            name:          $data['name'],
            cost_price:    $data['cost_price'],
            sale_price:    $data['sale_price'],
            current_stock: $data['current_stock'],
            minimum_stock: $data['minimum_stock'],
            color_shade:   $data['color_shade']  ?? null,
            category_id:   $data['category_id']  ?? null,
            brand_id:      $data['brand_id']      ?? null,
            supplier_id:   $data['supplier_id']   ?? null,
            location_id:   $data['location_id']   ?? null,
            notes:         $data['notes']         ?? null,
            is_active:     $data['is_active']     ?? true,
        );
    }
}
