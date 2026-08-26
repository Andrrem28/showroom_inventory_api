<?php

namespace App\DTOs;

class CategoryDTO
{
    public function __construct(
        public readonly string $name,
        public readonly ?string $description = null,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data["name"],
            description: $data["description"] ?? null,
        );
    }
}
