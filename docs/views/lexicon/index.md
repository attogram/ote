# Lexicon Index View

The Lexicon Index view is the main browsing interface for all lexical entries in the system.

**URL:** `/lexicon`
**View File:** `resources/views/lexicon/index.blade.php`

## What it is

This page provides a complete list of all lexical entries, which represent words or phrases in specific languages. It's the primary entry point for exploring the contents of the dictionary.

## What it does

The view offers the following functionalities:

1.  **Add New Entry Button:**
    For users with sufficient permissions, this button provides a direct link to the [Lexicon Create View](create.md) to add a new entry to the dictionary.

2.  **Suggest a New Entry Button:**
    For authenticated users, this button provides a link to the [Suggestion Create View](../suggestions/create.md). This allows them to suggest a new lexical entry for review by an editor or administrator.

3.  **Lexical Entry List:**
    The main part of the page is a list of all lexical entries.
    -   Each entry is displayed as its token text followed by the language code in parentheses (e.g., "hello (en)").
    -   Each list item is a link that navigates to the [Lexicon Show View](show.md) for that specific entry, where detailed information can be found.

This page serves as a comprehensive directory of the lexicon's contents and a starting point for both adding and suggesting new content.
