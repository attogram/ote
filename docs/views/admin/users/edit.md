# Admin: User Edit View

The User Edit view provides a form for administrators to modify an existing user's account details.

**URL:** `/admin/users/{id}/edit` (e.g., `/admin/users/5/edit`)
**View File:** `resources/views/admin/users/edit.blade.php`

## What it is

This page allows an administrator to update a user's name, email, password, and role.

## What it does

The view presents a form pre-populated with the user's current information:

-   **Name:** The user's current name.
-   **Email:** The user's current email address.
-   **New Password:** An optional field to set a new password for the user.
-   **Confirm New Password:** Must be filled in if a new password is set.
-   **Role:** A dropdown menu showing the user's current role, which can be changed.

### Form Submission

-   When the "Update User" button is clicked, the form data is sent to the server.
-   The server validates the input (e.g., ensures email is unique if changed, checks that passwords match if provided).
-   If validation passes, the `User` record is updated with the new information.
-   The administrator is then redirected back to the [User Index View](index.md) with a success message.
-   If validation fails, the form is re-displayed with error messages.
-   The form uses the `PUT` HTTP method for the update and includes CSRF (Cross-Site Request Forgery) protection for security.
