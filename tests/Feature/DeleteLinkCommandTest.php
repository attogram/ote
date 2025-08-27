<?php

use App\Models\Link;

test('the delete link command deletes a link', function () {
    $link = Link::factory()->create();

    $this->artisan('ote:delete-link', ['id' => $link->id])
        ->expectsOutput("Link with ID '{$link->id}' has been deleted.")
        ->assertExitCode(0);

    $this->assertDatabaseMissing('links', ['id' => $link->id]);
});

test('the delete link command handles non-existent links', function () {
    $this->artisan('ote:delete-link', ['id' => 999])
        ->expectsOutput("Link with ID '999' not found.")
        ->assertExitCode(1);
});
