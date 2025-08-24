<?php

use App\Models\LexicalEntry;
use App\Models\Token;
use App\Models\Language;
use App\Models\Attribute;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a lexical entry can be created', function () {
    $entry = LexicalEntry::factory()->create();
    expect($entry)->toBeInstanceOf(LexicalEntry::class);
});

test('a lexical entry belongs to a token', function () {
    $token = Token::factory()->create();
    $entry = LexicalEntry::factory()->for($token)->create();
    expect($entry->token)->toBeInstanceOf(Token::class);
});

test('a lexical entry belongs to a language', function () {
    $language = Language::factory()->create();
    $entry = LexicalEntry::factory()->for($language)->create();
    expect($entry->language)->toBeInstanceOf(Language::class);
});

test('a lexical entry has many attributes', function () {
    $entry = LexicalEntry::factory()->has(Attribute::factory()->count(3))->create();
    expect($entry->attributes)->toHaveCount(3);
});

test('a lexical entry has many links', function () {
    $entry = LexicalEntry::factory()->has(Link::factory()->count(2), 'sourceEntry')->create();
    expect($entry->links)->toHaveCount(2);
});

test('a lexical entry has many linked from', function () {
    $entry = LexicalEntry::factory()->has(Link::factory()->count(4), 'targetEntry')->create();
    expect($entry->linkedFrom)->toHaveCount(4);
});
