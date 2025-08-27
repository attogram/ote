<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Token;
use App\Models\Language;

class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_homepage_loads_correctly()
    {
        Token::factory()->count(5)->create();
        Language::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertSee('Open Translation Engine v2');
        $response->assertSee('Lexicon Statistics');
        $response->assertSee('Tokens');
        $response->assertSee('5');
        $response->assertSee('Languages');
        $response->assertSee('3');
        $response->assertSee('Manage Tokens');
        $response->assertSee('Manage Languages');
        $response->assertSee('Manage Lexical Entries');
        $response->assertSee('Validate Data Integrity');
    }

    public function test_the_validation_page_loads_correctly_with_no_issues()
    {
        $response = $this->get('/validate');

        $response->assertStatus(200);
        $response->assertSee('Data Validation Results');
        $response->assertSee('All good!');
    }

    public function test_the_validation_page_shows_issues()
    {
        Token::factory()->create(['text' => 'apple']);
        Token::factory()->create(['text' => 'Apple']);

        $response = $this->get('/validate');

        $response->assertStatus(200);
        $response->assertSee('Data Validation Results');
        $response->assertSee('Issues found!');
        $response->assertSee('Case-Insensitive Duplicate Tokens');
        $response->assertSee('apple');
        $response->assertSee('Apple');
    }
}
