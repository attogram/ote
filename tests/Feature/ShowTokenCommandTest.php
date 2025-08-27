<?php

use App\Models\Token;
use App\Models\LexicalEntry;

test('the show token command displays token details', function () {
    $entry = LexicalEntry::factory()->create();
    $token = $entry->token;

    $this->artisan('ote:show-token', ['id' => $token->id])
        ->expectsOutput("Token Details:")
        ->expectsOutput("  ID: {$token->id}")
        ->expectsOutput("  Text: {$token->text}")
        ->expectsOutput("Lexical Entries:")
        ->expectsTable(
            ['Entry ID', 'Language'],
            [
                [$entry->id, $entry->language->name],
            ]
        )
        ->assertExitCode(0);
});

test('the show token command handles non-existent tokens', function () {
    $this->artisan('ote:show-token', ['id' => 999])
        ->expectsOutput("Token with ID '999' not found.")
        ->assertExitCode(1);
});
