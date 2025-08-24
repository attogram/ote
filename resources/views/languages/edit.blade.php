<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Language</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Language</h1>
        <form action="{{ route('languages.update', $language) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="code" class="block text-gray-700">Language Code:</label>
                <input type="text" name="code" id="code" value="{{ $language->code }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700">Language Name:</label>
                <input type="text" name="name" id="name" value="{{ $language->name }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Language</button>
        </form>
    </div>
</body>
</html>
