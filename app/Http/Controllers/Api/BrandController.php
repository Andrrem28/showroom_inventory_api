<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Http\Resources\BrandResource;
use App\Services\BrandService;

class BrandController extends Controller
{
    public function __construct(
        private readonly BrandService $service
    ) {}

    // GET /api/brands
    public function index()
    {
        $brands = $this->service->getAll();

        return BrandResource::collection($brands);
    }

    // GET /api/brands/{brand}
    public function show(int $brand)
    {
        $found = $this->service->findOrFail($brand);

        return new BrandResource($found);
    }

    // POST /api/brands
    public function store(StoreBrandRequest $request)
    {
        $brand = $this->service->create($request->toDTO());

        return new BrandResource($brand);
    }

    // PUT /api/brands/{brand}
    public function update(UpdateBrandRequest $request, int $brand)
    {
        $found   = $this->service->findOrFail($brand);
        $updated = $this->service->update($found, $request->toDTO());

        return new BrandResource($updated);
    }

    // DELETE /api/brands/{brand}
    public function destroy(int $brand)
    {
        $found = $this->service->findOrFail($brand);
        $this->service->delete($found);

        return response()->json(['message' => 'Marca removida com sucesso.']);
    }
}
