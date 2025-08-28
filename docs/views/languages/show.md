# Language Show View

The Language Show view provides a detailed look at a single language and all the lexical entries associated with it.

**URL:** `/languages/{id}` (e.g., `/languages/1`)
**View File:** `resources/views/languages/show.blade.php`

## What it is

This page is dedicated to displaying the contents of a specific language. It's the primary way to see all the words and phrases that have been cataloged for that language.

## What it does

The view displays the following information:

1.  **Language Header:**
    The name and code of the language are prominently displayed at the top of the page.

2.  **List of Lexical Entries:**
    The main content of the page is a list of all lexical entries that belong to this language.
    -   Each entry is represented by its token's text (the word or phrase).
    -   Each item in the list is a link that navigates to the corresponding [Lexicon Show View](../lexicon/show.md), where more details about that specific entry can be found.
    -   If the language has no lexical entries, a message "No lexical entries found for this language" is displayed instead.

This view is read-only and serves as a way to browse the contents of a language's dictionary.
