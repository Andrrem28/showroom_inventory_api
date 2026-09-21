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

    public function create(SaleDTO $dto, int $userId): Sale
    {
        return DB::transaction(function () use ($dto, $userId) {
            $totalAmount = 0;
            $saleItems   = [];

            // Valida e prepara os itens
            foreach ($dto->items as $item) {
                $product = Product::findOrFail($item['product_id']);

                // Verifica estoque disponível
                if ($product->current_stock < $item['quantity']) {
                    throw new \Exception(
                        "Estoque insuficiente para o produto \"{$product->name}\". " .
                        "Disponível: {$product->current_stock}, Solicitado: {$item['quantity']}."
                    );
                }

                $unitPrice    = (float) $product->sale_price;
                $subtotal     = $unitPrice * $item['quantity'];
                $totalAmount += $subtotal;

                $saleItems[] = [
                    'product'   => $product,
                    'quantity'  => $item['quantity'],
                    'unitPrice' => $unitPrice,
                    'subtotal'  => $subtotal,
                ];
            }

            // Calcula o valor da parcela
            $installments     = $dto->installments ?? 1;
            $installmentValue = $installments > 1
                ? round($totalAmount / $installments, 2)
                : $totalAmount;

            // Cria a venda
            $sale = Sale::create([
                'client_id'         => $dto->client_id,
                'user_id'           => $userId,
                'payment_method'    => $dto->payment_method,
                'installments'      => $installments,
                'installment_value' => $installmentValue,
                'total_amount'      => $totalAmount,
                'sold_at'           => $dto->sold_at ?? now(),
                'notes'             => $dto->notes,
            ]);

            // Cria os itens, desconta o estoque e registra a movimentação
            foreach ($saleItems as $item) {
                $sale->items()->create([
                    'product_id' => $item['product']->id,
                    'quantity'   => $item['quantity'],
                    'unit_price' => $item['unitPrice'],
                    'subtotal'   => $item['subtotal'],
                ]);

                // Desconta o estoque
                $item['product']->decrement('current_stock', $item['quantity']);

                // Registra saída automática em stock_movements
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

                // Devolve o estoque
                $item->product->increment('current_stock', $item->quantity);

                // Registra entrada de estorno em stock_movements
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
