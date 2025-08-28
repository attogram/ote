# `ote:list-tokens`

The `ote:list-tokens` command displays a list of all tokens currently in the system.

## Usage

```bash
php artisan ote:list-tokens
```

## Description

This command provides a straightforward list of all the unique tokens that have been created. It presents the information in a simple table, showing the `ID` and `Text` for each token.

This is a useful command for getting an overview of the tokens in your lexicon and for finding the correct text to use with other commands, such as `ote:add-entry` or `ote:delete-token`.

## Output Example

When you run the command, the output will look something like this:

```
+----+---------+
| ID | Text    |
+----+---------+
|  1 | hello   |
|  2 | world   |
|  3 | cat     |
|  4 | chat    |
+----+---------+
```
