<?php

namespace App\DTOs;

class SaleDTO
{
    public function __construct(
        public readonly string  $payment_method,
        public readonly array   $items,
        public readonly int     $installments  = 1,
        public readonly ?int    $client_id     = null,
        public readonly ?string $notes         = null,
        public readonly ?string $sold_at       = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            payment_method: $data['payment_method'],
            items:          $data['items'],
            installments:   $data['installments'] ?? 1,
            client_id:      $data['client_id']    ?? null,
            notes:          $data['notes']         ?? null,
            sold_at:        $data['sold_at']       ?? null,
        );
    }
}
