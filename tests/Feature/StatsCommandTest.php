<?php

use App\Models\Token;
use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Attribute;
use App\Models\Link;

test('the stats command displays lexicon statistics', function () {
    Token::factory()->count(5)->create();
    Language::factory()->count(3)->create();
    LexicalEntry::factory()->count(10)->create();
    Attribute::factory()->count(15)->create();
    Link::factory()->count(20)->create();

    $this->artisan('ote:stats')
        ->expectsOutput('Lexicon Statistics:')
        ->expectsTable(
            ['Entity', 'Count'],
            [
                ['Tokens', 5],
                ['Languages', 3],
                ['Lexical Entries', 10],
                ['Attributes', 15],
                ['Links', 20],
            ]
        )
        ->expectsOutput('Entries per language:')
        ->assertExitCode(0);
});
