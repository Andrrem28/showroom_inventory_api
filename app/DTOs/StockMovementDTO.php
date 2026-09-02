<?php

namespace App\DTOs;

class StockMovementDTO
{
    public function __construct(
        public readonly int     $product_id,
        public readonly string  $type,
        public readonly int     $quantity,
        public readonly ?string $notes    = null,
        public readonly ?string $moved_at = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            product_id: $data['product_id'],
            type:       $data['type'],
            quantity:   $data['quantity'],
            notes:      $data['notes']    ?? null,
            moved_at:   $data['moved_at'] ?? null,
        );
    }
}
