<?php

namespace App\Console\Commands;

use App\Models\Link;
use Illuminate\Console\Command;

class ListLinks extends Command
{
    protected $signature = 'ote:list-links';

    protected $description = 'Lists all links.';

    public function handle()
    {
        $links = Link::with(['sourceEntry', 'targetEntry'])->get()->map(function ($link) {
            return [
                'ID' => $link->id,
                'Source Entry ID' => $link->source_lexical_entry_id,
                'Target Entry ID' => $link->target_lexical_entry_id,
                'Type' => $link->type,
            ];
        });

        $this->table(['ID', 'Source Entry ID', 'Target Entry ID', 'Type'], $links);
    }
}
