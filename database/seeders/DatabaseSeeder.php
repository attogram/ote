<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\LexicalEntry;
use App\Models\Link;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            UserSeeder::class,
            TokenSeeder::class,
            LexicalEntrySeeder::class,
        ]);

        // Add some attributes
        $helloEn = LexicalEntry::whereHas('token', fn($q) => $q->where('text', 'hello'))
            ->whereHas('language', fn($q) => $q->where('code', 'en'))->first();
        if ($helloEn) {
            Attribute::firstOrCreate(['lexical_entry_id' => $helloEn->id, 'key' => 'pronunciation', 'value' => '/həˈloʊ/']);
        }

        // Add some links
        $holaEs = LexicalEntry::whereHas('token', fn($q) => $q->where('text', 'hola'))
            ->whereHas('language', fn($q) => $q->where('code', 'es'))->first();
        if ($helloEn && $holaEs) {
            Link::firstOrCreate([
                'source_lexical_entry_id' => $helloEn->id,
                'target_lexical_entry_id' => $holaEs->id,
                'type' => 'translation',
            ]);
        }
    }
}
