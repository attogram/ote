<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Token;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class LexicalEntrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = Language::pluck('id');
        $tokens = Token::pluck('id');
        $now = Carbon::now();

        $possibleEntries = [];
        foreach ($tokens as $tokenId) {
            foreach ($languages as $languageId) {
                $possibleEntries[] = [
                    'token_id' => $tokenId,
                    'language_id' => $languageId,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        // Shuffle and take a subset of possible entries to insert
        $entriesToInsert = collect($possibleEntries)->shuffle()->take(200)->all();

        // Insert in chunks to be efficient
        foreach (array_chunk($entriesToInsert, 200) as $chunk) {
            LexicalEntry::insert($chunk);
        }
    }
}
