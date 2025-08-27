@extends('layouts.app')

@section('title', 'Add Link')

@section('content')
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
@endsection
