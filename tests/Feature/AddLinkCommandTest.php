<?php

use App\Models\LexicalEntry;
use App\Models\Link;

test('the add link command creates a new link', function () {
    $sourceEntry = LexicalEntry::factory()->create();
    $targetEntry = LexicalEntry::factory()->create();

    $this->artisan('ote:add-link', [
        'source_id' => $sourceEntry->id,
        'target_id' => $targetEntry->id,
        'type' => 'translation',
    ])
        ->expectsOutput('Link created successfully: 1 -> 2 (translation).')
        ->assertExitCode(0);

    $this->assertDatabaseHas('links', [
        'source_lexical_entry_id' => $sourceEntry->id,
        'target_lexical_entry_id' => $targetEntry->id,
        'type' => 'translation',
    ]);
});

test('the add link command handles missing source entry', function () {
    $targetEntry = LexicalEntry::factory()->create();

    $this->artisan('ote:add-link', [
        'source_id' => 1,
        'target_id' => $targetEntry->id,
        'type' => 'translation',
    ])
        ->expectsOutput('Source entry with ID 1 not found.')
        ->assertExitCode(1);
});

test('the add link command handles missing target entry', function () {
    $sourceEntry = LexicalEntry::factory()->create();

    $this->artisan('ote:add-link', [
        'source_id' => $sourceEntry->id,
        'target_id' => 2,
        'type' => 'translation',
    ])
        ->expectsOutput('Target entry with ID 2 not found.')
        ->assertExitCode(1);
});
