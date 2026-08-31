<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        private readonly ProductService $service
    ) {}

    // GET /api/products
    public function index()
    {
        $products = $this->service->getAll();

        return ProductResource::collection($products);
    }

    // GET /api/products/low-stock
    public function lowStock()
    {
        $products = $this->service->getBelowMinimumStock();

        return ProductResource::collection($products);
    }

    // GET /api/products/{product}
    public function show(int $product)
    {
        $found = $this->service->findOrFail($product);

        return new ProductResource($found);
    }

    // POST /api/products
    public function store(StoreProductRequest $request)
    {
        $product = $this->service->create($request->toDTO());

        return new ProductResource($product);
    }

    // PUT /api/products/{product}
    public function update(UpdateProductRequest $request, int $product)
    {
        $found   = $this->service->findOrFail($product);
        $updated = $this->service->update($found, $request->toDTO());

        return new ProductResource($updated);
    }

    // DELETE /api/products/{product}
    public function destroy(int $product)
    {
        $found = $this->service->findOrFail($product);
        $this->service->delete($found);

        return response()->json(['message' => 'Produto desativado com sucesso.']);
    }
}
