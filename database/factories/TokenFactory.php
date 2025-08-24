<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TokenFactory extends Factory
{
    public function definition(): array
    {
        return [
            'text' => $this->faker->unique()->word,
        ];
    }
}
