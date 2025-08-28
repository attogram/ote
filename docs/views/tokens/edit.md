# Token Edit View

The Token Edit view provides a form for modifying the text of an existing token.

**URL:** `/tokens/{id}/edit` (e.g., `/tokens/1/edit`)
**View File:** `resources/views/tokens/edit.blade.php`

## What it is

This page allows users to change the text of a token that has already been created. This is useful for correcting typos or standardizing terms.

## What it does

The view presents a form pre-populated with the token's current text:

-   **Token Text:** A text input showing the token's current text.

### Form Submission

-   When the "Update Token" button is clicked, the form data is sent to the server.
-   The server validates the new text to ensure it is present and unique.
-   If validation is successful, the `Token` record is updated in the database.
-   The user is then redirected to the [Tokens Index View](index.md).
-   If the new text is not unique, the user will be returned to the edit form with an error message.
-   The form uses the `PUT` HTTP method for the update and includes CSRF (Cross-Site Request Forgery) protection for security.
