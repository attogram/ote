
### MVP Requirements

To be a complete and working MVP, OTE 2.0 must support the following:

1.1. **Data Management:** The ability to add, edit, and list **Tokens**, **Languages**, **Lexical Entries**, **Attributes**, and **Links** via both CLI tools and a full web UI.

1.2. **Data Migration:** Import and export data in the standard OTE v1 format.

1.3. **Relational Model:** A functional database schema that supports the relationships between all data types.

1.4. **Web Interface:** A complete UI for viewing, adding, and editing all data types.

1.5. **Full UTF8 Support:** The entire system must be able to handle, store, and display UTF-8 characters correctly.

---

### Step-by-Step Build Guide

**Step 1.** Create a new Laravel project:
```bash
composer create-project laravel/laravel ote
````

**Step 2.** Navigate to the new project directory:

```bash
cd ote
```

**Step 3.** Open the `.env` file.

**Step 4.** Set the `DB_CONNECTION` to `sqlite`:

```bash
DB_CONNECTION=sqlite
```

**Step 5.** Create an empty SQLite database file:

```bash
touch database/database.sqlite
```

**Step 6.** Run the following commands to create all the migration files:

```bash
php artisan make:migration create_tokens_table
php artisan make:migration create_languages_table
php artisan make:migration create_lexical_entries_table
php artisan make:migration create_attributes_table
php artisan make:migration create_links_table
```

**Step 7.** Copy the following code into the `database/migrations/YYYY_MM_DD_HHMMSS_create_tokens_table.php` file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tokens', function (Blueprint $table) {
            $table->id();
            $table->text('text')->unique();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tokens');
    }
};
```

**Step 8.** Create the `languages` migration file:

```bash
php artisan make:migration create_languages_table
```

**Step 9.** Copy the following code into the `database/migrations/YYYY_MM_DD_HHMMSS_create_languages_table.php` file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate->Support\Facades->Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
```

**Step 10.** Create the `lexical_entries` migration file:

```bash
php artisan make:migration create_lexical_entries_table
```

**Step 11.** Copy the following code into the `database/migrations/YYYY_MM_DD_HHMMSS_create_lexical_entries_table.php` file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate->Database->Schema\Blueprint;
use Illuminate->Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lexical_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('token_id')->constrained()->cascadeOnDelete();
            $table->foreignId('language_id')->constrained()->cascadeOnDelete();
            $table->unique(['token_id', 'language_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lexical_entries');
    }
};
```

**Step 12.** Create the `attributes` migration file:

```bash
php artisan make:migration create_attributes_table
```

**Step 13.** Copy the following code into the `database/migrations/YYYY_MM_DD_HHMMSS_create_attributes_table.php` file:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate->Database->Schema\Blueprint;
use Illuminate->Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lexical_entry_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->string('value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attributes');
    }
};
```

**Step 14.** Create the `links` migration file:

```bash
php artisan make:migration create_links_table
```

**Step 15.** Copy the following code into the `database/migrations/YYYY_MM_DD_HHMMSS_create_links_table.php` file:

```php
<?php

use Illuminate->Database\Migrations\Migration;
use Illuminate->Database->Schema\Blueprint;
use Illuminate->Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_lexical_entry_id')->constrained('lexical_entries')->cascadeOnDelete();
            $table->foreignId('target_lexical_entry_id')->constrained('lexical_entries')->cascadeOnDelete();
            $table->string('type');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
```

**Step 16.** Run all pending migrations:

```bash
php artisan migrate
```

**Step 17.** Run the following commands to create all the Eloquent models:

```bash
php artisan make:model Token
php artisan make:model Language
php artisan make:model LexicalEntry
php artisan make:model Attribute
php artisan make:model Link
```

**Step 18.** Copy the following code into `app/Models/Token.php`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate->Database->Eloquent->Relations\HasMany;

class Token extends Model
{
    protected $fillable = ['text'];
    
    public function lexicalEntries(): HasMany
    {
        return $this->hasMany(LexicalEntry::class);
    }
}
```

**Step 19.** Copy the following code into `app/Models/Language.php`:

```php
<?php

namespace App\Models;

use Illuminate->Database\Eloquent\Model;
use Illuminate->Database->Eloquent->Relations\HasMany;

class Language extends Model
{
    protected $fillable = ['code', 'name'];

    public function lexicalEntries(): HasMany
    {
        return $this->hasMany(LexicalEntry::class);
    }
}
```

**Step 20.** Copy the following code into `app/Models/LexicalEntry.php`:

