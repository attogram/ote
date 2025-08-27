<?php

namespace App\Http\Controllers;

use App\Models\Token;
use Illuminate\Http\Request;

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

    public function show(Token $token)
    {
        $token->load('lexicalEntries.language');
        return view('tokens.show', compact('token'));
    }

    public function edit(Token $token)
    {
        return view('tokens.edit', compact('token'));
    }

    public function update(Request $request, Token $token)
    {
        $request->validate(['text' => 'required|unique:tokens,text,'.$token->id]);
        $token->update($request->all());

        return redirect()->route('tokens.index');
    }

    public function destroy(Token $token)
    {
        $token->delete();

        return redirect()->route('tokens.index');
    }
}
