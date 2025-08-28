# `ote:show-entry`

The `ote:show-entry` command displays detailed information about a specific lexical entry.

## Usage

```bash
php artisan ote:show-entry {id}
```

## Arguments

-   `{id}`: The ID of the lexical entry you want to inspect.

## Description

This command provides a comprehensive view of a single lexical entry, including its basic information, its attributes, and any links it has to other entries. It's a useful tool for debugging or for getting a complete picture of a specific entry in the dictionary.

The command will display:
-   The entry's ID, Token, and Language.
-   A table of all associated attributes (if any).
-   A table of all links where this entry is the source (if any).
-   A table of all links where this entry is the target (if any).

If the entry is not found, an error message will be shown.

## Example

To see the details for the lexical entry with ID `1`, you would run:

```bash
php artisan ote:show-entry 1
```

The output might look like this:

```
Lexical Entry Details:
  ID: 1
  Token: hello
  Language: English
Attributes:
+---------------+----------+
| Key           | Value    |
+---------------+----------+
| pronunciation | /həˈloʊ/ |
+---------------+----------+
Links (Source):
+-----------------+--------------+---------+
| Target Entry ID | Target Token | Type    |
+-----------------+--------------+---------+
|               3 | bonjour      | synonym |
+-----------------+--------------+---------+
```
