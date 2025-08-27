<?php

use App\Models\Language;
use App\Models\LexicalEntry;

test('the show language command displays language details', function () {
    $entry = LexicalEntry::factory()->create();
    $language = $entry->language;

    $this->artisan('ote:show-language', ['id' => $language->id])
        ->expectsOutput("Language Details:")
        ->expectsOutput("  ID: {$language->id}")
        ->expectsOutput("  Code: {$language->code}")
        ->expectsOutput("  Name: {$language->name}")
        ->expectsOutput("Lexical Entries:")
        ->expectsTable(
            ['Entry ID', 'Token'],
            [
                [$entry->id, $entry->token->text],
            ]
        )
        ->assertExitCode(0);
});

test('the show language command handles non-existent languages', function () {
    $this->artisan('ote:show-language', ['id' => 999])
        ->expectsOutput("Language with ID '999' not found.")
        ->assertExitCode(1);
});