```php
<?php

namespace App\Models;

use Illuminate->Database\Eloquent\Model;
use Illuminate->Database->Eloquent->Relations\BelongsTo;
use Illuminate->Database->Eloquent->Relations\HasMany;

class LexicalEntry extends Model
{
    protected $fillable = ['token_id', 'language_id'];
    
    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }

    public function links(): HasMany
    {
        return $this->hasMany(Link::class, 'source_lexical_entry_id');
    }

    public function linkedFrom(): HasMany
    {
        return $this->hasMany(Link::class, 'target_lexical_entry_id');
    }
}
```

**Step 21.** Copy the following code into `app/Models/Attribute.php`:

```php
<?php

namespace App\Models;

use Illuminate->Database->Eloquent->Model;
use Illuminate->Database->Eloquent->Relations\BelongsTo;

class Attribute extends Model
{
    protected $fillable = ['lexical_entry_id', 'key', 'value'];
    
    public function lexicalEntry(): BelongsTo
    {
        return $this->belongsTo(LexicalEntry::class);
    }
}
```

**Step 22.** Copy the following code into `app/Models/Link.php`:

```php
<?php

namespace App\Models;

use Illuminate->Database->Eloquent->Model;
use Illuminate->Database->Eloquent->Relations\BelongsTo;

class Link extends Model
{
    protected $fillable = ['source_lexical_entry_id', 'target_lexical_entry_id', 'type'];
    
    public function sourceEntry(): BelongsTo
    {
        return $this->belongsTo(LexicalEntry::class, 'source_lexical_entry_id');
    }

    public function targetEntry(): BelongsTo
    {
        return $this->belongsTo(LexicalEntry::class, 'target_lexical_entry_id');
    }
}
```

**Step 23.** Run the following commands to create all the CLI command files:

```bash
php artisan make:command AddToken
php artisan make:command AddLanguage
php artisan make:command AddEntry
php artisan make:command AddAttribute
php artisan make:command AddLink
php artisan make:command ListEntries
php artisan make:command ImportOteFile
php artisan make:command ExportOteFile
```

**Step 24.** Copy the following code into `app/Console/Commands/AddToken.php`:

```php
<?php

namespace App\Console/Commands;

use Illuminate\Console\Command;
use App->models\Token;

class AddToken extends Command
{
    protected $signature = 'ote:add-token {text}';
    protected $description = 'Adds a new unique token to the lexicon.';

    public function handle()
    {
        $text = $this->argument('text');
        
        $token = Token::firstOrCreate(['text' => $text]);

        if ($token->wasRecentlyCreated) {
            $this->info("Token '{$text}' added successfully with ID {$token->id}.");
        } else {
            $this->warn("Token '{$text}' already exists with ID {$token->id}.");
        }
    }
}
```

**Step 25.** Copy the following code into `app/Console/Commands/AddLanguage.php`:

```php
<?php

namespace App/Console/Commands;

use Illuminate\Console\Command;
use App->models\Language;

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
```

**Step 26.** Copy the following code into `app/Console/Commands/AddEntry.php`:

```php
<?php

namespace App/Console/Commands;

use Illuminate->Console\Command;
use App->models\Token;
use App->models\Language;
use App->models\LexicalEntry;

class AddEntry extends Command
{
    protected $signature = 'ote:add-entry {token} {language}';
    protected $description = 'Creates a new lexical entry linking a token and a language.';

    public function handle()
    {
        $tokenText = $this->argument('token');
        $langCode = $this->argument('language');

        $token = Token::where('text', $tokenText)->first();
        $language = Language::where('code', $langCode)->first();

        if (!$token) {
            $this->error("Token '{$tokenText}' not found. Please add it first.");
            return Command::FAILURE;
        }

        if (!$language) {
            $this->error("Language '{$langCode}' not found. Please add it first.");
            return Command::FAILURE;
        }

        $entry = LexicalEntry::firstOrCreate([
            'token_id' => $token->id,
            'language_id' => $language->id,
        ]);

        if ($entry->wasRecentlyCreated) {
            $this->info("Lexical entry for '{$tokenText}' in '{$langCode}' created successfully with ID {$entry->id}.");
        } else {
            $this->warn("Lexical entry already exists with ID {$entry->id}.");
        }
    }
}
```

**Step 27.** Copy the following code into `app/Console/Commands/AddAttribute.php`:

```php
<?php

namespace App/Console/Commands;

use Illuminate->Console\Command;
use App->models\LexicalEntry;
use App->models\Attribute;

class AddAttribute extends Command
{
    protected $signature = 'ote:add-attribute {entry_id} {key} {value}';
    protected $description = 'Adds an attribute to a lexical entry.';

    public function handle()
    {
        $entryId = $this->argument('entry_id');
        $key = $this->argument('key');
        $value = $this->argument('value');

        $entry = LexicalEntry::find($entryId);

        if (!$entry) {
            $this->error("Lexical entry with ID {$entryId} not found.");
            return Command::FAILURE;
        }

        $attribute = $entry->attributes()->create(['key' => $key, 'value' => $value]);

        $this->info("Attribute '{$key}' added to entry {$entryId} with ID {$attribute->id}.");
    }
}
```

