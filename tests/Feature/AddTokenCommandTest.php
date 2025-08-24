<?php

use App\Models\Token;

test('the add token command creates a new token', function () {
    $this->artisan('ote:add-token', ['text' => 'new-token'])
        ->expectsOutput('Token \'new-token\' added successfully with ID 1.')
        ->assertExitCode(0);

    $this->assertDatabaseHas('tokens', ['text' => 'new-token']);
});

test('the add token command handles existing tokens', function () {
    $token = Token::factory()->create(['text' => 'existing-token']);

    $this->artisan('ote:add-token', ['text' => 'existing-token'])
        ->expectsOutput('Token \'existing-token\' already exists with ID 1.')
        ->assertExitCode(0);
});
