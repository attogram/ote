# Login View

The Login view provides the interface for users to authenticate and access their accounts.

**URL:** `/login`
**View File:** `resources/views/auth/login.blade.php`

## What it is

This is a standard login page that allows registered users to sign into the application.

## What it does

The view presents a form with the following fields:

-   **Email:** The user's registered email address.
-   **Password:** The user's password.

### Form Submission

-   When the form is submitted, the credentials are sent to the server for verification.
-   If the credentials are correct, the user is authenticated and redirected to the [Home View](home.md).
-   If the credentials are incorrect, the page reloads with an error message, and the email field is repopulated for convenience.
-   The form includes CSRF (Cross-Site Request Forgery) protection to ensure security.
