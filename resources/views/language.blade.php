<style>
table {
    border-collapse: collapse;
    margin-left: 10px;
}
table, th, td {
    border: 1px solid #111;
    padding: 4px;
}
th {
    background-color: #aaa;
}
</style> 
<h1>Languages</h1>
<table>
    <tr>
        <th>Code</th>
        <th>Language</th>
        <th>Self Name</th>
    </tr>
    @foreach ($languages as $language)

    <tr>

        <td>
            <a href="./{{ $language->code }}">
                <code>
                    {{ $language->code }}
                </code>
            </a>
        </td>
        <td>{{ $language->name }}</td>
        <td>{{ $language->name_self }}</td>
        
    </tr>

    @endforeach
</table>