**Step 28.** Copy the following code into `app/Console/Commands/AddLink.php`:

```php
<?php

namespace App/Console/Commands;

use Illuminate->Console\Command;
use App->models\LexicalEntry;
use App->models\Link;

class AddLink extends Command
{
    protected $signature = 'ote:add-link {source_id} {target_id} {type}';
    protected $description = 'Links two lexical entries with a specified relationship type.';

    public function handle()
    {
        $sourceId = $this->argument('source_id');
        $targetId = $this->argument('target_id');
        $type = $this->argument('type');

        $sourceEntry = LexicalEntry::find($sourceId);
        $targetEntry = LexicalEntry::find($targetId);

        if (!$sourceEntry) {
            $this->error("Source entry with ID {$sourceId} not found.");
            return Command::FAILURE;
        }

        if (!$targetEntry) {
            $this->error("Target entry with ID {$targetId} not found.");
            return Command::FAILURE;
        }

        $link = Link::firstOrCreate([
            'source_lexical_entry_id' => $sourceId,
            'target_lexical_entry_id' => $targetId,
            'type' => $type
        ]);

        if ($link->wasRecentlyCreated) {
            $this->info("Link created successfully: {$sourceId} -> {$targetId} ({$type}).");
        } else {
            $this->warn("Link already exists.");
        }
    }
}
```

**Step 29.** Copy the following code into `app/Console/Commands/ListEntries.php`:

```php
<?php

namespace App/Console/Commands;

use Illuminate->Console\Command;
use App->models\LexicalEntry;

class ListEntries extends Command
{
    protected $signature = 'ote:list-entries';
    protected $description = 'Lists all lexical entries.';

    public function handle()
    {
        $entries = LexicalEntry::with(['token', 'language'])->get()->map(function ($entry) {
            return [
                'ID' => $entry->id,
                'Token' => $entry->token->text,
                'Language' => $entry->language->name
            ];
        });
        
        $this->table(['ID', 'Token', 'Language'], $entries);
    }
}
```

**Step 30.** Copy the following code into `app/Console/Commands/ImportOteFile.php`:

```php
<?php

namespace App/Console/Commands;

use Illuminate\Console\Command;
use App->models\Token;
use App->models\Language;
use App->models\LexicalEntry;
use App->models\Link;
use Illuminate->Support\Facades\File;

class ImportOteFile extends Command
{
    protected $signature = 'ote:import-ote-file {path}';
    protected $description = 'Imports data from a legacy OTE word pair file.';
    
    public function handle()
    {
        $path = $this->argument('path');
    
        if (!File::exists($path)) {
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
            $this->error("Could not determine source and target languages from the file metadata.");
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
                'type' => 'translation'
            ]);
        }
    
        $bar->finish();
        $this->newLine();
        $this->info('Data import completed successfully!');
    }
}
```

**Step 31.** Copy the following code into `app/Console/Commands/ExportOteFile.php`:

```php
<?php

namespace App->Console->Commands;

use Illuminate->Console\Command;
use App->models\LexicalEntry;
use Illuminate->Support\Facades\File;

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
        $this->info("Successfully exported {$links->count()} word pairs to {$path}.");
    }
}
```

**Step 32.** Open `app/Console/Kernel.php` and update the `$commands` array to include all new commands:

```php
protected $commands = [
    \App\Console\Commands\AddToken::class,
    \App\Console\Commands\AddLanguage::class,
    \App\Console\Commands\AddEntry::class,
    \App->console->commands->AddAttribute::class,
    \App->console->commands->AddLink::class,
    \App->console->commands->ListEntries::class,
    \App->console->commands->ImportOteFile::class,
    \App->console->commands->ExportOteFile::class,
];
```

**Step 33.** Create the `TokenController` for the UI:

```bash
php artisan make:controller TokenController --resource
```

**Step 34.** Create the `LanguageController` for the UI:

```bash
php artisan make:controller LanguageController --resource
```

**Step 35.** Create the `LexiconController` for the UI:

```bash
php artisan make:controller LexiconController
```

**Step 36.** Open `routes/web.php` and replace the existing code with the following routes for full CRUD functionality:

