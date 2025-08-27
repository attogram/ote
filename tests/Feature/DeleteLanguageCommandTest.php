<?php

use App\Models\Language;
use App\Models\LexicalEntry;

test('the delete language command deletes a language and its entries', function () {
    $entry = LexicalEntry::factory()->create();
    $language = $entry->language;

    $this->artisan('ote:delete-language', ['language' => $language->code])
        ->expectsOutput("Language '{$language->code}' and its associated lexical entries have been deleted.")
        ->assertExitCode(0);

    $this->assertDatabaseMissing('languages', ['id' => $language->id]);
    $this->assertDatabaseMissing('lexical_entries', ['id' => $entry->id]);
});

test('the delete language command handles non-existent languages', function () {
    $this->artisan('ote:delete-language', ['language' => 'non-existent-language'])
        ->expectsOutput("Language 'non-existent-language' not found.")
        ->assertExitCode(1);
});
