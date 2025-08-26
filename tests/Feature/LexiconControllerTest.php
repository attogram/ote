<?php

use App\Models\Attribute;
use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Link;
use App\Models\Token;

// Lexical Entry CRUD
test('it displays a list of lexical entries', function () {
    LexicalEntry::factory()->count(3)->create();
    $response = $this->get('/lexicon');
    $response->assertStatus(200);
    $response->assertSee(LexicalEntry::first()->token->text);
});

test('it displays the create lexical entry form', function () {
    $response = $this->get('/lexicon/create');
    $response->assertStatus(200);
});

test('it stores a new lexical entry', function () {
    $token = Token::factory()->create();
    $language = Language::factory()->create();
    $response = $this->post('/lexicon', ['token_id' => $token->id, 'language_id' => $language->id]);
    $response->assertRedirect('/lexicon');
    $this->assertDatabaseHas('lexical_entries', ['token_id' => $token->id]);
});

test('it shows a lexical entry', function () {
    $entry = LexicalEntry::factory()->create();
    $response = $this->get('/lexicon/'.$entry->id);
    $response->assertStatus(200);
    $response->assertSee($entry->token->text);
});

test('it displays the edit lexical entry form', function () {
    $entry = LexicalEntry::factory()->create();
    $response = $this->get('/lexicon/'.$entry->id.'/edit');
    $response->assertStatus(200);
    $response->assertSee($entry->token->text);
});

test('it updates a lexical entry', function () {
    $entry = LexicalEntry::factory()->create();
    $token = Token::factory()->create();
    $language = Language::factory()->create();
    $response = $this->put('/lexicon/'.$entry->id, ['token_id' => $token->id, 'language_id' => $language->id]);
    $response->assertRedirect('/lexicon');
    $this->assertDatabaseHas('lexical_entries', ['id' => $entry->id, 'token_id' => $token->id]);
});

test('it deletes a lexical entry', function () {
    $entry = LexicalEntry::factory()->create();
    $response = $this->delete('/lexicon/'.$entry->id);
    $response->assertRedirect('/lexicon');
    $this->assertDatabaseMissing('lexical_entries', ['id' => $entry->id]);
});

// Attribute CRUD
test('it displays the create attribute form', function () {
    $entry = LexicalEntry::factory()->create();
    $response = $this->get('/lexicon/'.$entry->id.'/add-attribute');
    $response->assertStatus(200);
});

test('it stores a new attribute', function () {
    $entry = LexicalEntry::factory()->create();
    $response = $this->post('/lexicon/'.$entry->id.'/store-attribute', ['key' => 'pos', 'value' => 'noun']);
    $response->assertRedirect('/lexicon/'.$entry->id);
    $this->assertDatabaseHas('attributes', ['key' => 'pos']);
});

test('it displays the edit attribute form', function () {
    $attribute = Attribute::factory()->create();
    $response = $this->get('/lexicon/'.$attribute->lexical_entry_id.'/edit-attribute/'.$attribute->id);
    $response->assertStatus(200);
    $response->assertSee($attribute->key);
});

test('it updates an attribute', function () {
    $attribute = Attribute::factory()->create();
    $response = $this->put('/lexicon/'.$attribute->lexical_entry_id.'/update-attribute/'.$attribute->id, ['key' => 'pos-updated', 'value' => 'verb']);
    $response->assertRedirect('/lexicon/'.$attribute->lexical_entry_id);
    $this->assertDatabaseHas('attributes', ['key' => 'pos-updated']);
});

test('it deletes an attribute', function () {
    $attribute = Attribute::factory()->create();
    $response = $this->delete('/lexicon/'.$attribute->lexical_entry_id.'/delete-attribute/'.$attribute->id);
    $response->assertRedirect('/lexicon/'.$attribute->lexical_entry_id);
    $this->assertDatabaseMissing('attributes', ['id' => $attribute->id]);
});

// Link CRUD
test('it displays the create link form', function () {
    $entry = LexicalEntry::factory()->create();
    $response = $this->get('/lexicon/'.$entry->id.'/add-link');
    $response->assertStatus(200);
});

test('it stores a new link', function () {
    $entry = LexicalEntry::factory()->create();
    $target = LexicalEntry::factory()->create();
    $response = $this->post('/lexicon/'.$entry->id.'/store-link', ['target_lexical_entry_id' => $target->id, 'type' => 'translation']);
    $response->assertRedirect('/lexicon/'.$entry->id);
    $this->assertDatabaseHas('links', ['type' => 'translation']);
});

test('it displays the edit link form', function () {
    $link = Link::factory()->create();
    $response = $this->get('/lexicon/'.$link->source_lexical_entry_id.'/edit-link/'.$link->id);
    $response->assertStatus(200);
    $response->assertSee($link->type);
});

test('it updates a link', function () {
    $link = Link::factory()->create();
    $target = LexicalEntry::factory()->create();
    $response = $this->put('/lexicon/'.$link->source_lexical_entry_id.'/update-link/'.$link->id, ['target_lexical_entry_id' => $target->id, 'type' => 'synonym']);
    $response->assertRedirect('/lexicon/'.$link->source_lexical_entry_id);
    $this->assertDatabaseHas('links', ['type' => 'synonym']);
});

test('it deletes a link', function () {
    $link = Link::factory()->create();
    $response = $this->delete('/lexicon/'.$link->source_lexical_entry_id.'/delete-link/'.$link->id);
    $response->assertRedirect('/lexicon/'.$link->source_lexical_entry_id);
    $this->assertDatabaseMissing('links', ['id' => $link->id]);
});
