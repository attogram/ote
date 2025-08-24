<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Attribute</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Attribute for Entry #{{ $entry->id }}</h1>
        <form action="{{ route('lexicon.update-attribute', [$entry, $attribute]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="key" class="block text-gray-700">Key:</label>
                <input type="text" name="key" id="key" value="{{ $attribute->key }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="value" class="block text-gray-700">Value:</label>
                <input type="text" name="value" id="value" value="{{ $attribute->value }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Attribute</button>
        </form>
    </div>
</body>
</html>
