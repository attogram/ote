@extends('layouts.app')

@section('title', 'Add Language')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Language</h1>
        <form action="{{ route('languages.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="code" class="block text-gray-700">Language Code:</label>
                <input type="text" name="code" id="code" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Language Name:</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Language</button>
        </form>
    </div>
@endsection
