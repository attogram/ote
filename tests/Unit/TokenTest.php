<?php

use App\Models\Token;
use App\Models\LexicalEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a token can be created', function () {
    $token = Token::factory()->create(['text' => 'hello']);
    expect($token->text)->toBe('hello');
});

test('a token has many lexical entries', function () {
    $token = Token::factory()->has(LexicalEntry::factory()->count(3))->create();
    expect($token->lexicalEntries)->toHaveCount(3);
});
