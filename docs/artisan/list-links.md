# `ote:list-links`

The `ote:list-links` command displays a list of all the links between lexical entries.

## Usage

```bash
php artisan ote:list-links
```

## Description

This command provides a complete list of all the relationships that have been defined between lexical entries. The output is a table that shows the `ID` of the link, the `Source Entry ID`, the `Target Entry ID`, and the `Type` of the link.

This command is very useful for getting an overview of the connections in your dictionary and for finding the specific ID of a link that you might want to delete using the `ote:delete-link` command.

## Output Example

When you run the command, the output will look something like this:

```
+----+-----------------+-----------------+-------------+
| ID | Source Entry ID | Target Entry ID | Type        |
+----+-----------------+-----------------+-------------+
|  1 |               1 |               3 | translation |
|  2 |               2 |               4 | translation |
|  3 |               1 |               2 | synonym     |
+----+-----------------+-----------------+-------------+
```
