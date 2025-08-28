# Token Create View

The Token Create view provides a form for adding a new, unique token to the system.

**URL:** `/tokens/create`
**View File:** `resources/views/tokens/create.blade.php`

## What it is

This page contains a simple form that allows users to create a new token. A token is a language-agnostic word or phrase that can be linked to multiple language-specific entries.

## What it does

The view presents a form with a single text field:

-   **Token Text:** A text input for the new token (e.g., "automobile").

### Form Submission

-   When the "Create Token" button is clicked, the form data is sent to the server.
-   The server validates the input to ensure the text is present and unique.
-   If validation is successful, a new `Token` is created in the database, and the user is redirected to the [Tokens Index View](index.md).
-   If the token already exists, the user will be returned to the form with an error message.
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.
