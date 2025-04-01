<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'team_origin_id' => $this->faker->randomNumber(),
            'name' => $this->faker->word(),
            'full_name' => $this->faker->company(),
            'conference' => $this->faker->randomElement(['East', 'West']),
            'division' => $this->faker->randomElement(['North', 'South', 'Central']),
            'city' => $this->faker->city(),
            'abbreviation' => strtoupper($this->faker->lexify('??')),
        ];
    }
}
