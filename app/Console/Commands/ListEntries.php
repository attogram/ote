<?php

namespace App\Console\Commands;

use App\Models\LexicalEntry;
use Illuminate\Console\Command;

class ListEntries extends Command
{
    protected $signature = 'ote:list-entries';

    protected $description = 'Lists all lexical entries.';

    public function handle()
    {
        $entries = LexicalEntry::with(['token', 'language'])->get()->map(function ($entry) {
            return [
                'ID' => $entry->id,
                'Token' => $entry->token->text,
                'Language' => $entry->language->name,
            ];
        });

        $this->table(['ID', 'Token', 'Language'], $entries);
    }
}
