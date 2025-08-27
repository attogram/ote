<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class ShowLanguage extends Command
{
    protected $signature = 'ote:show-language {id : The ID of the language to show}';

    protected $description = 'Shows detailed information about a language.';

    public function handle()
    {
        $id = $this->argument('id');
        $language = Language::with('lexicalEntries.token')->find($id);

        if (!$language) {
            $this->error("Language with ID '{$id}' not found.");
            return 1;
        }

        $this->info("Language Details:");
        $this->line("  ID: {$language->id}");
        $this->line("  Code: {$language->code}");
        $this->line("  Name: {$language->name}");

        if ($language->lexicalEntries->isEmpty()) {
            $this->line("  No lexical entries for this language.");
            return 0;
        }

        $this->info("Lexical Entries:");
        $entries = $language->lexicalEntries->map(function ($entry) {
            return [
                'Entry ID' => $entry->id,
                'Token' => $entry->token->text,
            ];
        });

        $this->table(['Entry ID', 'Token'], $entries);
    }
}
