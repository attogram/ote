<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use App\Models\Language;
use App\Models\LexicalEntry;
use App\Models\Link;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $stats = [
            'tokens' => Token::count(),
            'languages' => Language::count(),
            'lexical_entries' => LexicalEntry::count(),
            'attributes' => Attribute::count(),
            'links' => Link::count(),
        ];

        $entriesPerLanguage = Language::withCount('lexicalEntries')->get();

        return view('home', compact('stats', 'entriesPerLanguage'));
    }

    public function validate()
    {
        $issues = [];

        // Check for duplicate tokens (case-insensitive)
        $duplicateTexts = DB::table('tokens')
            ->select(DB::raw('LOWER(text) as lower_text'))
            ->groupBy('lower_text')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('lower_text');

        if ($duplicateTexts->isNotEmpty()) {
            $issues['duplicate_tokens'] = Token::whereIn(DB::raw('LOWER(text)'), $duplicateTexts)->orderBy('text')->get();
        }

        // Check for duplicate language names
        $duplicateLangs = Language::select('name')
            ->groupBy('name')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        if ($duplicateLangs->isNotEmpty()) {
            $issues['duplicate_languages'] = $duplicateLangs;
        }

        // Check for unused tokens
        $unusedTokens = Token::whereDoesntHave('lexicalEntries')->get();
        if ($unusedTokens->isNotEmpty()) {
            $issues['unused_tokens'] = $unusedTokens;
        }

        // Check for unused languages
        $unusedLangs = Language::whereDoesntHave('lexicalEntries')->get();
        if ($unusedLangs->isNotEmpty()) {
            $issues['unused_languages'] = $unusedLangs;
        }

        return view('validate', compact('issues'));
    }
}
