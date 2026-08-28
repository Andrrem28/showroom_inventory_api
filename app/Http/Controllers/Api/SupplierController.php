<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Supplier\StoreSupplierRequest;
use App\Http\Requests\Supplier\UpdateSupplierRequest;
use App\Http\Resources\SupplierResource;
use App\Services\SupplierService;

class SupplierController extends Controller
{
    public function __construct(
        private readonly SupplierService $service
    ) {}

    // GET /api/suppliers
    public function index()
    {
        $suppliers = $this->service->getAll();

        return SupplierResource::collection($suppliers);
    }

    // GET /api/suppliers/{supplier}
    public function show(int $supplier)
    {
        $found = $this->service->findOrFail($supplier);

        return new SupplierResource($found);
    }

    // POST /api/suppliers
    public function store(StoreSupplierRequest $request)
    {
        $supplier = $this->service->create($request->toDTO());

        return new SupplierResource($supplier);
    }

    // PUT /api/suppliers/{supplier}
    public function update(UpdateSupplierRequest $request, int $supplier)
    {
        $found   = $this->service->findOrFail($supplier);
        $updated = $this->service->update($found, $request->toDTO());

        return new SupplierResource($updated);
    }

    // DELETE /api/suppliers/{supplier}
    public function destroy(int $supplier)
    {
        $found = $this->service->findOrFail($supplier);
        $this->service->delete($found);

        return response()->json(['message' => 'Fornecedor removido com sucesso.']);
    }
}
