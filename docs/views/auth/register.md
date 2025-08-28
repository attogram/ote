# Register View

The Register view provides the interface for new users to create an account.

**URL:** `/register`
**View File:** `resources/views/auth/register.blade.php`

## What it is

This is a standard registration page that allows visitors to sign up for the application.

## What it does

The view presents a form with the following fields for a new user to fill out:

-   **Name:** The user's full name.
-   **Email:** The user's email address, which will be used for logging in.
-   **Password:** A new password for the account.
-   **Confirm Password:** The new password again, for verification.

### Form Submission

-   When the form is submitted, the server validates the input (e.g., checks for a unique email, ensures passwords match and meet complexity requirements).
-   If validation passes, a new user account is created in the database.
-   The user is then automatically logged in and redirected to the [Home View](home.md).
-   If validation fails, the page reloads with error messages explaining the issues (e.g., "The email has already been taken."). The `name` and `email` fields are repopulated to avoid re-typing.
-   The form includes CSRF (Cross-Site Request Forgery) protection for security.
