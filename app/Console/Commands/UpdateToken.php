<?php

namespace App\Console\Commands;

use App\Models\Token;
use Illuminate\Console\Command;

class UpdateToken extends Command
{
    protected $signature = 'ote:update-token {id : The ID of the token to update} {new_text : The new text for the token}';

    protected $description = 'Updates the text of a token.';

    public function handle()
    {
        $id = $this->argument('id');
        $newText = $this->argument('new_text');

        $token = Token::find($id);

        if (!$token) {
            $this->error("Token with ID '{$id}' not found.");
            return 1;
        }

        $token->text = $newText;
        $token->save();

        $this->info("Token with ID '{$id}' has been updated to '{$newText}'.");
    }
}
