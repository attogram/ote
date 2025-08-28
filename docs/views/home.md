# Home View

The Home view serves as the main dashboard for authenticated users. It provides a quick overview of the lexicon's status and offers navigation to the main management areas of the application.

**URL:** `/home`
**View File:** `resources/views/home.blade.php`

## What it is

This page is the central hub for users who have logged into the system. It displays key statistics and provides quick links to major features.

## What it does

The Home view performs the following functions:

1.  **Displays Lexicon Statistics:**
    It shows a summary of the total number of:
    -   Tokens
    -   Languages
    -   Lexical Entries
    -   Attributes
    -   Links

2.  **Shows Entries per Language:**
    It lists each language and the number of lexical entries associated with it, giving a quick look at the completeness of each language's dictionary.

3.  **Provides Navigation:**
    It includes prominent buttons that link to the main management pages:
    -   **Manage Tokens:** Links to the [Token Index View](tokens/index.md).
    -   **Manage Languages:** Links to the [Language Index View](languages/index.md).
    -   **Manage Lexical Entries:** Links to the [Lexicon Index View](lexicon/index.md).

4.  **Offers Admin Tools:**
    It provides a link to the [Validate View](validate.md), which allows users to check the data integrity of the lexicon.
