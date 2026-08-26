<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $service
    ) {}

    // GET /api/categories
    public function index()
    {
        $categories = $this->service->getAll();

        return CategoryResource::collection($categories);
    }

    // GET /api/categories/{category}
    public function show(int $category)
    {
        $found = $this->service->findOrFail($category);

        return new CategoryResource($found);
    }

    // POST /api/categories
    public function store(StoreCategoryRequest $request)
    {
        $category = $this->service->create($request->toDTO());

        return new CategoryResource($category);
    }

    // PUT /api/categories/{category}
    public function update(UpdateCategoryRequest $request, int $category)
    {
        $found   = $this->service->findOrFail($category);
        $updated = $this->service->update($found, $request->toDTO());

        return new CategoryResource($updated);
    }

    // DELETE /api/categories/{category}
    public function destroy(int $category)
    {
        $found = $this->service->findOrFail($category);
        $this->service->delete($found);

        return response()->json(['message' => 'Categoria removida com sucesso.']);
    }
}
