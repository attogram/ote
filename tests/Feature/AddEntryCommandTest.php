<?php

use App\Models\Language;
use App\Models\Token;

test('the add entry command creates a new lexical entry', function () {
    $token = Token::factory()->create(['text' => 'hello']);
    $language = Language::factory()->create(['code' => 'en']);

    $this->artisan('ote:add-entry', ['token' => 'hello', 'language' => 'en'])
        ->expectsOutput('Lexical entry for \'hello\' in \'en\' created successfully with ID 1.')
        ->assertExitCode(0);

    $this->assertDatabaseHas('lexical_entries', [
        'token_id' => $token->id,
        'language_id' => $language->id,
    ]);
});

test('the add entry command handles missing token', function () {
    Language::factory()->create(['code' => 'en']);

    $this->artisan('ote:add-entry', ['token' => 'hello', 'language' => 'en'])
        ->expectsOutput('Token \'hello\' not found. Please add it first.')
        ->assertExitCode(1);
});

test('the add entry command handles missing language', function () {
    Token::factory()->create(['text' => 'hello']);

    $this->artisan('ote:add-entry', ['token' => 'hello', 'language' => 'en'])
        ->expectsOutput('Language \'en\' not found. Please add it first.')
        ->assertExitCode(1);
});
