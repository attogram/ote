<?php

use App\Models\Token;

test('the list tokens command shows a table of tokens', function () {
    $token1 = Token::factory()->create();
    $token2 = Token::factory()->create();

    $this->artisan('ote:list-tokens')
        ->expectsTable(
            ['ID', 'Text'],
            [
                [$token1->id, $token1->text],
                [$token2->id, $token2->text],
            ]
        )
        ->assertExitCode(0);
});
