# Admin: User Index View

The User Index view is the central dashboard for administrators to manage all user accounts in the system.

**URL:** `/admin/users`
**View File:** `resources/views/admin/users/index.blade.php`

## What it is

This page provides a complete list of all registered users and the primary tools for administering them. Access to this page is restricted to users with the 'admin' role.

## What it does

The view provides the following features:

1.  **Add New User Button:**
    A button at the top of the page links to the [User Create View](create.md), allowing administrators to create new user accounts manually.

2.  **User Table:**
    The core of the page is a table that lists all users with the following columns:
    -   **Name:** The user's name.
    -   **Email:** The user's email address.
    -   **Role:** The user's role (`user`, `editor`, or `admin`), which determines their permissions.

3.  **Actions:**
    For each user in the table, there are two actions available:
    -   **Edit:** A link to the [User Edit View](edit.md), where the user's details and role can be updated.
    -   **Delete:** A button that, when clicked, will prompt for confirmation ("Are you sure?") before permanently deleting the user account.

The page also displays a success message if a user was recently created, updated, or deleted.
