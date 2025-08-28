# Language Edit View

The Language Edit view provides a form for modifying the details of an existing language.

**URL:** `/languages/{id}/edit` (e.g., `/languages/1/edit`)
**View File:** `resources/views/languages/edit.blade.php`

## What it is

This page allows users with the appropriate permissions to update the information for a language that has already been created.

## What it does

The view presents a form that is pre-populated with the current data for the language being edited. The form includes the following fields:

-   **Language Code:** A text input showing the language's unique code.
-   **Language Name:** A text input showing the language's full name.

### Form Submission

-   When the "Update Language" button is clicked, the form data is sent to the server.
-   The server validates the input to ensure the required fields are filled and that the code remains unique (if changed).
-   If validation is successful, the language's record in the database is updated with the new information.
-   The user is then redirected to the [Languages Index View](index.md).
-   If validation fails, the user is returned to the edit form with an error message.
-   The form uses the `PUT` HTTP method for the update and includes CSRF (Cross-Site Request Forgery) protection for security.
