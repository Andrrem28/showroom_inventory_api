<?php

namespace App\Services;

use App\DTOs\SaleDTO;
use App\Models\Product;
use App\Models\Sale;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class SaleService
{
    public function getAll(): LengthAwarePaginator
    {
        return Sale::with(['client', 'user', 'items.product'])
            ->orderByDesc('sold_at')
            ->paginate(20);
    }

    public function findOrFail(int $id): Sale
    {
        return Sale::with(['client', 'user', 'items.product'])
            ->findOrFail($id);
    }

    // ─────────────────────────────────────
    // Calcula o percentual de desconto
    // ─────────────────────────────────────
    private function calculateDiscount(int $totalQty, string $paymentMethod, int $installments): float
    {
        // Fiado nunca tem desconto
        // if ($paymentMethod === 'fiado') return 0;

        // Crédito parcelado (2x+)
        if ($paymentMethod === 'cartao_credito' && $installments > 1) {
            return 10; // desconto fixo de 10% independente da quantidade
        }

        // Dinheiro, PIX, Débito e Crédito à vista
        if ($totalQty >= 3) {
            return $paymentMethod === 'cartao_credito' ? 20 : 25;
        }

        return 10;
    }

    public function create(SaleDTO $dto, int $userId): Sale
    {
        return DB::transaction(function () use ($dto, $userId) {
            $totalAmount = 0;
            $totalQty    = 0;
            $saleItems   = [];

            // Valida e prepara os itens
            foreach ($dto->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->current_stock < $item['quantity']) {
                    throw new \Exception(
                        "Estoque insuficiente para o produto \"{$product->name}\". " .
                        "Disponível: {$product->current_stock}, Solicitado: {$item['quantity']}."
                    );
                }

                $unitPrice    = (float) $product->sale_price;
                $subtotal     = $unitPrice * $item['quantity'];
                $totalAmount += $subtotal;
                $totalQty    += $item['quantity'];

                $saleItems[] = [
                    'product'   => $product,
                    'quantity'  => $item['quantity'],
                    'unitPrice' => $unitPrice,
                    'subtotal'  => $subtotal,
                ];
            }

            // Calcula desconto
            $installments      = $dto->installments ?? 1;
            $discountPercent   = $this->calculateDiscount($totalQty, $dto->payment_method, $installments);
            $discountAmount    = round($totalAmount * ($discountPercent / 100), 2);
            $totalWithDiscount = $totalAmount - $discountAmount;

            // Calcula parcela
            $installmentValue = $installments > 1
                ? round($totalWithDiscount / $installments, 2)
                : $totalWithDiscount;

            // Cria a venda
            $sale = Sale::create([
                'client_id'         => $dto->client_id,
                'user_id'           => $userId,
                'payment_method'    => $dto->payment_method,
                'installments'      => $installments,
                'installment_value' => $installmentValue,
                'discount_percent'  => $discountPercent,
                'discount_amount'   => $discountAmount,
                'total_amount'      => $totalWithDiscount,
                'sold_at'           => $dto->sold_at ?? now(),
                'notes'             => $dto->notes,
            ]);

            // Cria os itens, desconta estoque e registra movimentação
            foreach ($saleItems as $item) {
                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unitPrice'],
                    'subtotal'   => $item['subtotal'],
                ]);

                $item['product']->decrement('current_stock', $item['quantity']);

                StockMovement::create([
                    'product_id' => $item['product']->id,
                    'user_id'    => $userId,
                    'type'       => 'saida',
                    'quantity'   => $item['quantity'],
                    'notes'      => "Saída automática — Venda #{$sale->id}",
                    'moved_at'   => now(),
                ]);
            }

            return $sale->load(['client', 'user', 'items.product']);
        });
    }

    public function delete(Sale $sale): void
    {
        DB::transaction(function () use ($sale) {
            foreach ($sale->items as $item) {
                $item->product->increment('current_stock', $item->quantity);

                StockMovement::create([
                    'product_id' => $item->product->id,
                    'user_id'    => $sale->user_id,
                    'type'       => 'entrada',
                    'quantity'   => $item->quantity,
                    'notes'      => "Estorno automático — Cancelamento da Venda #{$sale->id}",
                    'moved_at'   => now(),
                ]);
            }

            $sale->delete();
        });
    }
}
