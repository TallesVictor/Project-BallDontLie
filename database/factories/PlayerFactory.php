<?php

namespace Database\Factories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Player>
 */
class PlayerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $team = Team::factory()->create();
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'position' => $this->faker->randomElement(['PG', 'SG', 'SF', 'PF', 'C']),
            'height' => $this->faker->randomFloat(2, 1.75, 2.20),
            'weight' => $this->faker->numberBetween(70, 130),
            'jersey_number' => $this->faker->numberBetween(0, 99),
            'college' => $this->faker->company(),
            'country' => $this->faker->country(),
            'team_id' => $team->id,
            'draft_year' => $this->faker->numberBetween(1990, 2025),
            'draft_round' => $this->faker->numberBetween(1, 2),
            'draft_number' => $this->faker->numberBetween(1, 60),
        ];
    }
}
