# `ote:validate`

The `ote:validate` command checks the integrity of the lexicon data and reports any potential issues.

## Usage

```bash
php artisan ote:validate
```

## Description

This command runs a series of checks on your database to find common data problems that could affect the application's performance or accuracy. It's a good idea to run this command periodically, especially after large data imports, to ensure your lexicon remains clean and consistent.

The validator currently checks for:
-   **Case-insensitive duplicate tokens**: Finds tokens that have the same text but different casing (e.g., "Word" and "word").
-   **Duplicate language names**: Finds languages that have been given the same name.
-   **Unused tokens**: Finds tokens that have been created but are not linked to any lexical entries.
-   **Unused languages**: Finds languages that have been created but have no lexical entries.

If no issues are found, the command will report that the validation was successful. If any issues are found, they will be listed in tables with a final error message.

## Output Example

If issues are found, the output might look like this:

```
Starting validation...
Found case-insensitive duplicate tokens:
+----+------+
| ID | Text |
+----+------+
| 10 | Word |
| 25 | word |
+----+------+
Found unused tokens:
+----+----------+
| ID | Text     |
+----+----------+
| 30 | obsolete |
+----+----------+
Validation complete. Issues found.
```

If no issues are found, the output will be:

```
Starting validation...
Validation complete. No issues found.
```
