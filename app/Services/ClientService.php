<?php

namespace App\Services;

use App\DTOs\ClientDTO;
use App\Models\Client;
use Illuminate\Pagination\LengthAwarePaginator;

class ClientService
{
    public function getAll(): LengthAwarePaginator
    {
        return Client::orderBy('name')->paginate(20);
    }

    public function findOrFail(int $id): Client
    {
        return Client::findOrFail($id);
    }

    public function create(ClientDTO $dto): Client
    {
        return Client::create([
            'name'  => $dto->name,
            'phone' => $dto->phone,
            'email' => $dto->email,
        ]);
    }

    public function update(Client $client, ClientDTO $dto): Client
    {
        $client->update([
            'name'  => $dto->name,
            'phone' => $dto->phone,
            'email' => $dto->email,
        ]);

        return $client->fresh();
    }

    public function delete(Client $client): void
    {
        $client->delete();
    }
}
