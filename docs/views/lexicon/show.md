# Lexicon Show View

The Lexicon Show view provides a detailed, all-in-one look at a single lexical entry and its associated data.

**URL:** `/lexicon/{id}` (e.g., `/lexicon/1`)
**View File:** `resources/views/lexicon/show.blade.php`

## What it is

This is the main "dictionary entry" page. It displays the entry's token and language, and also serves as a hub for managing all the data connected to that entry, such as attributes and links to other entries.

## What it does

This comprehensive view is divided into several sections:

1.  **Header:**
    -   Displays the token text and language code of the entry (e.g., "hello (en)").
    -   Provides a "Back to list" link to return to the [Lexicon Index View](index.md).

2.  **Attributes Section:**
    -   Lists all attributes (key-value pairs) associated with the entry in a table.
    -   Includes an "Add Attribute" button that links to the [Create Attribute View](create-attribute.md).
    -   For each attribute, it provides "Edit" and "Delete" actions, linking to the [Edit Attribute View](edit-attribute.md) and a deletion confirmation, respectively.

3.  **Links Section:**
    -   Lists all relationships (links) where this entry is the source.
    -   The table shows the `Type` of link and the `Target Entry`. The target entry is a link to its own `show` page.
    -   Includes an "Add Link" button that links to the [Create Link View](create-link.md).
    -   For each link, it provides "Edit" and "Delete" actions, linking to the [Edit Link View](edit-link.md) and a deletion confirmation.

4.  **Suggest a Change Section:**
    -   For authenticated users, this section provides two buttons:
        -   **Suggest an Update:** Links to the [Suggestion Create View](../suggestions/create.md) to propose a modification to the entry.
        -   **Suggest Deletion:** Links to the [Suggestion Create View](../suggestions/create.md) to propose that the entry be deleted.
    -   For visitors who are not logged in, a message prompts them to log in to suggest a change.

This page is the central point for viewing and managing the rich data associated with a single lexical entry.
