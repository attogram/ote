<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Export OTE File</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <a href="{{ route('lexicon.index') }}" class="text-blue-600 hover:underline mb-4 inline-block">&larr; Back to Lexicon</a>
        <h1 class="text-2xl font-bold mb-4">Export Old OTE File</h1>
        @if ($groupedLinks->isEmpty())
            <p>No translation data to export.</p>
        @else
            @foreach ($groupedLinks as $key => $links)
                <h2 class="text-xl font-semibold mt-4">{{ $key }} ({{ $links->count() }} pairs)</h2>
                <pre class="bg-gray-200 p-4 rounded-lg text-sm overflow-x-auto"># {{ $links->first()->sourceEntry->language->name }} > {{ $links->first()->targetEntry->language->name }}
# {{ $links->count() }} Word Pairs
# Exported from OTE 2.0
# {{ now()->format('D, d M Y H:i:s T') }}
#
# delimiter: =
@foreach ($links as $link)
{{ $link->sourceEntry->token->text }}={{ $link->targetEntry->token->text }}
@endforeach
</pre>
            @endforeach
        @endif
    </div>
</body>
</html>
