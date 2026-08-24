<?php

namespace App\DTOs;

class UserDTO
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly ?string $password = null,
        public readonly bool $is_active = true,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            name      : $data['name'],
            email     : $data['email'],
            password  : $data['password'],
            is_active : $data['is_active'] ?? true,
        );
    }
}
