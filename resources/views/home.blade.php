<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTE v2 Homepage</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Open Translation Engine v2</h1>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Lexicon Statistics</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-lg font-medium">Tokens</p>
                    <p class="text-2xl font-bold">{{ $stats['tokens'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-lg font-medium">Languages</p>
                    <p class="text-2xl font-bold">{{ $stats['languages'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-lg font-medium">Lexical Entries</p>
                    <p class="text-2xl font-bold">{{ $stats['lexical_entries'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-lg font-medium">Attributes</p>
                    <p class="text-2xl font-bold">{{ $stats['attributes'] }}</p>
                </div>
                <div class="bg-gray-50 p-4 rounded-lg">
                    <p class="text-lg font-medium">Links</p>
                    <p class="text-2xl font-bold">{{ $stats['links'] }}</p>
                </div>
            </div>
        </div>

        <div class="mb-6">
            <h2 class="text-xl font-semibold mb-2">Entries per Language</h2>
            <ul class="list-disc pl-5">
                @foreach ($entriesPerLanguage as $language)
                    <li>{{ $language->name }}: {{ $language->lexical_entries_count }}</li>
                @endforeach
            </ul>
        </div>

        <div>
            <h2 class="text-xl font-semibold mb-2">Manage Lexicon</h2>
            <div class="flex space-x-4">
                <a href="{{ route('tokens.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Manage Tokens</a>
                <a href="{{ route('languages.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Manage Languages</a>
                <a href="{{ route('lexicon.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded">Manage Lexical Entries</a>
            </div>
        </div>

        <div class="mt-6">
            <h2 class="text-xl font-semibold mb-2">Admin Tools</h2>
            <a href="{{ route('validate') }}" class="text-blue-600 hover:underline">Validate Data Integrity</a>
        </div>
    </div>
</body>
</html>
