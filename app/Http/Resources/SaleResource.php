<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SaleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                => $this->id,
            'payment_method'    => $this->payment_method,
            'installments'      => $this->installments,
            'installment_value' => $this->installments > 1
                ? number_format($this->installment_value, 2, ',', '.')
                : null,
            'total_amount'      => number_format($this->total_amount, 2, ',', '.'),
            'notes'             => $this->notes,
            'sold_at'           => $this->sold_at->format('d/m/Y H:i'),
            'client'            => $this->whenLoaded('client', fn() => [
                'id'   => $this->client?->id,
                'name' => $this->client?->name,
            ]),
            'user'              => $this->whenLoaded('user', fn() => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ]),
            'items'             => $this->whenLoaded('items', fn() =>
                $this->items->map(fn($item) => [
                    'id'         => $item->id,
                    'product'    => [
                        'id'   => $item->product->id,
                        'code' => $item->product->code,
                        'name' => $item->product->name,
                    ],
                    'quantity'   => $item->quantity,
                    'unit_price' => number_format($item->unit_price, 2, ',', '.'),
                    'subtotal'   => number_format($item->subtotal, 2, ',', '.'),
                ])
            ),
            'created_at'        => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
