<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'code'          => $this->code,
            'name'          => $this->name,
            'color_shade'   => $this->color_shade,
            'cost_price'    => number_format($this->cost_price, 2, ',', '.'),
            'sale_price'    => number_format($this->sale_price, 2, ',', '.'),
            'unit_profit'   => number_format($this->unit_profit, 2, ',', '.'),
            'current_stock' => $this->current_stock,
            'minimum_stock' => $this->minimum_stock,
            'below_minimum' => $this->isBelowMinimumStock(),
            'is_active'     => $this->is_active,
            'notes'         => $this->notes,
            'category'      => $this->whenLoaded('category', fn() => [
                'id'   => $this->category?->id,
                'name' => $this->category?->name,
            ]),
            'brand'         => $this->whenLoaded('brand', fn() => [
                'id'   => $this->brand?->id,
                'name' => $this->brand?->name,
            ]),
            'supplier'      => $this->whenLoaded('supplier', fn() => [
                'id'   => $this->supplier?->id,
                'name' => $this->supplier?->name,
            ]),
            'location'      => $this->whenLoaded('location', fn() => [
                'id'   => $this->location?->id,
                'name' => $this->location?->name,
            ]),
            'created_at'    => $this->created_at->format('d/m/Y H:i'),
            'updated_at'    => $this->updated_at->format('d/m/Y H:i'),
        ];
    }
}
