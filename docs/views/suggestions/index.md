# Suggestions Index View

The Suggestions Index view is the main administrative page for reviewing and managing all user-submitted suggestions.

**URL:** `/suggestions`
**View File:** `resources/views/suggestions/index.blade.php`

## What it is

This page provides a high-level overview of all suggestions made by users. It is intended for editors and administrators who are responsible for moderating and approving changes to the lexicon.

## What it does

The view displays a table of all suggestions with the following columns:

-   **User:** The name of the user who made the suggestion.
-   **Type:** The type of suggestion (e.g., `create`, `update`, `delete`).
-   **Model:** The type of data the suggestion applies to (e.g., `LexicalEntry`, `Token`).
-   **Status:** The current status of the suggestion (`pending`, `approved`, `rejected`).
-   **Date:** The date the suggestion was created.
-   **Actions:** A "View" link that navigates to the [Suggestion Show View](show.md) for that specific suggestion, where an administrator can review the details and take action.

If no suggestions have been made, a message "No suggestions found" is displayed in the table. The page also shows a success message if an action (like approving or rejecting a suggestion) was just completed.
