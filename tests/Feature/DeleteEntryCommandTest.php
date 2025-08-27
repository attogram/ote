<?php

use App\Models\LexicalEntry;
use App\Models\Attribute;
use App\Models\Link;

test('the delete entry command deletes an entry and its associations', function () {
    $entry = LexicalEntry::factory()->create();
    $attribute = Attribute::factory()->create(['lexical_entry_id' => $entry->id]);
    $link = Link::factory()->create(['source_lexical_entry_id' => $entry->id]);

    $this->artisan('ote:delete-entry', ['id' => $entry->id])
        ->expectsOutput("Lexical entry with ID '{$entry->id}' has been deleted.")
        ->assertExitCode(0);

    $this->assertDatabaseMissing('lexical_entries', ['id' => $entry->id]);
    $this->assertDatabaseMissing('attributes', ['id' => $attribute->id]);
    $this->assertDatabaseMissing('links', ['id' => $link->id]);
});

test('the delete entry command handles non-existent entries', function () {
    $this->artisan('ote:delete-entry', ['id' => 999])
        ->expectsOutput("Lexical entry with ID '999' not found.")
        ->assertExitCode(1);
});
