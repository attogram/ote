# `ote:show-language`

The `ote:show-language` command displays detailed information about a specific language, including a list of all its lexical entries.

## Usage

```bash
php artisan ote:show-language {id}
```

## Arguments

-   `{id}`: The ID of the language you want to inspect.

## Description

This command provides a detailed view of a single language. It shows the language's `ID`, `Code`, and `Name`, and then lists all the lexical entries that belong to it in a table.

This is useful for seeing all the words and phrases that have been added for a particular language. To find the ID of a language, you can use the `ote:list-languages` command.

If a language with the specified ID is not found, an error message will be displayed.

## Example

To see the details for the language with ID `1` (e.g., English), you would run:

```bash
php artisan ote:show-language 1
```

The output might look like this:

```
Language Details:
  ID: 1
  Code: en
  Name: English
Lexical Entries:
+----------+-------+
| Entry ID | Token |
+----------+-------+
|        1 | hello |
|        2 | world |
|        5 | cat   |
+----------+-------+
```
