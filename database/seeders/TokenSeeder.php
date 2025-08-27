<?php

namespace Database\Seeders;

use App\Models\Token;
use Illuminate\Database\Seeder;

class TokenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Token::firstOrCreate(['text' => 'hello']);
        Token::firstOrCreate(['text' => 'world']);
        Token::firstOrCreate(['text' => 'hola']);
        Token::firstOrCreate(['text' => 'mundo']);
        Token::firstOrCreate(['text' => 'bonjour']);
        Token::firstOrCreate(['text' => 'monde']);
    }
}
