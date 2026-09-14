<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sale\StoreSaleRequest;
use App\Http\Resources\SaleResource;
use App\Services\SaleService;

class SaleController extends Controller
{
    public function __construct(
        private readonly SaleService $service
    ) {}

    // GET /api/sales
    public function index()
    {
        $sales = $this->service->getAll();

        return SaleResource::collection($sales);
    }

    // GET /api/sales/{sale}
    public function show(int $sale)
    {
        $found = $this->service->findOrFail($sale);

        return new SaleResource($found);
    }

    // POST /api/sales
    public function store(StoreSaleRequest $request)
    {
        try {
            $sale = $this->service->create(
                $request->toDTO(),
                $request->user()->id
            );

            return new SaleResource($sale);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    // DELETE /api/sales/{sale}
    public function destroy(int $sale)
    {
        $found = $this->service->findOrFail($sale);
        $this->service->delete($found);

        return response()->json(['message' => 'Venda cancelada e estoque restaurado com sucesso.']);
    }
}
