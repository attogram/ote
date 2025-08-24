<?php

namespace Database\Factories;

use App\Models\LexicalEntry;
use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'source_lexical_entry_id' => LexicalEntry::factory(),
            'target_lexical_entry_id' => LexicalEntry::factory(),
            'type' => $this->faker->word,
        ];
    }
}
