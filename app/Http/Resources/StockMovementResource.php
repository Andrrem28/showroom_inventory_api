<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StockMovementResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'type'     => $this->type,
            'quantity' => $this->quantity,
            'notes'    => $this->notes,
            'moved_at' => $this->moved_at->format('d/m/Y H:i'),
            'product'  => $this->whenLoaded('product', fn() => [
                'id'            => $this->product->id,
                'code'          => $this->product->code,
                'name'          => $this->product->name,
                'current_stock' => $this->product->current_stock,
            ]),
            'user' => $this->whenLoaded('user', fn() => [
                'id'   => $this->user->id,
                'name' => $this->user->name,
            ]),
            'created_at' => $this->created_at->format('d/m/Y H:i'),
        ];
    }
}
