<?php

namespace App\Http\Requests\StockMovement;

use App\DTOs\StockMovementDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreStockMovementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'product_id' => 'required|integer|exists:products,id',
            'type'       => ['required', Rule::in(['entrada', 'saida'])],
            'quantity'   => 'required|integer|min:1',
            'notes'      => 'nullable|string|max:255',
            'moved_at'   => 'nullable|date',
        ];
    }

    public function toDTO(): StockMovementDTO
    {
        return StockMovementDTO::fromRequest($this->validated());
    }
}
