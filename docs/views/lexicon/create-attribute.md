# Lexicon Create Attribute View

The Lexicon Create Attribute view provides a form to add a new attribute (a key-value pair) to a specific lexical entry.

**URL:** `/lexicon/{id}/attributes/create` (e.g., `/lexicon/1/attributes/create`)
**View File:** `resources/views/lexicon/create-attribute.blade.php`

## What it is

This page allows users to add supplementary information to a lexical entry. Attributes can be used to store data like pronunciation, grammatical gender, part of speech, or any other relevant metadata.

## What it does

The view displays the ID of the lexical entry being modified and presents a form with two text fields:

-   **Key:** The name of the attribute (e.g., "gender", "pronunciation").
-   **Value:** The value of the attribute (e.g., "masculine", "/həˈloʊ/").

### Form Submission

-   When the "Create Attribute" button is clicked, the form data is sent to the server.
-   The server validates the input and creates a new `Attribute` record associated with the lexical entry.
-   Upon successful creation, the user is redirected back to the [Lexicon Show View](show.md) for the parent entry, where the new attribute will now be visible in the attributes table.
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.
