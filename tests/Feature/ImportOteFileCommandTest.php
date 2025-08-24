<?php

use Illuminate\Support\Facades\File;

test('the import ote file command imports data from a file', function () {
    $filePath = storage_path('app/test.ote');
    $content = "# eng > nld\nhello=hallo\nworld=wereld";
    File::put($filePath, $content);

    $this->artisan('ote:import-ote-file', ['path' => $filePath])
        ->expectsOutput('Starting import of \'eng\' to \'nld\' translations...')
        ->expectsOutput('Data import completed successfully!')
        ->assertExitCode(0);

    $this->assertDatabaseHas('tokens', ['text' => 'hello']);
    $this->assertDatabaseHas('tokens', ['text' => 'hallo']);
    $this->assertDatabaseHas('tokens', ['text' => 'world']);
    $this->assertDatabaseHas('tokens', ['text' => 'wereld']);
    $this->assertDatabaseHas('languages', ['code' => 'eng']);
    $this->assertDatabaseHas('languages', ['code' => 'nld']);
});

test('the import ote file command handles missing file', function () {
    $this->artisan('ote:import-ote-file', ['path' => 'non-existent-file.ote'])
        ->expectsOutput('File not found at: non-existent-file.ote')
        ->assertExitCode(1);
});
