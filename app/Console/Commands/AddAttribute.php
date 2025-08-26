<?php

namespace App\Console\Commands;

use App\Models\LexicalEntry;
use Illuminate\Console\Command;

class AddAttribute extends Command
{
    protected $signature = 'ote:add-attribute {entry_id} {key} {value}';

    protected $description = 'Adds an attribute to a lexical entry.';

    public function handle()
    {
        $entryId = $this->argument('entry_id');
        $key = $this->argument('key');
        $value = $this->argument('value');

        $entry = LexicalEntry::find($entryId);

        if (! $entry) {
            $this->error("Lexical entry with ID {$entryId} not found.");

            return Command::FAILURE;
        }

        $attribute = $entry->attributes()->create(['key' => $key, 'value' => $value]);

        $this->info("Attribute '{$key}' added to entry {$entryId} with ID {$attribute->id}.");
    }
}
