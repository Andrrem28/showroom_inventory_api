<?php

namespace App\Services;

use App\DTOs\StockMovementDTO;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class StockMovementService
{
    public function getAll(): LengthAwarePaginator
    {
        return StockMovement::with(['product', 'user'])
            ->orderByDesc('moved_at')
            ->paginate(20);
    }

    public function getByProduct(int $productId): LengthAwarePaginator
    {
        return StockMovement::with(['product', 'user'])
            ->where('product_id', $productId)
            ->orderByDesc('moved_at')
            ->paginate(20);
    }

    public function findOrFail(int $id): StockMovement
    {
        return StockMovement::with(['product', 'user'])->findOrFail($id);
    }

    public function create(StockMovementDTO $dto, int $userId): StockMovement
    {
        return DB::transaction(function () use ($dto, $userId) {
            $product = Product::findOrFail($dto->product_id);

            // Valida se há estoque suficiente para saída
            if ($dto->type === 'saida' && $product->current_stock < $dto->quantity) {
                throw new \Exception(
                    "Estoque insuficiente. Disponível: {$product->current_stock}, Solicitado: {$dto->quantity}."
                );
            }

            // Atualiza o estoque do produto
            if ($dto->type === 'entrada') {
                $product->increment('current_stock', $dto->quantity);
            } else {
                $product->decrement('current_stock', $dto->quantity);
            }

            // Registra a movimentação
            return StockMovement::create([
                'product_id' => $dto->product_id,
                'user_id'    => $userId,
                'type'       => $dto->type,
                'quantity'   => $dto->quantity,
                'notes'      => $dto->notes,
                'moved_at'   => $dto->moved_at ?? now(),
            ]);
        });
    }
}
