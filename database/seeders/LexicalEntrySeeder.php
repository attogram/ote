<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Token;
use Illuminate\Database\Seeder;

class LexicalEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $en = Language::where('code', 'en')->first();
        $es = Language::where('code', 'es')->first();
        $fr = Language::where('code', 'fr')->first();

        $tokens = [
            'hello' => Token::where('text', 'hello')->first(),
            'world' => Token::where('text', 'world')->first(),
            'hola' => Token::where('text', 'hola')->first(),
            'mundo' => Token::where('text', 'mundo')->first(),
            'bonjour' => Token::where('text', 'bonjour')->first(),
            'monde' => Token::where('text', 'monde')->first(),
        ];

        LexicalEntry::firstOrCreate(['token_id' => $tokens['hello']->id, 'language_id' => $en->id]);
        LexicalEntry::firstOrCreate(['token_id' => $tokens['world']->id, 'language_id' => $en->id]);
        LexicalEntry::firstOrCreate(['token_id' => $tokens['hola']->id, 'language_id' => $es->id]);
        LexicalEntry::firstOrCreate(['token_id' => $tokens['mundo']->id, 'language_id' => $es->id]);
        LexicalEntry::firstOrCreate(['token_id' => $tokens['bonjour']->id, 'language_id' => $fr->id]);
        LexicalEntry::firstOrCreate(['token_id' => $tokens['monde']->id, 'language_id' => $fr->id]);
    }
}
