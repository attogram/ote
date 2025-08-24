<?php

use App\Models\Language;

test('the add language command creates a new language', function () {
    $this->artisan('ote:add-language', ['code' => 'en', 'name' => 'English'])
        ->expectsOutput('Language \'English\' (en) added successfully with ID 1.')
        ->assertExitCode(0);

    $this->assertDatabaseHas('languages', ['code' => 'en', 'name' => 'English']);
});

test('the add language command handles existing languages', function () {
    Language::factory()->create(['code' => 'en', 'name' => 'English']);

    $this->artisan('ote:add-language', ['code' => 'en', 'name' => 'English'])
        ->expectsOutput('Failed to add language: A language with this code may already exist.')
        ->assertExitCode(0);
});
