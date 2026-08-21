<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Permission\StorePermissionRequest;
use App\Http\Requests\Permission\UpdatePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Services\PermissionService;

class PermissionController extends Controller
{
    public function __construct(
        private readonly PermissionService $service
    ) {}

    // GET /api/permissions
    public function index()
    {
        $permissions = $this->service->getAll();

        return PermissionResource::collection($permissions);
    }

    // GET /api/permissions/{id}
    public function show(int $id)
    {
        $permission = $this->service->findOrFail($id);

        return new PermissionResource($permission);
    }

    // POST /api/permissions
    public function store(StorePermissionRequest $request)
    {
        $permission = $this->service->create($request->toDTO());

        return new PermissionResource($permission);
    }

    // PUT /api/permissions/{id}
    public function update(UpdatePermissionRequest $request, int $id)
    {
        $permission = $this->service->findOrFail($id);
        $updated    = $this->service->update($permission, $request->toDTO());

        return new PermissionResource($updated);
    }

    // DELETE /api/permissions/{id}
    public function destroy(int $id)
    {
        $permission = $this->service->findOrFail($id);
        $this->service->delete($permission);

        return response()->json(['message' => 'Permissão removida com sucesso.']);
    }
}
