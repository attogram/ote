<?php

use App\Models\LexicalEntry;

test('the add attribute command creates a new attribute', function () {
    $entry = LexicalEntry::factory()->create();

    $this->artisan('ote:add-attribute', [
        'entry_id' => $entry->id,
        'key' => 'pos',
        'value' => 'noun',
    ])
        ->expectsOutput('Attribute \'pos\' added to entry 1 with ID 1.')
        ->assertExitCode(0);

    $this->assertDatabaseHas('attributes', [
        'lexical_entry_id' => $entry->id,
        'key' => 'pos',
        'value' => 'noun',
    ]);
});

test('the add attribute command handles missing lexical entry', function () {
    $this->artisan('ote:add-attribute', [
        'entry_id' => 1,
        'key' => 'pos',
        'value' => 'noun',
    ])
        ->expectsOutput('Lexical entry with ID 1 not found.')
        ->assertExitCode(1);
});
