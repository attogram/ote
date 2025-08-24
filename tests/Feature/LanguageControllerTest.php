<?php

use App\Models\Language;

test('it displays a list of languages', function () {
    Language::factory()->count(3)->create();
    $response = $this->get('/languages');
    $response->assertStatus(200);
    $response->assertSee(Language::first()->name);
});

test('it displays the create language form', function () {
    $response = $this->get('/languages/create');
    $response->assertStatus(200);
});

test('it creates a new language', function () {
    $response = $this->post('/languages', ['code' => 'en', 'name' => 'English']);
    $response->assertRedirect('/languages');
    $this->assertDatabaseHas('languages', ['code' => 'en']);
});

test('it displays the edit language form', function () {
    $language = Language::factory()->create();
    $response = $this->get('/languages/' . $language->id . '/edit');
    $response->assertStatus(200);
    $response->assertSee($language->name);
});

test('it updates a language', function () {
    $language = Language::factory()->create();
    $response = $this->put('/languages/' . $language->id, ['code' => 'en', 'name' => 'English-updated']);
    $response->assertRedirect('/languages');
    $this->assertDatabaseHas('languages', ['name' => 'English-updated']);
});

test('it deletes a language', function () {
    $language = Language::factory()->create();
    $response = $this->delete('/languages/' . $language->id);
    $response->assertRedirect('/languages');
    $this->assertDatabaseMissing('languages', ['id' => $language->id]);
});
