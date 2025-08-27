<?php

namespace App\Console\Commands;

use App\Models\Language;
use App\Models\Token;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Validate extends Command
{
    protected $signature = 'ote:validate';

    protected $description = 'Validates the integrity of the lexicon data.';

    public function handle()
    {
        $this->info('Starting validation...');
        $foundIssues = false;

        // Check for duplicate tokens (case-insensitive)
        $duplicateTexts = DB::table('tokens')
            ->select(DB::raw('LOWER(text) as lower_text'))
            ->groupBy('lower_text')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('lower_text');

        if ($duplicateTexts->isNotEmpty()) {
            $duplicateTokens = Token::whereIn(DB::raw('LOWER(text)'), $duplicateTexts)->orderBy('text')->get();
            if ($duplicateTokens->isNotEmpty()) {
                $foundIssues = true;
                $this->warn('Found case-insensitive duplicate tokens:');
                $this->table(['ID', 'Text'], $duplicateTokens->map(fn($t) => [$t->id, $t->text]));
            }
        }

        // Check for duplicate language names
        $duplicateLangs = Language::select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicateLangs->isNotEmpty()) {
            $foundIssues = true;
            $this->warn('Found duplicate language names:');
            $this->table(['Name'], $duplicateLangs->map(fn($l) => [$l->name]));
        }

        // Check for unused tokens
        $unusedTokens = Token::whereDoesntHave('lexicalEntries')->get();
        if ($unusedTokens->isNotEmpty()) {
            $foundIssues = true;
            $this->warn('Found unused tokens:');
            $this->table(['ID', 'Text'], $unusedTokens->map(fn($t) => [$t->id, $t->text]));
        }

        // Check for unused languages
        $unusedLangs = Language::whereDoesntHave('lexicalEntries')->get();
        if ($unusedLangs->isNotEmpty()) {
            $foundIssues = true;
            $this->warn('Found unused languages:');
            $this->table(['ID', 'Name'], $unusedLangs->map(fn($l) => [$l->id, $l->name]));
        }

        if (!$foundIssues) {
            $this->info('Validation complete. No issues found.');
        } else {
            $this->error('Validation complete. Issues found.');
        }
    }
}
