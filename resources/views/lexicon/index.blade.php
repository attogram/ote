@extends('layouts.app')

@section('title', 'Lexicon Entries')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold">Lexicon Entries</h1>
            <div>
                {{-- @can('create', App\Models\LexicalEntry::class) --}}
                <a href="{{ route('lexicon.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Add New Entry</a>
                {{-- @endcan --}}
                @auth
                    <a href="{{ route('suggestions.create', ['type' => 'create', 'model_type' => \App\Models\LexicalEntry::class]) }}" class="bg-yellow-500 text-white px-4 py-2 rounded ml-4">Suggest a New Entry</a>
                @endauth
            </div>
        </div>
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
@endsection
