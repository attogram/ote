<?php

use App\Models\Language;

test('the list languages command shows a table of languages', function () {
    $language1 = Language::factory()->create();
    $language2 = Language::factory()->create();

    $this->artisan('ote:list-languages')
        ->expectsTable(
            ['ID', 'Code', 'Name'],
            [
                [$language1->id, $language1->code, $language1->name],
                [$language2->id, $language2->code, $language2->name],
            ]
        )
        ->assertExitCode(0);
});
