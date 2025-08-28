# `ote:list-attributes`

The `ote:list-attributes` command displays a list of all attributes in the database.

## Usage

```bash
php artisan ote:list-attributes
```

## Description

This command provides a comprehensive overview of all attributes associated with lexical entries. It presents the information in a tabular format, making it easy to see the `ID`, `Lexical Entry ID`, `Key`, and `Value` for each attribute.

This command is particularly useful when you need to find the ID of a specific attribute to use with other commands, such as `ote:delete-attribute`.

## Output Example

When you run the command, the output will look similar to this:

```
+----+------------------+---------------+--------------------------+
| ID | Lexical Entry ID | Key           | Value                    |
+----+------------------+---------------+--------------------------+
|  1 |              123 | pronunciation | /pɹəˌnʌnsiˈeɪʃən/        |
|  2 |              123 | gender        | neuter                   |
|  3 |              456 | plural        | words                    |
+----+------------------+---------------+--------------------------+
```