```php
<?php

use Illuminate\Support\Facades\Route;
use App->http->controllers->TokenController;
use App->http->controllers->LanguageController;
use App->http->controllers->LexiconController;

Route::get('/', function () {
    return redirect()->route('lexicon.index');
});

Route::resources([
    'tokens' => TokenController::class,
    'languages' => LanguageController::class,
]);

Route::get('/lexicon', [LexiconController::class, 'index'])->name('lexicon.index');
Route::get('/lexicon/create', [LexiconController::class, 'create'])->name('lexicon.create');
Route::post('/lexicon', [LexiconController::class, 'store'])->name('lexicon.store');
Route::get('/lexicon/{entry}', [LexiconController::class, 'show'])->name('lexicon.show');
Route::get('/lexicon/{entry}/edit', [LexiconController::class, 'edit'])->name('lexicon.edit');
Route::put('/lexicon/{entry}', [LexiconController::class, 'update'])->name('lexicon.update');
Route::delete('/lexicon/{entry}', [LexiconController::class, 'destroy'])->name('lexicon.destroy');

Route::get('/lexicon/{entry}/add-attribute', [LexiconController::class, 'createAttribute'])->name('lexicon.create-attribute');
Route::post('/lexicon/{entry}/store-attribute', [LexiconController::class, 'storeAttribute'])->name('lexicon.store-attribute');
Route::get('/lexicon/{entry}/edit-attribute/{attribute}', [LexiconController::class, 'editAttribute'])->name('lexicon.edit-attribute');
Route::put('/lexicon/{entry}/update-attribute/{attribute}', [LexiconController::class, 'updateAttribute'])->name('lexicon.update-attribute');
Route::delete('/lexicon/{entry}/delete-attribute/{attribute}', [LexiconController::class, 'destroyAttribute'])->name('lexicon.destroy-attribute');

Route::get('/lexicon/{entry}/add-link', [LexiconController::class, 'createLink'])->name('lexicon.create-link');
Route::post('/lexicon/{entry}/store-link', [LexiconController::class, 'storeLink'])->name('lexicon.store-link');
Route::get('/lexicon/{entry}/edit-link/{link}', [LexiconController::class, 'editLink'])->name('lexicon.edit-link');
Route::put('/lexicon/{entry}/update-link/{link}', [LexiconController::class, 'updateLink'])->name('lexicon.update-link');
Route::delete('/lexicon/{entry}/delete-link/{link}', [LexiconController::class, 'destroyLink'])->name('lexicon.destroy-link');

Route::get('/import', [LexiconController::class, 'import'])->name('lexicon.import');
Route::post('/import', [LexiconController::class, 'handleImport'])->name('lexicon.handle-import');
Route::get('/export', [LexiconController::class, 'export'])->name('lexicon.export');
```

**Step 37.** Copy the following code into `app/Http/Controllers/TokenController.php`:

```php
<?php

namespace App->http->controllers;

use App->models\Token;
use Illuminate\Http->Request;

class TokenController extends Controller
{
    public function index()
    {
        $tokens = Token::all();
        return view('tokens.index', compact('tokens'));
    }

    public function create()
    {
        return view('tokens.create');
    }

    public function store(Request $request)
    {
        $request->validate(['text' => 'required|unique:tokens']);
        Token::create($request->all());
        return redirect()->route('tokens.index');
    }

    public function edit(Token $token)
    {
        return view('tokens.edit', compact('token'));
    }

    public function update(Request $request, Token $token)
    {
        $request->validate(['text' => 'required|unique:tokens,text,' . $token->id]);
        $token->update($request->all());
        return redirect()->route('tokens.index');
    }

    public function destroy(Token $token)
    {
        $token->delete();
        return redirect()->route('tokens.index');
    }
}
```

**Step 38.** Copy the following code into `app/Http/Controllers/LanguageController.php`:

```php
<?php

namespace App->http->controllers;

use App->models\Language;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function index()
    {
        $languages = Language::all();
        return view('languages.index', compact('languages'));
    }

    public function create()
    {
        return view('languages.create');
    }

    public function store(Request $request)
    {
        $request->validate(['code' => 'required|unique:languages', 'name' => 'required']);
        Language::create($request->all());
        return redirect()->route('languages.index');
    }

    public function edit(Language $language)
    {
        return view('languages.edit', compact('language'));
    }

    public function update(Request $request, Language $language)
    {
        $request->validate(['code' => 'required|unique:languages,code,' . $language->id, 'name' => 'required']);
        $language->update($request->all());
        return redirect()->route('languages.index');
    }

    public function destroy(Language $language)
    {
        $language->delete();
        return redirect()->route('languages.index');
    }
}
```

**Step 39.** Copy the following code into `app/Http/Controllers/LexiconController.php`:

