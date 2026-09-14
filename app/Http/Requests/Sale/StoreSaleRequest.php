<?php

namespace App\Http\Requests\Sale;

use App\DTOs\SaleDTO;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id'      => 'nullable|integer|exists:clients,id',
            'payment_method' => ['required', Rule::in([
                'dinheiro',
                'pix',
                'cartao_credito',
                'cartao_debito',
                'fiado',
            ])],
            'installments' => [
                'nullable',
                'integer',
                'min:1',
                'max:12',
                function ($attribute, $value, $fail) {
                    if ($value > 1 && $this->input('payment_method') !== 'cartao_credito') {
                        $fail('Parcelamento só é permitido para pagamentos em cartão de crédito.');
                    }
                },
            ],
            'notes'                => 'nullable|string|max:255',
            'sold_at'              => 'nullable|date',
            'items'                => 'required|array|min:1',
            'items.*.product_id'   => 'required|integer|exists:products,id',
            'items.*.quantity'     => 'required|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'installments.min' => 'O número de parcelas deve ser ao menos 1.',
            'installments.max' => 'O número máximo de parcelas é 12.',
            'items.required'   => 'A venda deve ter ao menos um item.',
            'items.min'        => 'A venda deve ter ao menos um item.',
        ];
    }

    public function toDTO(): SaleDTO
    {
        return SaleDTO::fromRequest($this->validated());
    }
}
