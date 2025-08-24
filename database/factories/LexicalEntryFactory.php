<?php

namespace Database\Factories;

use App\Models\Language;
use App\Models\Token;
use Illuminate\Database\Eloquent\Factories\Factory;

class LexicalEntryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'token_id' => Token::factory(),
            'language_id' => Language::factory(),
        ];
    }
}
