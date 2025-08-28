# `ote:list-languages`

The `ote:list-languages` command displays a list of all languages currently available in the system.

## Usage

```bash
php artisan ote:list-languages
```

## Description

This command provides a quick overview of all the languages that have been added to the Open Translation Engine. It presents the information in a simple table, showing the `ID`, `Code`, and `Name` for each language.

This is a useful command for seeing which languages are supported and for finding the correct language codes to use with other commands, such as `ote:add-entry` or `ote:delete-language`.

## Output Example

When you run the command, the output will look something like this:

```
+----+------+---------+
| ID | Code | Name    |
+----+------+---------+
|  1 | en   | English |
|  2 | fr   | French  |
|  3 | es   | Spanish |
+----+------+---------+
```
