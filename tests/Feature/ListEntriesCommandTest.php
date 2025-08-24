<?php

use App\Models\LexicalEntry;

test('the list entries command shows a table of entries', function () {
    $entry1 = LexicalEntry::factory()->create();
    $entry2 = LexicalEntry::factory()->create();

    $this->artisan('ote:list-entries')
        ->expectsTable(
            ['ID', 'Token', 'Language'],
            [
                [$entry1->id, $entry1->token->text, $entry1->language->name],
                [$entry2->id, $entry2->token->text, $entry2->language->name],
            ]
        )
        ->assertExitCode(0);
});
