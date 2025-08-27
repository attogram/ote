<?php

use App\Models\Token;
use App\Models\LexicalEntry;

test('the delete token command deletes a token and its entries', function () {
    $entry = LexicalEntry::factory()->create();
    $token = $entry->token;

    $this->artisan('ote:delete-token', ['token' => $token->text])
        ->expectsOutput("Token '{$token->text}' and its associated lexical entries have been deleted.")
        ->assertExitCode(0);

    $this->assertDatabaseMissing('tokens', ['id' => $token->id]);
    $this->assertDatabaseMissing('lexical_entries', ['id' => $entry->id]);
});

test('the delete token command handles non-existent tokens', function () {
    $this->artisan('ote:delete-token', ['token' => 'non-existent-token'])
        ->expectsOutput("Token 'non-existent-token' not found.")
        ->assertExitCode(1);
});
