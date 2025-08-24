<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\LexicalEntry;
use App\Models\Link;
use Illuminate\Support\Facades\File;

class ExportOteFile extends Command
{
    protected $signature = 'ote:export-ote-file {path} {source_lang_code} {target_lang_code}';
    protected $description = 'Exports a translation list in the old OTE format.';

    public function handle()
    {
        $path = $this->argument('path');
        $sourceLangCode = $this->argument('source_lang_code');
        $targetLangCode = $this->argument('target_lang_code');

        $links = Link::whereHas('sourceEntry.language', function ($query) use ($sourceLangCode) {
            $query->where('code', $sourceLangCode);
        })->whereHas('targetEntry.language', function ($query) use ($targetLangCode) {
            $query->where('code', $targetLangCode);
        })->where('type', 'translation')->with(['sourceEntry.token', 'targetEntry.token'])->get();

        if ($links->isEmpty()) {
            $this->error("No translation links found for {$sourceLangCode} to {$targetLangCode}.");
            return Command::FAILURE;
        }

        $content = "# {$sourceLangCode} > {$targetLangCode}\n";
        $content .= "# {$links->count()} Word Pairs\n";
        $content .= "# Exported from OTE 2.0\n";
        $content .= "# " . date('D, d M Y H:i:s T') . "\n";
        $content .= "#\n";
        $content .= "# delimiter: =\n";

        foreach ($links as $link) {
            $content .= "{$link->sourceEntry->token->text}={$link->targetEntry->token->text}\n";
        }

        File::put($path, $content);
        $this->info("Successfully exported 2 word pairs to /app/storage/app/test.ote");
    }
}
