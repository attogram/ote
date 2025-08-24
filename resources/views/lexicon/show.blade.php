<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $entry->token->text }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <a href="{{ route('lexicon.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to list</a>
        <h1 class="text-3xl font-bold mt-2 mb-4">{{ $entry->token->text }} ({{ $entry->language->code }})</h1>

        <h2 class="text-xl font-semibold mt-4 mb-2">Attributes</h2>
        <a href="{{ route('lexicon.create-attribute', $entry) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mb-4 inline-block">Add Attribute</a>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Key</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Value</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($entry->attributes as $attribute)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attribute->key }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attribute->value }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('lexicon.edit-attribute', [$entry, $attribute]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('lexicon.destroy-attribute', [$entry, $attribute]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No attributes found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <h2 class="text-xl font-semibold mt-4 mb-2">Links</h2>
        <a href="{{ route('lexicon.create-link', $entry) }}" class="bg-blue-500 text-white px-2 py-1 rounded text-sm mb-4 inline-block">Add Link</a>
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Entry</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse ($entry->links as $link)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $link->type }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <a href="{{ route('lexicon.show', $link->targetEntry) }}" class="text-blue-600 hover:underline">
                                {{ $link->targetEntry->token->text }} ({{ $link->targetEntry->language->code }})
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('lexicon.edit-link', [$entry, $link]) }}" class="text-indigo-600 hover:text-indigo-900">Edit</a>
                            <form action="{{ route('lexicon.destroy-link', [$entry, $link]) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">No links found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</body>
</html>
