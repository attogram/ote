# `ote:list-entries`

The `ote:list-entries` command displays a list of all lexical entries in the database.

## Usage

```bash
php artisan ote:list-entries
```

## Description

This command provides a comprehensive list of all lexical entries, showing the `ID` of the entry, the `Token` (the word or phrase), and the `Language` it belongs to. The output is presented in a clean, tabular format.

This command is useful for getting an overview of the content in the dictionary and for finding the IDs of specific entries to use with other commands like `ote:delete-entry`, `ote:show-entry`, or `ote:add-attribute`.

## Output Example

When you run the command, the output will look similar to this:

```
+----+---------+----------+
| ID | Token   | Language |
+----+---------+----------+
|  1 | hello   | English  |
|  2 | world   | English  |
|  3 | bonjour | French   |
|  4 | monde   | French   |
+----+---------+----------+
```
