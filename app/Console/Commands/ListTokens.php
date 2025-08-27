<?php

namespace App\Console\Commands;

use App\Models\Token;
use Illuminate\Console\Command;

class ListTokens extends Command
{
    protected $signature = 'ote:list-tokens';

    protected $description = 'Lists all tokens.';

    public function handle()
    {
        $tokens = Token::all(['id', 'text']);

        $this->table(['ID', 'Text'], $tokens);
    }
}
