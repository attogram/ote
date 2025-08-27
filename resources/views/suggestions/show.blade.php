@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Suggestion Details</h1>

    <div class="bg-white shadow-md rounded-lg p-6">
        <div class="mb-4">
            <h2 class="text-xl font-semibold">User</h2>
            <p>{{ $suggestion->user->name }} ({{ $suggestion->user->email }})</p>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Type</h2>
            <p>{{ $suggestion->type }}</p>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Model</h2>
            <p>{{ $suggestion->model_type }} @if($suggestion->model_id) (ID: {{ $suggestion->model_id }}) @endif</p>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Suggested Data</h2>
            <pre class="bg-gray-200 p-4 rounded-lg">{{ json_encode($suggestion->data, JSON_PRETTY_PRINT) }}</pre>
        </div>

        <div class="mb-4">
            <h2 class="text-xl font-semibold">Status</h2>
            <p>{{ $suggestion->status }}</p>
        </div>

        @if ($suggestion->status === 'pending')
            <div class="flex items-center">
                <form method="POST" action="{{ route('suggestions.approve', $suggestion) }}">
                    @csrf
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Approve</button>
                </form>
                <form method="POST" action="{{ route('suggestions.reject', $suggestion) }}" class="ml-4">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded">Reject</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
