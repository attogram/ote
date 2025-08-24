<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Lexical Entry</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Add New Lexical Entry</h1>
        <form action="{{ route('lexicon.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="token_id" class="block text-gray-700">Token:</label>
                <select name="token_id" id="token_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($tokens as $token)
                        <option value="{{ $token->id }}">{{ $token->text }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="language_id" class="block text-gray-700">Language:</label>
                <select name="language_id" id="language_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    @foreach($languages as $language)
                        <option value="{{ $language->id }}">{{ $language->name }} ({{ $language->code }})</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Entry</button>
        </form>
    </div>
</body>
</html>
