# Lexicon Create View

The Lexicon Create view provides a form to create a new lexical entry by associating a token with a language.

**URL:** `/lexicon/create`
**View File:** `resources/views/lexicon/create.blade.php`

## What it is

This page allows users to create a new entry in the dictionary. A lexical entry is the core concept that represents a word or phrase in a specific language.

## What it does

The view presents a form with two dropdown menus:

-   **Token:** A dropdown list of all existing tokens in the system. The user must select the token they want to create an entry for.
-   **Language:** A dropdown list of all available languages. The user must select the language to which the token belongs for this entry.

### Form Submission

-   When the "Create Entry" button is clicked, the selected `token_id` and `language_id` are sent to the server.
-   The server validates the input and creates a new `LexicalEntry` record linking the two.
-   If an entry for this specific token and language already exists, the system will prevent a duplicate from being created.
-   Upon successful creation, the user is redirected to the [Lexicon Index View](index.md).
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.

**Note:** Before a lexical entry can be created, both the desired token and language must already exist in the system. They can be added via the [Token Management](../tokens/index.md) and [Language Management](../languages/index.md) sections, respectively.
