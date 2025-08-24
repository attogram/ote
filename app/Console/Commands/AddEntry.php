<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Token;
use App\Models\Language;
use App\Models\LexicalEntry;

class AddEntry extends Command
{
    protected $signature = 'ote:add-entry {token} {language}';
    protected $description = 'Creates a new lexical entry linking a token and a language.';

    public function handle()
    {
        $tokenText = $this->argument('token');
        $langCode = $this->argument('language');

        $token = Token::where('text', $tokenText)->first();
        $language = Language::where('code', $langCode)->first();

        if (!$token) {
            $this->error("Token '{$tokenText}' not found. Please add it first.");
            return Command::FAILURE;
        }

        if (!$language) {
            $this->error("Language '{$langCode}' not found. Please add it first.");
            return Command::FAILURE;
        }

        $entry = LexicalEntry::firstOrCreate([
            'token_id' => $token->id,
            'language_id' => $language->id,
        ]);

        if ($entry->wasRecentlyCreated) {
            $this->info("Lexical entry for '{$tokenText}' in '{$langCode}' created successfully with ID {$entry->id}.");
        } else {
            $this->warn("Lexical entry already exists with ID {$entry->id}.");
        }
    }
}
