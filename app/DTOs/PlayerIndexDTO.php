<?php

namespace App\DTOs;

class PlayerIndexDTO
{
    public function __construct(
        public string $page,
        public string $per_page,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            page: $data['page'],
            per_page: $data['per_page'],
        );
    }

    public function toArray(): array
    {
        return [
            'page' => $this->page,
            'per_page' => $this->per_page,
        ];
    }
}
