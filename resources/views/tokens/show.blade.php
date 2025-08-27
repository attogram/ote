@extends('layouts.app')

@section('title', 'Token: ' . $token->text)

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Token: {{ $token->text }}</h1>

        <h2 class="text-xl font-semibold mt-4 mb-2">Lexical Entries</h2>
        <ul class="list-disc pl-5">
            @forelse ($token->lexicalEntries as $entry)
                <li class="mb-2">
                    <a href="{{ route('lexicon.show', $entry) }}" class="text-blue-600 hover:underline">
                        {{ $entry->language->name }} ({{ $entry->language->code }})
                    </a>
                </li>
            @empty
                <li>No lexical entries found for this token.</li>
            @endforelse
        </ul>
    </div>
@endsection
