# Language Create View

The Language Create view provides a form for adding a new language to the system.

**URL:** `/languages/create`
**View File:** `resources/views/languages/create.blade.php`

## What it is

This page contains a simple form that allows users with the appropriate permissions to register a new language in the Open Translation Engine.

## What it does

The view presents a form with the following fields:

-   **Language Code:** A text input for the unique language code (e.g., "es"). It is recommended to use [ISO 639-1](https://en.wikipedia.org/wiki/List_of_ISO_639-1_codes) codes.
-   **Language Name:** A text input for the full, human-readable name of the language (e.g., "Spanish").

### Form Submission

-   When the "Create Language" button is clicked, the form data is sent to the server.
-   The server validates the input to ensure the code and name are present and that the code is unique.
-   If validation is successful, a new language is created in the database, and the user is redirected to the [Languages Index View](index.md).
-   If validation fails (e.g., the code already exists), the user is returned to the form with an error message.
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.
