# Lexicon Edit Link View

The Lexicon Edit Link view provides a form to modify an existing link between two lexical entries.

**URL:** `/lexicon/{entry_id}/links/{link_id}/edit` (e.g., `/lexicon/1/links/3/edit`)
**View File:** `resources/views/lexicon/edit-link.blade.php`

## What it is

This page allows users to change the details of a relationship between two entries, such as modifying the link type or changing the target entry altogether.

## What it does

The view displays the ID of the source lexical entry and presents a form pre-populated with the link's current data:

-   **Link Type:** A text input showing the current type of the link (e.g., "translation").
-   **Target Entry:** A dropdown menu of all other lexical entries, with the current target entry pre-selected.

### Form Submission

-   When the "Update Link" button is clicked, the form data is sent to the server.
-   The server validates the input and updates the `Link` record in the database.
-   Upon successful update, the user is redirected back to the [Lexicon Show View](show.md) for the source entry, where the updated link will be visible.
-   The form uses the `PUT` HTTP method for the update and includes CSRF (Cross-Site Request Forgery) protection for security.
