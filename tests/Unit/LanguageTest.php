<?php

use App\Models\Language;
use App\Models\LexicalEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a language can be created', function () {
    $language = Language::factory()->create(['name' => 'English']);
    expect($language->name)->toBe('English');
});

test('a language has many lexical entries', function () {
    $language = Language::factory()->has(LexicalEntry::factory()->count(5))->create();
    expect($language->lexicalEntries)->toHaveCount(5);
});
