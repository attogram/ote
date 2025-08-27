<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class UpdateLanguage extends Command
{
    protected $signature = 'ote:update-language {id : The ID of the language to update} {new_name : The new name for the language}';

    protected $description = 'Updates the name of a language.';

    public function handle()
    {
        $id = $this->argument('id');
        $newName = $this->argument('new_name');

        $language = Language::find($id);

        if (!$language) {
            $this->error("Language with ID '{$id}' not found.");
            return 1;
        }

        $language->name = $newName;
        $language->save();

        $this->info("Language with ID '{$id}' has been updated to '{$newName}'.");
    }
}
