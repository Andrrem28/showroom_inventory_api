<?php

namespace App\DTOs;

class BrandDTO
{
    public function __construct(
        public readonly string $name,
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            name: $data['name'],
        );
    }
}
