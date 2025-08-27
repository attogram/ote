@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="text-2xl font-bold mb-4">Create Suggestion</h1>

    <form method="POST" action="{{ route('suggestions.store') }}">
        @csrf

        <input type="hidden" name="type" value="{{ $type }}">
        <input type="hidden" name="model_type" value="{{ $model_type }}">
        <input type="hidden" name="model_id" value="{{ $model_id }}">

        @if($model)
            <div class="mb-4">
                <h2 class="text-xl font-semibold">Current Data</h2>
                <pre class="bg-gray-200 p-4 rounded-lg">{{ json_encode($model->toArray(), JSON_PRETTY_PRINT) }}</pre>
            </div>
        @endif

        <div class="mb-4">
            <label for="data" class="block text-gray-700">Suggestion Data (JSON format)</label>
            <textarea id="data" name="data" rows="10" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>{{ old('data') }}</textarea>
            @error('data')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </div>

        <div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Submit Suggestion</button>
        </div>
    </form>
</div>
@endsection
