# `ote:show-token`

The `ote:show-token` command displays detailed information about a specific token, including all of its associated lexical entries.

## Usage

```bash
php artisan ote:show-token {id}
```

## Arguments

-   `{id}`: The ID of the token you want to inspect.

## Description

This command provides a detailed view of a single token. It shows the token's `ID` and `Text`, and then lists all the lexical entries that are linked to it, showing which languages the token has been defined in.

This is useful for understanding how a single concept (the token) is represented across different languages in your lexicon. To find the ID of a token, you can use the `ote:list-tokens` command.

If a token with the specified ID is not found, an error message will be displayed.

## Example

To see the details for the token with ID `1` (e.g., "hello"), you would run:

```bash
php artisan ote:show-token 1
```

The output might look like this:

```
Token Details:
  ID: 1
  Text: hello
Lexical Entries:
+----------+----------+
| Entry ID | Language |
+----------+----------+
|        1 | English  |
|        3 | French   |
+----------+----------+
```
