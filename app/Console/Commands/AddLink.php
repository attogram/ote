<?php

namespace App\Console\Commands;

use App\Models\LexicalEntry;
use App\Models\Link;
use Illuminate\Console\Command;

class AddLink extends Command
{
    protected $signature = 'ote:add-link {source_id} {target_id} {type}';

    protected $description = 'Links two lexical entries with a specified relationship type.';

    public function handle()
    {
        $sourceId = $this->argument('source_id');
        $targetId = $this->argument('target_id');
        $type = $this->argument('type');

        $sourceEntry = LexicalEntry::find($sourceId);
        $targetEntry = LexicalEntry::find($targetId);

        if (! $sourceEntry) {
            $this->error("Source entry with ID {$sourceId} not found.");

            return Command::FAILURE;
        }

        if (! $targetEntry) {
            $this->error("Target entry with ID {$targetId} not found.");

            return Command::FAILURE;
        }

        $link = Link::firstOrCreate([
            'source_lexical_entry_id' => $sourceId,
            'target_lexical_entry_id' => $targetId,
            'type' => $type,
        ]);

        if ($link->wasRecentlyCreated) {
            $this->info("Link created successfully: {$sourceId} -> {$targetId} ({$type}).");
        } else {
            $this->warn('Link already exists.');
        }
    }
}
