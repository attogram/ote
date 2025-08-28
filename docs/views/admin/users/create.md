# Admin: User Create View

The User Create view provides a form for administrators to manually create a new user account.

**URL:** `/admin/users/create`
**View File:** `resources/views/admin/users/create.blade.php`

## What it is

This page allows an administrator to bypass the public registration process and create a new user directly, with the ability to assign a specific role from the outset.

## What it does

The view presents a comprehensive form for creating a new user:

-   **Name:** The new user's name.
-   **Email:** The new user's email address.
-   **Password:** A password for the new account.
-   **Confirm Password:** The password again for verification.
-   **Role:** A dropdown menu to assign a role to the new user. The options are `User`, `Worker` (Editor), and `Admin`.

### Form Submission

-   When the "Create User" button is clicked, the form data is sent to the server.
-   The server validates the input (e.g., checks for a unique email, ensures passwords match).
-   If validation passes, a new `User` record is created with the specified details and role.
-   The administrator is then redirected back to the [User Index View](index.md) with a success message.
-   If validation fails, the form is re-displayed with error messages.
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.
