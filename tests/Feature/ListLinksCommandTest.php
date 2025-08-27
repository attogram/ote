<?php

use App\Models\Link;

test('the list links command shows a table of links', function () {
    $link1 = Link::factory()->create();
    $link2 = Link::factory()->create();

    $this->artisan('ote:list-links')
        ->expectsTable(
            ['ID', 'Source Entry ID', 'Target Entry ID', 'Type'],
            [
                [$link1->id, $link1->source_lexical_entry_id, $link1->target_lexical_entry_id, $link1->type],
                [$link2->id, $link2->source_lexical_entry_id, $link2->target_lexical_entry_id, $link2->type],
            ]
        )
        ->assertExitCode(0);
});
