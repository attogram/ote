# `ote:import-ote-file`

The `ote:import-ote-file` command imports translation data from a file formatted in the classic OTE (v1) style.

## Usage

```bash
php artisan ote:import-ote-file {path}
```

## Arguments

-   `{path}`: The path to the OTE-formatted file you want to import.

## Description

This command is designed to migrate data from older OTE installations or from other sources that can produce the same simple format. The command reads the specified file, automatically creating tokens, languages, lexical entries, and translation links as needed.

The importer expects the file to contain metadata in comments (`#`) to determine the source and target languages and the delimiter used. For example:
-   `# eng > nld` specifies the languages.
-   `# delimiter: =` specifies the separator between words.

The command will create languages if they don't exist, but it's recommended to create them beforehand with proper names using `ote:add-language`. It will also create tokens and lexical entries for each word in the file. If the tokens, entries, or links already exist, they will not be duplicated.

A progress bar is displayed during the import process.

## Example

To import a file named `en-nl.txt`, you would run:

```bash
php artisan ote:import-ote-file data/en-nl.txt
```

The file `en-nl.txt` should be formatted like this:

```
# eng > nld
# delimiter: =
cat=kat
dog=hond
```

During the import, you will see a progress bar, followed by a success message:

```
Starting import of 'eng' to 'nld' translations...
████████████████████████████████████████ 100%
Data import completed successfully!
```
