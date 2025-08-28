# Tokens Index View

The Tokens Index view is the main page for managing all the tokens in the system.

**URL:** `/tokens`
**View File:** `resources/views/tokens/index.blade.php`

## What it is

This page displays a complete list of all tokens that have been created. A token is a language-agnostic representation of a word or phrase, forming the basic building block of the lexicon.

## What it does

The view provides the following features:

1.  **Add New Token Button:**
    A button at the top of the page links to the [Token Create View](create.md), allowing users to add a new unique token.

2.  **Token Table:**
    The main part of the page is a table that lists all tokens with the following columns:
    -   **ID:** The unique identifier for the token.
    -   **Text:** The text of the token. The text is a link that navigates to the [Token Show View](show.md) for that specific token.

3.  **Actions:**
    For each token in the table, there are two actions available:
    -   **Edit:** A link to the [Token Edit View](edit.md), where the token's text can be updated.
    -   **Delete:** A button that, when clicked, will prompt the user for confirmation ("Are you sure?") before deleting the token and all of its associated data (including lexical entries and links).

This page provides a complete interface for viewing, adding, editing, and deleting tokens.
