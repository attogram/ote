<?php

namespace App\Console\Commands;

use App\Models\LexicalEntry;
use Illuminate\Console\Command;

class DeleteEntry extends Command
{
    protected $signature = 'ote:delete-entry {id : The ID of the lexical entry to delete}';

    protected $description = 'Deletes a lexical entry and its associated attributes and links.';

    public function handle()
    {
        $id = $this->argument('id');
        $entry = LexicalEntry::find($id);

        if (!$entry) {
            $this->error("Lexical entry with ID '{$id}' not found.");
            return 1;
        }

        // Manually delete related attributes and links
        $entry->attributes()->delete();
        $entry->links()->delete();
        $entry->linkedFrom()->delete();

        $entry->delete();

        $this->info("Lexical entry with ID '{$id}' has been deleted.");
    }
}
