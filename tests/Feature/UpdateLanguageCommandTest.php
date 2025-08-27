<?php

use App\Models\Language;

test('the update language command updates a language', function () {
    $language = Language::factory()->create(['name' => 'Old Name']);

    $this->artisan('ote:update-language', [
        'id' => $language->id,
        'new_name' => 'New Name'
    ])
        ->expectsOutput("Language with ID '{$language->id}' has been updated to 'New Name'.")
        ->assertExitCode(0);

    $this->assertDatabaseHas('languages', [
        'id' => $language->id,
        'name' => 'New Name',
    ]);
});

test('the update language command handles non-existent languages', function () {
    $this->artisan('ote:update-language', [
        'id' => 999,
        'new_name' => 'New Name'
    ])
        ->expectsOutput("Language with ID '999' not found.")
        ->assertExitCode(1);
});
