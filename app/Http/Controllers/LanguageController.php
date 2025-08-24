<?php

namespace App\Http\Controllers;

use App\Models\Language;
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
