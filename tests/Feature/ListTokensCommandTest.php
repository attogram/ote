<?php

use App\Models\Token;

test('the list tokens command shows a table of tokens', function () {
    $token1 = Token::factory()->create();
    $token2 = Token::factory()->create();

    $this->artisan('ote:list-tokens')
        ->expectsOutputToContain($token1->text)
        ->expectsOutputToContain($token2->text)
        ->assertExitCode(0);
});
