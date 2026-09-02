<?php

namespace App\DTOs;

class ClientDTO
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $phone = null,
        public readonly ?string $email = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name:  $data['name'],
            phone: $data['phone'] ?? null,
            email: $data['email'] ?? null,
        );
    }
}
