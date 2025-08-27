<?php

use App\Models\Token;
use App\Models\Language;
use Illuminate\Support\Facades\Artisan;

test('the validate command finds no issues with clean data', function () {
    $this->artisan('ote:validate')
        ->expectsOutput('Starting validation...')
        ->expectsOutput('Validation complete. No issues found.')
        ->assertExitCode(0);
});

test('the validate command finds duplicate tokens', function () {
    $token1 = Token::factory()->create(['text' => 'apple']);
    $token2 = Token::factory()->create(['text' => 'Apple']);

    $this->artisan('ote:validate')
        ->expectsOutputToContain('Starting validation...')
        ->expectsOutputToContain('Found case-insensitive duplicate tokens:')
        ->expectsOutputToContain('Apple')
        ->expectsOutputToContain('apple')
        ->expectsOutputToContain('Validation complete. Issues found.')
        ->assertExitCode(0);
});

test('the validate command finds unused tokens', function () {
    $token = Token::factory()->create();

    $this->artisan('ote:validate')
        ->expectsOutput('Starting validation...')
        ->expectsOutput('Found unused tokens:')
        ->expectsTable(['ID', 'Text'], [[$token->id, $token->text]])
        ->expectsOutput('Validation complete. Issues found.')
        ->assertExitCode(0);
});

test('the validate command finds unused languages', function () {
    $language = Language::factory()->create();

    $this->artisan('ote:validate')
        ->expectsOutput('Starting validation...')
        ->expectsOutput('Found unused languages:')
        ->expectsTable(['ID', 'Name'], [[$language->id, $language->name]])
        ->expectsOutput('Validation complete. Issues found.')
        ->assertExitCode(0);
});
