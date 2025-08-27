<?php

use App\Models\LexicalEntry;
use App\Models\Attribute;
use App\Models\Link;

test('the show entry command displays entry details', function () {
    $entry = LexicalEntry::factory()->create();
    $attribute = Attribute::factory()->create(['lexical_entry_id' => $entry->id]);
    $link = Link::factory()->create(['source_lexical_entry_id' => $entry->id]);

    $this->artisan('ote:show-entry', ['id' => $entry->id])
        ->expectsOutput("Lexical Entry Details:")
        ->expectsOutput("  ID: {$entry->id}")
        ->expectsOutput("  Token: {$entry->token->text}")
        ->expectsOutput("  Language: {$entry->language->name}")
        ->expectsOutput("Attributes:")
        ->expectsTable(['Key', 'Value'], [[$attribute->key, $attribute->value]])
        ->expectsOutput("Links (Source):")
        ->expectsTable(['Target Entry ID', 'Target Token', 'Type'], [[$link->target_lexical_entry_id, $link->targetEntry->token->text, $link->type]])
        ->assertExitCode(0);
});

test('the show entry command handles non-existent entries', function () {
    $this->artisan('ote:show-entry', ['id' => 999])
        ->expectsOutput("Lexical entry with ID '999' not found.")
        ->assertExitCode(1);
});
