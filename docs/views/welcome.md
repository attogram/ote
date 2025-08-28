# Welcome View

The Welcome view is the public-facing landing page of the application. It is the first page a new visitor will see.

**URL:** `/`
**View File:** `resources/views/welcome.blade.php`

## What it is

This is the default welcome screen for a new Laravel application. It serves as a placeholder and entry point for visitors who are not yet authenticated.

## What it does

The primary purpose of this page is to provide basic navigation for new and returning users.

1.  **Displays Application Name:** It shows the application's name in the title.

2.  **Provides Authentication Links:**
    -   If the user is not logged in, it displays **"Log in"** and **"Register"** links, directing them to the [Login View](auth/login.md) and [Register View](auth/register.md) respectively.
    -   If the user is already logged in, it shows a **"Dashboard"** link that directs them to the [Home View](home.md).

3.  **Links to Laravel Resources:**
    The main content of the page contains links to external resources for learning about Laravel, the underlying framework:
    -   Laravel Documentation
    -   Laracasts (video tutorials)
    -   Laravel Cloud (deployment service)

This page is typically customized or replaced during application development to reflect the actual purpose and branding of the project.
