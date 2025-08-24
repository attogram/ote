<?php

namespace Database\Factories;

use App\Models\LexicalEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'lexical_entry_id' => LexicalEntry::factory(),
            'key' => $this->faker->word,
            'value' => $this->faker->sentence,
        ];
    }
}
