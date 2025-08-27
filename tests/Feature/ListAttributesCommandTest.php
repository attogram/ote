<?php

use App\Models\Attribute;

test('the list attributes command shows a table of attributes', function () {
    $attribute1 = Attribute::factory()->create();
    $attribute2 = Attribute::factory()->create();

    $this->artisan('ote:list-attributes')
        ->expectsTable(
            ['ID', 'Lexical Entry ID', 'Key', 'Value'],
            [
                [$attribute1->id, $attribute1->lexical_entry_id, $attribute1->key, $attribute1->value],
                [$attribute2->id, $attribute2->lexical_entry_id, $attribute2->key, $attribute2->value],
            ]
        )
        ->assertExitCode(0);
});
