# Suggestion Show View

The Suggestion Show view provides a detailed breakdown of a single user suggestion, allowing administrators to review and act upon it.

**URL:** `/suggestions/{id}` (e.g., `/suggestions/1`)
**View File:** `resources/views/suggestions/show.blade.php`

## What it is

This is the moderation page for an individual suggestion. It presents all the relevant information about the proposed change so that an editor or administrator can make an informed decision.

## What it does

The view displays the following details about the suggestion:

-   **User:** The name and email of the user who submitted the suggestion.
-   **Type:** The nature of the suggestion (`create`, `update`, or `delete`).
-   **Model:** The type of data the suggestion applies to (e.g., `LexicalEntry`) and its ID (if applicable).
-   **Suggested Data:** A JSON object showing the exact data being proposed. For an `update`, this shows the new values. For a `create`, it shows the complete data for the new item. For a `delete`, this may be empty.
-   **Status:** The current status of the suggestion (`pending`, `approved`, `rejected`).

### Actions

If the suggestion's status is `pending`, two action buttons are displayed:

-   **Approve:** This button will trigger the logic to apply the suggested change to the database. For example, it might create a new lexical entry, update an existing one, or delete one. After the action is performed, the suggestion's status is set to `approved`.
-   **Reject:** This button will simply change the suggestion's status to `rejected` without making any changes to the lexicon data.

Once a suggestion is approved or rejected, these buttons are no longer visible on the page.
