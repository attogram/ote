<?php

use App\Models\Attribute;

test('the delete attribute command deletes an attribute', function () {
    $attribute = Attribute::factory()->create();

    $this->artisan('ote:delete-attribute', ['id' => $attribute->id])
        ->expectsOutput("Attribute with ID '{$attribute->id}' has been deleted.")
        ->assertExitCode(0);

    $this->assertDatabaseMissing('attributes', ['id' => $attribute->id]);
});

test('the delete attribute command handles non-existent attributes', function () {
    $this->artisan('ote:delete-attribute', ['id' => 999])
        ->expectsOutput("Attribute with ID '999' not found.")
        ->assertExitCode(1);
});
