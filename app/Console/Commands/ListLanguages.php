<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class ListLanguages extends Command
{
    protected $signature = 'ote:list-languages';

    protected $description = 'Lists all languages.';

    public function handle()
    {
        $languages = Language::all(['id', 'code', 'name']);

        $this->table(['ID', 'Code', 'Name'], $languages);
    }
}