```php
<?php

namespace App->http->controllers;

use App->models\LexicalEntry;
use App->models\Token;
use App->models\Language;
use App->models\Attribute;
use App->models\Link;
use Illuminate\Http\Request;
use Illuminate->Support\Facades\File;

class LexiconController extends Controller
{
    // Lexical Entry CRUD
    public function index()
    {
        $entries = LexicalEntry::with(['token', 'language'])->get();
        return view('lexicon.index', compact('entries'));
    }

    public function create()
    {
        $tokens = Token::all();
        $languages = Language::all();
        return view('lexicon.create', compact('tokens', 'languages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'token_id' => 'required|exists:tokens,id',
            'language_id' => 'required|exists:languages,id',
        ]);

        LexicalEntry::create($request->all());
        return redirect()->route('lexicon.index');
    }

    public function show(LexicalEntry $entry)
    {
        $entry->load(['token', 'language', 'attributes', 'links.targetEntry.token', 'links.targetEntry.language']);
        $allEntries = LexicalEntry::all();
        return view('lexicon.show', compact('entry', 'allEntries'));
    }

    public function edit(LexicalEntry $entry)
    {
        $tokens = Token::all();
        $languages = Language::all();
        return view('lexicon.edit', compact('entry', 'tokens', 'languages'));
    }

    public function update(Request $request, LexicalEntry $entry)
    {
        $request->validate([
            'token_id' => 'required|exists:tokens,id',
            'language_id' => 'required|exists:languages,id',
        ]);
        $entry->update($request->all());
        return redirect()->route('lexicon.index');
    }

    public function destroy(LexicalEntry $entry)
    {
        $entry->delete();
        return redirect()->route('lexicon.index');
    }

    // Attribute CRUD
    public function createAttribute(LexicalEntry $entry)
    {
        return view('lexicon.create-attribute', compact('entry'));
    }

    public function storeAttribute(Request $request, LexicalEntry $entry)
    {
        $request->validate(['key' => 'required', 'value' => 'required']);
        $entry->attributes()->create($request->all());
        return redirect()->route('lexicon.show', $entry);
    }

    public function editAttribute(LexicalEntry $entry, Attribute $attribute)
    {
        return view('lexicon.edit-attribute', compact('entry', 'attribute'));
    }

    public function updateAttribute(Request $request, LexicalEntry $entry, Attribute $attribute)
    {
        $request->validate(['key' => 'required', 'value' => 'required']);
        $attribute->update($request->all());
        return redirect()->route('lexicon.show', $entry);
    }

    public function destroyAttribute(LexicalEntry $entry, Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->route('lexicon.show', $entry);
    }

    // Link CRUD
    public function createLink(LexicalEntry $entry)
    {
        $allEntries = LexicalEntry::all();
        return view('lexicon.create-link', compact('entry', 'allEntries'));
    }

    public function storeLink(Request $request, LexicalEntry $entry)
    {
        $request->validate(['target_lexical_entry_id' => 'required|exists:lexical_entries,id', 'type' => 'required']);
        $entry->links()->create($request->all());
        return redirect()->route('lexicon.show', $entry);
    }
    
    public function editLink(LexicalEntry $entry, Link $link)
    {
        $allEntries = LexicalEntry::all();
        return view('lexicon.edit-link', compact('entry', 'link', 'allEntries'));
    }

    public function updateLink(Request $request, LexicalEntry $entry, Link $link)
    {
        $request->validate(['target_lexical_entry_id' => 'required|exists:lexical_entries,id', 'type' => 'required']);
        $link->update($request->all());
        return redirect()->route('lexicon.show', $entry);
    }

    public function destroyLink(LexicalEntry $entry, Link $link)
    {
        $link->delete();
        return redirect()->route('lexicon.show', $entry);
    }
    
    // Import/Export
    public function import()
    {
        return view('lexicon.import');
    }
    
    public function handleImport(Request $request)
    {
        $request->validate([
            'ote_file' => 'required|file|mimes:txt,dat',
        ]);

        $file = $request->file('ote_file');
        $lines = file($file->getRealPath(), FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
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
            return redirect()->back()->withErrors('Could not determine source and target languages from the file.');
        }

        $sourceLang = Language::firstOrCreate(['code' => $langCodes['source'], 'name' => '']);
        $targetLang = Language::firstOrCreate(['code' => $langCodes['target'], 'name' => '']);

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
                'type' => 'translation'
            ]);
        }

        return redirect()->route('lexicon.index')->with('success', 'File imported successfully!');
    }

    public function export(Request $request)
    {
        $links = Link::where('type', 'translation')
            ->with(['sourceEntry.token', 'sourceEntry.language', 'targetEntry.token', 'targetEntry.language'])
            ->get();
        
        $groupedLinks = $links->groupBy(function ($link) {
            return $link->sourceEntry->language->code . '>' . $link->targetEntry->language->code;
        });

        return view('lexicon.export', compact('groupedLinks'));
    }
}
```

