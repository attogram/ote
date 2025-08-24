<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Token</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Edit Token</h1>
        <form action="{{ route('tokens.update', $token) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="text" class="block text-gray-700">Token Text:</label>
                <input type="text" name="text" id="text" value="{{ $token->text }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Token</button>
        </form>
    </div>
</body>
</html>
