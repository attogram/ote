# Lexicon Edit View

The Lexicon Edit view provides a form to modify an existing lexical entry.

**URL:** `/lexicon/{id}/edit` (e.g., `/lexicon/1/edit`)
**View File:** `resources/views/lexicon/edit.blade.php`

## What it is

This page allows users to change the core association of a lexical entry, specifically which token it points to or which language it belongs to.

## What it does

The view presents a form that is pre-populated with the current data for the lexical entry being edited. The form includes two dropdown menus:

-   **Token:** A dropdown list of all existing tokens, with the entry's current token pre-selected.
-   **Language:** A dropdown list of all available languages, with the entry's current language pre-selected.

### Form Submission

-   When the "Update Entry" button is clicked, the form data is sent to the server.
-   The server validates the input and updates the `LexicalEntry` record with the new `token_id` or `language_id`.
-   Upon successful update, the user is redirected to the [Lexicon Index View](index.md).
-   If the update would result in a duplicate entry (i.e., an entry for that token and language already exists), the database will prevent the change, and an error may be shown.
-   The form uses the `PUT` HTTP method for the update and includes CSRF (Cross-Site Request Forgery) protection for security.
