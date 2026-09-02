<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\ClientResource;
use App\Services\ClientService;

class ClientController extends Controller
{
    public function __construct(
        private readonly ClientService $service
    ) {}

    // GET /api/clients
    public function index()
    {
        $clients = $this->service->getAll();

        return ClientResource::collection($clients);
    }

    // GET /api/clients/{client}
    public function show(int $client)
    {
        $found = $this->service->findOrFail($client);

        return new ClientResource($found);
    }

    // POST /api/clients
    public function store(StoreClientRequest $request)
    {
        $client = $this->service->create($request->toDTO());

        return new ClientResource($client);
    }

    // PUT /api/clients/{client}
    public function update(UpdateClientRequest $request, int $client)
    {
        $found   = $this->service->findOrFail($client);
        $updated = $this->service->update($found, $request->toDTO());

        return new ClientResource($updated);
    }

    // DELETE /api/clients/{client}
    public function destroy(int $client)
    {
        $found = $this->service->findOrFail($client);
        $this->service->delete($found);

        return response()->json(['message' => 'Cliente removido com sucesso.']);
    }
}
