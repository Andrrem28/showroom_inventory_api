<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'color_shade',
        'cost_price',
        'sale_price',
        'current_stock',
        'minimum_stock',
        'category_id',
        'brand_id',
        'supplier_id',
        'location_id',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'cost_price'    => 'decimal:2',
            'sale_price'    => 'decimal:2',
            'current_stock' => 'integer',
            'minimum_stock' => 'integer',
            'is_active'     => 'boolean',
        ];
    }

    // Verifica se o estoque está abaixo do mínimo
    public function isBelowMinimumStock(): bool
    {
        return $this->current_stock <= $this->minimum_stock;
    }

    // Lucro unitário
    public function getUnitProfitAttribute(): float
    {
        return (float) $this->sale_price - (float) $this->cost_price;
    }

    // ─────────────────────────────────────
    // Relacionamentos
    // ─────────────────────────────────────

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location()
    {
        return $this->belongsTo(StorageLocation::class, 'location_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }
}
