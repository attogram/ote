<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Token;

class AddToken extends Command
{
    protected $signature = 'ote:add-token {text}';
    protected $description = 'Adds a new unique token to the lexicon.';

    public function handle()
    {
        $text = $this->argument('text');

        $token = Token::firstOrCreate(['text' => $text]);

        if ($token->wasRecentlyCreated) {
            $this->info("Token '{$text}' added successfully with ID {$token->id}.");
        } else {
            $this->warn("Token '{$text}' already exists with ID {$token->id}.");
        }
    }
}
