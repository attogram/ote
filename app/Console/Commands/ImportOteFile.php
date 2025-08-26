<?php

namespace App\Console\Commands;

use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Link;
use App\Models\Token;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ImportOteFile extends Command
{
    protected $signature = 'ote:import-ote-file {path}';

    protected $description = 'Imports data from a legacy OTE word pair file.';

    public function handle()
    {
        $path = $this->argument('path');

        if (! File::exists($path)) {
            $this->error("File not found at: {$path}");

            return Command::FAILURE;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $langCodes = [];
        $delimiter = '=';
        $wordPairs = [];

        foreach ($lines as $line) {
            if (str_starts_with($line, '#')) {
                if (str_contains($line, 'eng > nld')) {
                    $parts = explode('>', trim(str_replace('#', '', $line)));
                    $langCodes['source'] = trim($parts[0]);
                    $langCodes['target'] = trim($parts[1]);
                } elseif (str_contains($line, 'delimiter:')) {
                    $delimiter = trim(str_replace('# delimiter:', '', $line));
                }

                continue;
            }
            $wordPairs[] = $line;
        }

        if (empty($langCodes)) {
            $this->error('Could not determine source and target languages from the file metadata.');

            return Command::FAILURE;
        }

        $sourceLang = Language::firstOrCreate(['code' => $langCodes['source'], 'name' => '']);
        $targetLang = Language::firstOrCreate(['code' => $langCodes['target'], 'name' => '']);

        $this->info("Starting import of '{$langCodes['source']}' to '{$langCodes['target']}' translations...");
        $bar = $this->output->createProgressBar(count($wordPairs));
        $bar->start();

        foreach ($wordPairs as $pair) {
            $parts = explode($delimiter, $pair, 2);
            if (count($parts) !== 2) {
                continue;
            }
            $sourceWord = trim($parts[0]);
            $targetWord = trim($parts[1]);

            $sourceToken = Token::firstOrCreate(['text' => $sourceWord]);
            $targetToken = Token::firstOrCreate(['text' => $targetWord]);

            $sourceEntry = LexicalEntry::firstOrCreate([
                'token_id' => $sourceToken->id,
                'language_id' => $sourceLang->id,
            ]);
            $targetEntry = LexicalEntry::firstOrCreate([
                'token_id' => $targetToken->id,
                'language_id' => $targetLang->id,
            ]);

            Link::firstOrCreate([
                'source_lexical_entry_id' => $sourceEntry->id,
                'target_lexical_entry_id' => $targetEntry->id,
                'type' => 'translation',
            ]);
        }

        $bar->finish();
        $this->newLine();
        $this->info('Data import completed successfully!');
    }
}
