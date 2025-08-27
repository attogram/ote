<?php

namespace App\Console\Commands;

use App\Models\Token;
use Illuminate\Console\Command;

class ShowToken extends Command
{
    protected $signature = 'ote:show-token {id : The ID of the token to show}';

    protected $description = 'Shows detailed information about a token.';

    public function handle()
    {
        $id = $this->argument('id');
        $token = Token::with('lexicalEntries.language')->find($id);

        if (!$token) {
            $this->error("Token with ID '{$id}' not found.");
            return 1;
        }

        $this->info("Token Details:");
        $this->line("  ID: {$token->id}");
        $this->line("  Text: {$token->text}");

        if ($token->lexicalEntries->isEmpty()) {
            $this->line("  No lexical entries for this token.");
            return 0;
        }

        $this->info("Lexical Entries:");
        $entries = $token->lexicalEntries->map(function ($entry) {
            return [
                'Entry ID' => $entry->id,
                'Language' => $entry->language->name,
            ];
        });

        $this->table(['Entry ID', 'Language'], $entries);
    }
}
