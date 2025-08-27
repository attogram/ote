<?php

use App\Models\Token;

test('the update token command updates a token', function () {
    $token = Token::factory()->create(['text' => 'old-text']);

    $this->artisan('ote:update-token', [
        'id' => $token->id,
        'new_text' => 'new-text'
    ])
        ->expectsOutput("Token with ID '{$token->id}' has been updated to 'new-text'.")
        ->assertExitCode(0);

    $this->assertDatabaseHas('tokens', [
        'id' => $token->id,
        'text' => 'new-text',
    ]);
});

test('the update token command handles non-existent tokens', function () {
    $this->artisan('ote:update-token', [
        'id' => 999,
        'new_text' => 'new-text'
    ])
        ->expectsOutput("Token with ID '999' not found.")
        ->assertExitCode(1);
});
