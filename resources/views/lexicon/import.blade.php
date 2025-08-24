<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import OTE File</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <a href="{{ route('lexicon.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Lexicon</a>
        <h1 class="text-2xl font-bold mb-4">Import Old OTE File</h1>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                {{ session('success') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <form action="{{ route('lexicon.handle-import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="ote_file" class="block text-gray-700">Select OTE File (.txt, .dat)</label>
                <input type="file" name="ote_file" id="ote_file" class="mt-1 block w-full">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Import</button>
        </form>
    </div>
</body>
</html>
