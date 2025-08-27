<?php

namespace App\Console\Commands;

use App\Models\LexicalEntry;
use Illuminate\Console\Command;

class ShowEntry extends Command
{
    protected $signature = 'ote:show-entry {id : The ID of the lexical entry to show}';

    protected $description = 'Shows detailed information about a lexical entry.';

    public function handle()
    {
        $id = $this->argument('id');
        $entry = LexicalEntry::with(['token', 'language', 'attributes', 'links.targetEntry.token', 'linkedFrom.sourceEntry.token'])->find($id);

        if (!$entry) {
            $this->error("Lexical entry with ID '{$id}' not found.");
            return 1;
        }

        $this->info("Lexical Entry Details:");
        $this->line("  ID: {$entry->id}");
        $this->line("  Token: {$entry->token->text}");
        $this->line("  Language: {$entry->language->name}");

        if (!$entry->attributes->isEmpty()) {
            $this->info("Attributes:");
            $this->table(['Key', 'Value'], $entry->attributes->map(function ($attr) {
                return [$attr->key, $attr->value];
            }));
        }

        if (!$entry->links->isEmpty()) {
            $this->info("Links (Source):");
            $this->table(['Target Entry ID', 'Target Token', 'Type'], $entry->links->map(function ($link) {
                return [$link->target_lexical_entry_id, $link->targetEntry->token->text, $link->type];
            }));
        }

        if (!$entry->linkedFrom->isEmpty()) {
            $this->info("Links (Target):");
            $this->table(['Source Entry ID', 'Source Token', 'Type'], $entry->linkedFrom->map(function ($link) {
                return [$link->source_lexical_entry_id, $link->sourceEntry->token->text, $link->type];
            }));
        }
    }
}
