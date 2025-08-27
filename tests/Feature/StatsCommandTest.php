<?php

use App\Models\Token;
use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Attribute;
use App\Models\Link;
use Illuminate\Support\Facades\Artisan;

test('the stats command displays lexicon statistics', function () {
    Token::factory()->count(5)->create();
    Language::factory()->count(3)->create();
    LexicalEntry::factory()->count(10)->create();
    Attribute::factory()->count(15)->create();
    Link::factory()->count(20)->create();

    $this->artisan('ote:stats')
        ->expectsOutputToContain('Lexicon Statistics')
        ->expectsOutputToContain('Tokens')
        ->expectsOutputToContain('Languages')
        ->expectsOutputToContain('Lexical Entries')
        ->expectsOutputToContain('Attributes')
        ->expectsOutputToContain('Links')
        ->expectsOutputToContain('Entries per language')
        ->assertExitCode(0);
});
