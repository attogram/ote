<?php

namespace App\Http\Controllers;

use App\Models\LexicalEntry;
use App\Models\Token;
use App\Models\Language;
use App\Models\Attribute;
use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