**Step 50.** Create the `resources/views/tokens/` directory:

```bash
mkdir -p resources/views/tokens
```

**Step 51.** Copy the following code into `resources/views/tokens/index.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tokens</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Tokens</h1>
        <a href="{{ route('tokens.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Token</a>
        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Text</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($tokens as $token)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $token->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $token->text }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('tokens.edit', $token) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('tokens.destroy', $token) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
```

**Step 51.** Copy the following code into `resources/views/tokens/create.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Token</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Token</h1>
        <form action="{{ route('tokens.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="text" class="block text-gray-700">Token Text:</label>
                <input type="text" name="text" id="text" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Token</button>
        </form>
    </div>
</body>
</html>
```

**Step 52.** Copy the following code into `resources/views/tokens/edit.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Token</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Token</h1>
        <form action="{{ route('tokens.update', $token) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="text" class="block text-gray-700">Token Text:</label>
                <input type="text" name="text" id="text" value="{{ $token->text }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Token</button>
        </form>
    </div>
</body>
</html>
```

**Step 53.** Create the `resources/views/languages/` directory:

```bash
mkdir -p resources/views/languages
```

**Step 54.** Copy the following code into `resources/views/languages/index.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Languages</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Languages</h1>
        <a href="{{ route('languages.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Language</a>
        <table class="min-w-full divide-y divide-gray-200 mt-4">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($languages as $language)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $language->id }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $language->code }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $language->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('languages.edit', $language) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('languages.destroy', $language) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
```

**Step 55.** Copy the following code into `resources/views/languages/create.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Language</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Language</h1>
        <form action="{{ route('languages.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-gray-700">Language Code:</label>
                <input type="text" name="code" id="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Language Name:</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Language</button>
        </form>
    </div>
</body>
</html>
```

**Step 56.** Copy the following code into `resources/views/languages/edit.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Language</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Language</h1>
        <form action="{{ route('languages.update', $language) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="code" class="block text-gray-700">Language Code:</label>
                <input type="text" name="code" id="code" value="{{ $language->code }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Language Name:</label>
                <input type="text" name="name" id="name" value="{{ $language->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Language</button>
        </form>
    </div>
</body>
</html>
```

**Step 57.** Create the `resources/views/lexicon/` directory:

```bash
mkdir -p resources/views/lexicon
```

**Step 58.** Copy the following code into `resources/views/lexicon/index.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lexicon Entries</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Lexicon Entries</h1>
        <a href="{{ route('lexicon.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Entry</a>
        <ul class="list-disc pl-5">
            @foreach ($entries as $entry)
                <li class="mb-2">
                    <a href="{{ route('lexicon.show', $entry) }}" class="text-blue-600 hover:underline">
                        {{ $entry->token->text }} ({{ $entry->language->code }})
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
```

**Step 59.** Copy the following code into `resources/views/lexicon/show.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $entry->token->text }}</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <a href="{{ route('lexicon.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to list</a>
        <h1 class="text-3xl font-bold mt-2 mb-4">{{ $entry->token->text }} ({{ $entry->language->code }})</h1>

        <h2 class="text-xl font-semibold mt-4 mb-2">Attributes</h2>
        <a href="{{ route('lexicon.create-attribute', $entry) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mb-4 inline-block">Add Attribute</a>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($entry->attributes as $attribute)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attribute->key }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attribute->value }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('lexicon.edit-attribute', [$entry, $attribute]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('lexicon.destroy-attribute', [$entry, $attribute]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No attributes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="text-xl font-semibold mt-4 mb-2">Links</h2>
        <a href="{{ route('lexicon.create-link', $entry) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mb-4 inline-block">Add Link</a>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Entry</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($entry->links as $link)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $link->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('lexicon.show', $link->targetEntry) }}" class="text-blue-600 hover:underline">
                                {{ $link->targetEntry->token->text }} ({{ $link->targetEntry->language->code }})
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('lexicon.edit-link', [$entry, $link]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('lexicon.destroy-link', [$entry, $link]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No links found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
```

**Step 60.** Copy the following code into `resources/views/lexicon/create.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lexical Entry</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Lexical Entry</h1>
        <form action="{{ route('lexicon.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="token_id" class="block text-gray-700">Token:</label>
                <select name="token_id" id="token_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($tokens as $token)
                        <option value="{{ $token->id }}">{{ $token->text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="language_id" class="block text-gray-700">Language:</label>
                <select name="language_id" id="language_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}">{{ $language->name }} ({{ $language->code }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Entry</button>
        </form>
    </div>
</body>
</html>
```

