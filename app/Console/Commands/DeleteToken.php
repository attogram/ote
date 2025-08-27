<?php

namespace App\Console\Commands;

use App\Models\Token;
use Illuminate\Console\Command;

class DeleteToken extends Command
{
    protected $signature = 'ote:delete-token {token : The token to delete}';

    protected $description = 'Deletes a token and its associated lexical entries.';

    public function handle()
    {
        $tokenText = $this->argument('token');
        $token = Token::where('text', $tokenText)->first();

        if (!$token) {
            $this->error("Token '{$tokenText}' not found.");
            return 1;
        }

        // Manually delete related lexical entries because of potential model events.
        foreach ($token->lexicalEntries as $entry) {
            $entry->delete();
        }

        $token->delete();

        $this->info("Token '{$tokenText}' and its associated lexical entries have been deleted.");
    }
}
