<?php

use App\Models\Attribute;
use App\Models\LexicalEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('an attribute can be created', function () {
    $attribute = Attribute::factory()->create();
    expect($attribute)->toBeInstanceOf(Attribute::class);
});

test('an attribute belongs to a lexical entry', function () {
    $entry = LexicalEntry::factory()->create();
    $attribute = Attribute::factory()->for($entry)->create();
    expect($attribute->lexicalEntry)->toBeInstanceOf(LexicalEntry::class);
});
