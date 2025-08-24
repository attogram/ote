<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LexicalEntry;

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
                'Language' => $entry->language->name
            ];
        });

        $this->table(['ID', 'Token', 'Language'], $entries);
    }
}
