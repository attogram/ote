<?php

namespace OpenTranslationEngine\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{

    public function index()
    {
        $languages = DB::table('language')->get();
        return view(
            'language', 
            [
                'languages' => $languages
            ]
        );
    }
}
