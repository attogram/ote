# `ote:export-ote-file`

The `ote:export-ote-file` command exports a list of translations between two languages into a file using the classic OTE (v1) format.

## Usage

```bash
php artisan ote:export-ote-file {path} {source_lang_code} {target_lang_code}
```

## Arguments

-   `{path}`: The file path where the export will be saved (e.g., `exports/en-fr.txt`).
-   `{source_lang_code}`: The language code of the source language (e.g., "en").
-   `{target_lang_code}`: The language code of the target language (e.g., "fr").

## Description

This command is used to generate a simple, plain-text file containing translation pairs. It finds all "translation" links between the specified source and target languages and writes them to the specified file, with each pair separated by an equals sign (`=`).

The output file format is designed for compatibility with older versions of OTE and for easy parsing by other tools.

If no translation links are found between the two languages, the command will report an error and no file will be created.

## Example

To export all English-to-French translations to a file named `en-fr_export.txt`, you would run:

```bash
php artisan ote:export-ote-file en-fr_export.txt en fr
```

If successful, the command will output a confirmation message:

```
Successfully exported {count} word pairs to en-fr_export.txt
```

And the content of `en-fr_export.txt` would look like this:

```
# en > fr
# 1 Word Pairs
# Exported from OTE 2.0
# Mon, 01 Jan 2024 12:00:00 UTC
#
# delimiter: =
cat=chat
```
