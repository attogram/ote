# Suggestion Create View

The Suggestion Create view provides a form for users to submit suggestions for changes to the lexicon.

**URL:** `/suggestions/create` (with query parameters)
**View File:** `resources/views/suggestions/create.blade.php`

## What it is

This is a versatile form that allows authenticated users to propose creations, updates, or deletions of lexicon data. The form's behavior is controlled by query parameters in the URL.

## What it does

The view presents a form that adapts based on the type of suggestion being made.

-   **Hidden Fields:** The form contains hidden inputs for `type`, `model_type`, and `model_id`, which are pre-filled based on the link the user clicked to get to the page (e.g., from the [Lexicon Show View](lexicon/show.md)).
-   **Current Data Display:** If the suggestion is for an `update` or `delete`, the page displays the current data of the item in question in a read-only JSON format. This gives the user context for their suggested change.
-   **Suggestion Data Text Area:** This is the main input field where the user writes their suggestion. It is a textarea where the user is expected to provide data in JSON format.
    -   For a `create` suggestion, the user would provide the full JSON for the new item.
    -   For an `update` suggestion, the user would provide the JSON keys and values they wish to change.

### Form Submission

-   When the "Submit Suggestion" button is clicked, the form data, including the hidden fields and the user-provided JSON, is sent to the server.
-   The server validates the input, ensuring the JSON is valid.
-   A new `Suggestion` record is created with a `pending` status.
-   The user is then redirected to the [Suggestions Index View](index.md) with a success message, where they can see their suggestion awaiting review.
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.
