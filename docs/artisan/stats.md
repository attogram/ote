# `ote:stats`

The `ote:stats` command displays a variety of statistics about the current state of the lexicon.

## Usage

```bash
php artisan ote:stats
```

## Description

This command provides a high-level overview of the data in your Open Translation Engine database. It is useful for monitoring the growth and composition of your lexicon.

The command outputs two main sections:
1.  A table with the total counts of major entities:
    -   Tokens
    -   Languages
    -   Lexical Entries
    -   Attributes
    -   Links
2.  A table showing the number of lexical entries for each language, which helps you see how developed each language's dictionary is.

## Output Example

When you run the command, the output will look something like this:

```
Lexicon Statistics:
+-----------------+-------+
| Entity          | Count |
+-----------------+-------+
| Tokens          |   150 |
| Languages       |     3 |
| Lexical Entries |   250 |
| Attributes      |    50 |
| Links           |   200 |
+-----------------+-------+
Entries per language:
+----------+---------+
| Language | Entries |
+----------+---------+
| English  |     100 |
| French   |      80 |
| Spanish  |      70 |
+----------+---------+
```
