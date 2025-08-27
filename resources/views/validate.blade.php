@extends('layouts.app')

@section('title', 'Data Validation')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Data Validation Results</h1>

        @if (empty($issues))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">All good!</strong>
                <span class="block sm:inline">No data integrity issues found.</span>
            </div>
        @else
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Issues found!</strong>
                <span class="block sm:inline">Please review the data integrity issues below.</span>
            </div>

            @if (isset($issues['duplicate_tokens']))
                <h2 class="text-xl font-semibold mt-6 mb-2">Case-Insensitive Duplicate Tokens</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Text</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issues['duplicate_tokens'] as $token)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $token->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $token->text }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if (isset($issues['duplicate_languages']))
                <h2 class="text-xl font-semibold mt-6 mb-2">Duplicate Language Names</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issues['duplicate_languages'] as $language)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $language->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if (isset($issues['unused_tokens']))
                <h2 class="text-xl font-semibold mt-6 mb-2">Unused Tokens</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Text</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issues['unused_tokens'] as $token)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $token->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $token->text }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            @if (isset($issues['unused_languages']))
                <h2 class="text-xl font-semibold mt-6 mb-2">Unused Languages</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">ID</th>
                            <th class="py-2 px-4 border-b">Name</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($issues['unused_languages'] as $language)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $language->id }}</td>
                                <td class="py-2 px-4 border-b">{{ $language->name }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

        @endif

        <div class="mt-6">
            <a href="{{ route('home') }}" class="text-blue-600 hover:underline">Back to Homepage</a>
        </div>
    </div>
@endsection
