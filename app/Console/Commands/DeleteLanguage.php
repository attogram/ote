<?php

namespace App\Console\Commands;

use App\Models\Language;
use Illuminate\Console\Command;

class DeleteLanguage extends Command
{
    protected $signature = 'ote:delete-language {language : The code of the language to delete}';

    protected $description = 'Deletes a language and its associated lexical entries.';

    public function handle()
    {
        $languageCode = $this->argument('language');
        $language = Language::where('code', $languageCode)->first();

        if (!$language) {
            $this->error("Language '{$languageCode}' not found.");
            return 1;
        }

        // Manually delete related lexical entries because of potential model events.
        foreach ($language->lexicalEntries as $entry) {
            $entry->delete();
        }

        $language->delete();

        $this->info("Language '{$languageCode}' and its associated lexical entries have been deleted.");
    }
}
