@extends('layouts.app')

@section('title', 'Edit Lexical Entry')

@section('content')
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
@endsection
