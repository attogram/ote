@extends('layouts.app')

@section('title', 'Lexicon Entries')

@section('content')
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
@endsection
