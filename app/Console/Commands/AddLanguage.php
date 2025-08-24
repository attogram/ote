<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Language;

class AddLanguage extends Command
{
    protected $signature = 'ote:add-language {code} {name}';
    protected $description = 'Adds a new language to the lexicon.';

    public function handle()
    {
        $code = $this->argument('code');
        $name = $this->argument('name');

        try {
            $language = Language::create(['code' => $code, 'name' => $name]);
            $this->info("Language '{$name}' ({$code}) added successfully with ID {$language->id}.");
        } catch (\Exception $e) {
            $this->error("Failed to add language: A language with this code may already exist.");
        }
    }
}
