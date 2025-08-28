# Languages Index View

The Languages Index view is the main page for managing all the languages in the system.

**URL:** `/languages`
**View File:** `resources/views/languages/index.blade.php`

## What it is

This page displays a comprehensive list of all languages that have been added to the Open Translation Engine. It serves as the central hub for language management.

## What it does

The view provides the following features:

1.  **Add New Language Button:**
    A prominent button at the top of the page links to the [Language Create View](create.md), allowing users to add a new language.

2.  **Language Table:**
    The core of the page is a table that lists all languages with the following columns:
    -   **ID:** The unique identifier for the language.
    -   **Code:** The language code (e.g., "en", "fr").
    -   **Name:** The full name of the language. The name is a link that navigates to the [Language Show View](show.md) for that language.

3.  **Actions:**
    For each language in the table, there are two actions available:
    -   **Edit:** A link to the [Language Edit View](edit.md), where the language's name can be updated.
    -   **Delete:** A button that, when clicked, will prompt the user for confirmation ("Are you sure?") before deleting the language and all its associated data.

This page provides a complete set of tools for viewing, adding, editing, and deleting languages.
