<?php

namespace App\Console\Commands;

use App\Models\Attribute;
use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Link;
use App\Models\Token;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Stats extends Command
{
    protected $signature = 'ote:stats';

    protected $description = 'Shows statistics about the lexicon.';

    public function handle()
    {
        $this->info('Lexicon Statistics:');

        $stats = [
            ['Entity', 'Count'],
            ['Tokens', Token::count()],
            ['Languages', Language::count()],
            ['Lexical Entries', LexicalEntry::count()],
            ['Attributes', Attribute::count()],
            ['Links', Link::count()],
        ];

        $this->table(['Entity', 'Count'], $stats);

        $this->info('Entries per language:');

        $entriesPerLanguage = Language::withCount('lexicalEntries')
            ->get()
            ->map(function ($language) {
                return [$language->name, $language->lexical_entries_count];
            });

        if ($entriesPerLanguage->isEmpty()) {
            $this->line('No languages with entries.');
        } else {
            $this->table(['Language', 'Entries'], $entriesPerLanguage);
        }
    }
}