**Step 61.** Copy the following code into `resources/views/lexicon/edit.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Lexical Entry</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Lexical Entry</h1>
        <form action="{{ route('lexicon.update', $entry) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="token_id" class="block text-gray-700">Token:</label>
                <select name="token_id" id="token_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($tokens as $token)
                        <option value="{{ $token->id }}" @selected($entry->token_id == $token->id)>{{ $token->text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="language_id" class="block text-gray-700">Language:</label>
                <select name="language_id" id="language_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}" @selected($entry->language_id == $language->id)>{{ $language->name }} ({{ $language->code }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Entry</button>
        </form>
    </div>
</body>
</html>
```

**Step 62.** Copy the following code into `resources/views/lexicon/create-attribute.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Attribute</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Attribute for Entry #{{ $entry->id }}</h1>
        <form action="{{ route('lexicon.store-attribute', $entry) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="key" class="block text-gray-700">Key:</label>
                <input type="text" name="key" id="key" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="value" class="block text-gray-700">Value:</label>
                <input type="text" name="value" id="value" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Attribute</button>
        </form>
    </div>
</body>
</html>
```

**Step 63.** Copy the following code into `resources/views/lexicon/edit-attribute.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attribute</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Attribute for Entry #{{ $entry->id }}</h1>
        <form action="{{ route('lexicon.update-attribute', [$entry, $attribute]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="key" class="block text-gray-700">Key:</label>
                <input type="text" name="key" id="key" value="{{ $attribute->key }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="value" class="block text-gray-700">Value:</label>
                <input type="text" name="value" id="value" value="{{ $attribute->value }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Attribute</button>
        </form>
    </div>
</body>
</html>
```

**Step 64.** Copy the following code into `resources/views/lexicon/create-link.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Link</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Link for Entry #{{ $entry->id }}</h1>
        <form action="{{ route('lexicon.store-link', $entry) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="type" class="block text-gray-700">Link Type:</label>
                <input type="text" name="type" id="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="target_lexical_entry_id" class="block text-gray-700">Target Entry:</label>
                <select name="target_lexical_entry_id" id="target_lexical_entry_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($allEntries as $targetEntry)
                        @if ($targetEntry->id !== $entry->id)
                            <option value="{{ $targetEntry->id }}">{{ $targetEntry->token->text }} ({{ $targetEntry->language->code }})</option>
                        @endif
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Link</button>
        </form>
    </div>
</body>
</html>
```

**Step 65.** Copy the following code into `resources/views/lexicon/edit-link.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Link</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Link for Entry #{{ $entry->id }}</h1>
        <form action="{{ route('lexicon.update-link', [$entry, $link]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="type" class="block text-gray-700">Link Type:</label>
                <input type="text" name="type" id="type" value="{{ $link->type }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="target_lexical_entry_id" class="block text-gray-700">Target Entry:</label>
                <select name="target_lexical_entry_id" id="target_lexical_entry_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($allEntries as $targetEntry)
                        @if ($targetEntry->id !== $entry->id)
                            <option value="{{ $targetEntry->id }}" @selected($link->target_lexical_entry_id == $targetEntry->id)>
                                {{ $targetEntry->token->text }} ({{ $targetEntry->language->code }})
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Link</button>
        </form>
    </div>
</body>
</html>
```

**Step 68.** Copy the following code into `resources/views/lexicon/import.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import OTE File</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <a href="{{ route('lexicon.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Lexicon</a>
        <h1 class="text-2xl font-bold mb-4">Import Old OTE File</h1>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('lexicon.handle-import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="ote_file" class="block text-gray-700">Select OTE File (.txt, .dat)</label>
                <input type="file" name="ote_file" id="ote_file" class="mt-1 block w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Import</button>
        </form>
    </div>
</body>
</html>
```

**Step 69.** Copy the following code into `resources/views/lexicon/export.blade.php`:

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export OTE File</title>
    <script src="[https://cdn.tailwindcss.com](https://cdn.tailwindcss.com)"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <a href="{{ route('lexicon.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Lexicon</a>
        <h1 class="text-2xl font-bold mb-4">Export Old OTE File</h1>
        @if ($groupedLinks->isEmpty())
            <p>No translation data to export.</p>
        @else
            @foreach ($groupedLinks as $key => $links)
                <h2 class="text-xl font-semibold mt-4">{{ $key }} ({{ $links->count() }} pairs)</h2>
                <pre class="bg-gray-200 p-4 rounded-lg text-sm overflow-x-auto"># {{ $links->first()->sourceEntry->language->name }} > {{ $links->first()->targetEntry->language->name }}
