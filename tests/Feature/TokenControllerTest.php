<?php

use App\Models\Token;
use App\Models\LexicalEntry;

test('it displays a list of tokens', function () {
    Token::factory()->count(3)->create();
    $response = $this->get('/tokens');
    $response->assertStatus(200);
    $response->assertSee(Token::first()->text);
});

test('it displays the create token form', function () {
    $response = $this->get('/tokens/create');
    $response->assertStatus(200);
});

test('it creates a new token', function () {
    $response = $this->post('/tokens', ['text' => 'new-token']);
    $response->assertRedirect('/tokens');
    $this->assertDatabaseHas('tokens', ['text' => 'new-token']);
});

test('it displays the edit token form', function () {
    $token = Token::factory()->create();
    $response = $this->get('/tokens/'.$token->id.'/edit');
    $response->assertStatus(200);
    $response->assertSee($token->text);
});

test('it updates a token', function () {
    $token = Token::factory()->create();
    $response = $this->put('/tokens/'.$token->id, ['text' => 'updated-token']);
    $response->assertRedirect('/tokens');
    $this->assertDatabaseHas('tokens', ['text' => 'updated-token']);
});

test('it deletes a token', function () {
    $token = Token::factory()->create();
    $response = $this->delete('/tokens/'.$token->id);
    $response->assertRedirect('/tokens');
    $this->assertDatabaseMissing('tokens', ['id' => $token->id]);
});

test('it shows a token and its lexical entries', function () {
    $entry = LexicalEntry::factory()->create();
    $token = $entry->token;

    $response = $this->get('/tokens/'.$token->id);

    $response->assertStatus(200);
    $response->assertSee($token->text);
    $response->assertSee($entry->language->name);
});
