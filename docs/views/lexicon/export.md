# Lexicon Export View

The Lexicon Export view provides a way to see all "translation" links in the classic OTE (v1) file format.

**URL:** `/lexicon/export`
**View File:** `resources/views/lexicon/export.blade.php`

## What it is

This page generates and displays the contents of what would be an OTE v1 export file. It is the web-based equivalent of the `ote:export-ote-file` Artisan command, but it exports all language pairs simultaneously.

## What it does

The view fetches all links of type "translation" from the database and groups them by language pair (e.g., "English to French").

For each language pair found, it displays:
1.  **A Header:** Shows the language pair and the total number of translation pairs found (e.g., "English > French (150 pairs)").
2.  **A Preformatted Text Block:** This block contains the export data in the OTE v1 format, which includes:
    -   Metdata comments (`#`) specifying the languages, the number of pairs, the export source (OTE 2.0), the current timestamp, and the delimiter (`=`).
    -   A list of the word pairs, one per line, with the source and target words separated by an equals sign (e.g., `cat=chat`).

This view is designed to be a simple way to get a full dump of the translation data. Users can then manually copy and paste the contents of the text blocks into a `.txt` file to create their own export files.

A "Back to Lexicon" link is provided for easy navigation back to the [Lexicon Index View](index.md).
