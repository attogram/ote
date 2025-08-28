# Lexicon Edit Attribute View

The Lexicon Edit Attribute view provides a form to modify an existing attribute of a lexical entry.

**URL:** `/lexicon/{entry_id}/attributes/{attribute_id}/edit` (e.g., `/lexicon/1/attributes/5/edit`)
**View File:** `resources/views/lexicon/edit-attribute.blade.php`

## What it is

This page allows users to change the key or value of an attribute that has already been assigned to a lexical entry.

## What it does

The view displays the ID of the parent lexical entry and presents a form pre-populated with the attribute's current data:

-   **Key:** A text input showing the attribute's current key.
-   **Value:** A text input showing the attribute's current value.

### Form Submission

-   When the "Update Attribute" button is clicked, the form data is sent to the server.
-   The server validates the input and updates the `Attribute` record in the database.
-   Upon successful update, the user is redirected back to the [Lexicon Show View](show.md) for the parent entry, where the updated attribute will be visible.
-   The form uses the `PUT` HTTP method for the update and includes CSRF (Cross-Site Request Forgery) protection for security.
