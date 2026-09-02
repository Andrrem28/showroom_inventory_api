<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovement\StoreStockMovementRequest;
use App\Http\Resources\StockMovementResource;
use App\Services\StockMovementService;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function __construct(
        private readonly StockMovementService $service
    ) {}

    // GET /api/stock-movements
    public function index()
    {
        $movements = $this->service->getAll();

        return StockMovementResource::collection($movements);
    }

    // GET /api/stock-movements/{stockMovement}
    public function show(int $stockMovement)
    {
        $found = $this->service->findOrFail($stockMovement);

        return new StockMovementResource($found);
    }

    // GET /api/products/{product}/stock-movements
    public function byProduct(int $product)
    {
        $movements = $this->service->getByProduct($product);

        return StockMovementResource::collection($movements);
    }

    // POST /api/stock-movements
    public function store(StoreStockMovementRequest $request)
    {
        try {
            $movement = $this->service->create(
                $request->toDTO(),
                $request->user()->id
            );

            return new StockMovementResource(
                $movement->load(['product', 'user'])
            );
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
