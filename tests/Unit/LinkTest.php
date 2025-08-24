<?php

use App\Models\Link;
use App\Models\LexicalEntry;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a link can be created', function () {
    $link = Link::factory()->create();
    expect($link)->toBeInstanceOf(Link::class);
});

test('a link has a source entry', function () {
    $entry = LexicalEntry::factory()->create();
    $link = Link::factory()->create(['source_lexical_entry_id' => $entry->id]);
    expect($link->sourceEntry)->toBeInstanceOf(LexicalEntry::class);
});

test('a link has a target entry', function () {
    $entry = LexicalEntry::factory()->create();
    $link = Link::factory()->create(['target_lexical_entry_id' => $entry->id]);
    expect($link->targetEntry)->toBeInstanceOf(LexicalEntry::class);
});
