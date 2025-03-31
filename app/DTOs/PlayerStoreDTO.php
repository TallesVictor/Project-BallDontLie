<?php

namespace App\DTOs;

class PlayerStoreDTO
{
    public function __construct(
        public string $first_name,
        public string $last_name,
        public string $position,
        public string $height,
        public int $weight,
        public string $jersey_number,
        public string $college,
        public string $country,
        public int $draft_year,
        public int $draft_round,
        public int $draft_number,
        public string $team_full_name,
    ) {}
    
    public static function fromArray(array $data): self
    {
        return new self(
            first_name: $data['first_name'],
            last_name: $data['last_name'],
            position: $data['position'],
            height: $data['height'],
            weight: $data['weight'],
            jersey_number: $data['jersey_number'],
            college: $data['college'],
            country: $data['country'],
            draft_year: $data['draft_year'],
            draft_round: $data['draft_round'],
            draft_number: $data['draft_number'],
            team_full_name: $data['team_full_name'],
        );
    }
    
    public function toArray(): array
    {
        return [
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position' => $this->position,
            'height' => $this->height,
            'weight' => $this->weight,
            'jersey_number' => $this->jersey_number,
            'college' => $this->college,
            'country' => $this->country,
            'draft_year' => $this->draft_year,
            'draft_round' => $this->draft_round,
            'draft_number' => $this->draft_number,
            'team_full_name' => $this->team_full_name,
        ];
    }
}