# {{ $links->count() }} Word Pairs
# Exported from OTE 2.0
# {{ now()->format('D, d M Y H:i:s T') }}
# 
# delimiter: =
@foreach ($links as $link)
{{ $link->sourceEntry->token->text }}={{ $link->targetEntry->token->text }}
@endforeach
</pre>
            @endforeach
        @endif
    </div>
</body>
</html>
```

-----

### Workflow for CLI

This workflow demonstrates how to populate the database with three languages, ten tokens, and their relationships using the command line.

**Step 70.** Add the "English" language:

```bash
php artisan ote:add-language en English
```

**Step 71.** Add the "Spanish" language:

```bash
php artisan ote:add-language es Spanish
```

**Step 72.** Add the "French" language:

```bash
php artisan ote:add-language fr French
```

**Step 73.** Add the "hello" token:

```bash
php artisan ote:add-token hello
```

**Step 74.** Add the "world" token:

```bash
php artisan ote:add-token world
```

**Step 75.** Add the "cat" token:

```bash
php artisan ote:add-token cat
```

**Step 76.** Add the "dog" token:

```bash
php artisan ote:add-token dog
```

**Step 77.** Add the "house" token:

```bash
php artisan ote:add-token house
```

**Step 78.** Add the "car" token:

```bash
php artisan ote:add-token car
```

**Step 79.** Add the "book" token:

```bash
php artisan ote:add-token book
```

**Step 80.** Add the "water" token:

```bash
php artisan ote:add-token water
```

**Step 81.** Add the "sun" token:

```bash
php artisan ote:add-token sun
```

**Step 82.** Add the "moon" token:

```bash
php artisan ote:add-token moon
```

**Step 83.** Add a lexical entry for "hello" in English:

```bash
php artisan ote:add-entry hello en
```

**Step 84.** Add a lexical entry for "hello" in Spanish:

```bash
php artisan ote:add-entry hello es
```

**Step 85.** Add a lexical entry for "hello" in French:

```bash
php artisan ote:add-entry hello fr
```

**Step 86.** Add an attribute to the Spanish "hello" entry (assuming entry ID 2):

```bash
php artisan ote:add-attribute 2 greeting hola
```

**Step 87.** Add a translation link from the English "hello" entry to the Spanish entry (assuming English is ID 1 and Spanish is ID 2):

```bash
php artisan ote:add-link 1 2 translation
```

**Step 88.** Add a translation link from the English "hello" entry to the French entry (assuming French is ID 3):

```bash
php artisan ote:add-link 1 3 translation
```

**Step 89.** List all lexical entries to confirm the data has been created:

```bash
php artisan ote:list-entries
```

-----

### Workflow for Web UI

This workflow demonstrates how to perform similar tasks using the web interface.

**Step 90.** Start the development server (if not already running):

```bash
php artisan serve
```

**Step 91.** Access the main lexicon page in your browser at `http://127.0.0.1:8000/lexicon`.

**Step 92.** Navigate to the "Tokens" page by visiting `http://127.0.0.1:8000/tokens`.

**Step 93.** Click the "Add New Token" button.

**Step 94.** Fill out the form with the token "bird".

**Step 95.** Click the "Create Token" button.

**Step 96.** Navigate to the "Languages" page by visiting `http://127.0.0.1:8000/languages`.

**Step 97.** Click the "Add New Language" button.

**Step 98.** Fill out the form with the code "de" and the name "German".

**Step 99.** Click the "Create Language" button.

**Step 100.** Navigate back to the main lexicon page by visiting `http://127.0.0.1:8000/lexicon`.

**Step 101.** Click the "Add New Entry" button.

**Step 102.** Select the "bird" token from the dropdown.

**Step 103.** Select the "German" language from the dropdown.

**Step 104.** Click the "Create Entry" button.

**Step 105.** On the main lexicon list, click on the "bird (de)" entry to go to its show page.

**Step 106.** On the show page, click "Add Attribute".

**Step 107.** Fill out the "Key" field with `gender`.

**Step 108.** Fill out the "Value" field with `masculine`.

**Step 109.** Click the "Create Attribute" button.

**Step 110.** On the show page for "bird (de)", click "Add Link".

**Step 111.** In the form, set the "Link Type" to `translation`.

**Step 112.** Select the "cat (en)" entry from the "Target Entry" dropdown.

**Step 113.** Click the "Create Link" button.

You have now created a translation link via the web UI.
\</immersive\>
