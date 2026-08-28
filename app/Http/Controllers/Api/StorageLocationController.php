<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorageLocation\StoreStorageLocationRequest;
use App\Http\Requests\StorageLocation\UpdateStorageLocationRequest;
use App\Http\Resources\StorageLocationResource;
use App\Services\StorageLocationService;

class StorageLocationController extends Controller
{
    public function __construct(
        private readonly StorageLocationService $service
    ) {}

    // GET /api/storage-locations
    public function index()
    {
        $locations = $this->service->getAll();

        return StorageLocationResource::collection($locations);
    }

    // GET /api/storage-locations/{storageLocation}
    public function show(int $storageLocation)
    {
        $found = $this->service->findOrFail($storageLocation);

        return new StorageLocationResource($found);
    }

    // POST /api/storage-locations
    public function store(StoreStorageLocationRequest $request)
    {
        $location = $this->service->create($request->toDTO());

        return new StorageLocationResource($location);
    }

    // PUT /api/storage-locations/{storageLocation}
    public function update(UpdateStorageLocationRequest $request, int $storageLocation)
    {
        $found   = $this->service->findOrFail($storageLocation);
        $updated = $this->service->update($found, $request->toDTO());

        return new StorageLocationResource($updated);
    }

    // DELETE /api/storage-locations/{storageLocation}
    public function destroy(int $storageLocation)
    {
        $found = $this->service->findOrFail($storageLocation);
        $this->service->delete($found);

        return response()->json(['message' => 'Local de armazenamento removido com sucesso.']);
    }
}
