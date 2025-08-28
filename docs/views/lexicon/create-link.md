# Lexicon Create Link View

The Lexicon Create Link view provides a form to create a relationship (a link) between the current lexical entry and another entry in the lexicon.

**URL:** `/lexicon/{id}/links/create` (e.g., `/lexicon/1/links/create`)
**View File:** `resources/views/lexicon/create-link.blade.php`

## What it is

This page is used to define how two lexical entries relate to each other. This is the foundation of the translation dictionary, allowing the creation of translations, synonyms, antonyms, etc.

## What it does

The view displays the ID of the source lexical entry and presents a form with the following fields:

-   **Link Type:** A text input where the user can define the type of relationship (e.g., "translation", "synonym", "antonym").
-   **Target Entry:** A dropdown menu listing all other lexical entries in the database. The user selects the entry they want to link to.

### Form Submission

-   When the "Create Link" button is clicked, the form data is sent to the server.
-   The server validates the input and creates a new `Link` record connecting the source entry to the selected target entry with the specified type.
-   Upon successful creation, the user is redirected back to the [Lexicon Show View](show.md) for the source entry, where the new link will be visible in the links table.
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.
