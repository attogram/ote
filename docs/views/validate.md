# Validate View

The Validate view displays the results of a data integrity check on the lexicon. It is the web interface for the `ote:validate` Artisan command.

**URL:** `/validate`
**View File:** `resources/views/validate.blade.php`

## What it is

This page provides a user-friendly way to see potential issues within the lexicon data. It helps administrators and editors maintain a clean and consistent database.

## What it does

When a user navigates to this page, the application runs a series of validation checks in the background. The view then displays the results of these checks.

1.  **No Issues Found:**
    If the validation process finds no problems, the page displays a success message indicating that the data is clean.

2.  **Issues Found:**
    If the validation process finds any issues, the page will display a warning message and one or more tables, each corresponding to a specific type of problem:
    -   **Case-Insensitive Duplicate Tokens:** Lists tokens that have the same text but different capitalization (e.g., "word" and "Word").
    -   **Duplicate Language Names:** Shows if multiple languages share the same name.
    -   **Unused Tokens:** Lists tokens that exist in the database but are not associated with any lexical entry.
    -   **Unused Languages:** Lists languages that have been created but contain no lexical entries.

3.  **Navigation:**
    The page includes a link to return to the [Home View](home.md).

This view is read-only; it only reports issues. To fix the reported problems, the user must use the appropriate management tools (e.g., editing or deleting tokens and languages) or run the corresponding Artisan commands.
