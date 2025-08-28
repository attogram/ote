# GitHub Codespaces Development Environment

This repository is configured to use [GitHub Codespaces](https://github.com/features/codespaces)
for a cloud-based development environment.

## Getting Started

1.  Click the "Code" button on the repository's main page.
2.  Select the "Codespaces" tab.
3.  Click "Create codespace on main".

GitHub will then create a new Codespace and set up the environment for you automatically. This includes:
- Building the Docker containers for the application, database, and Redis.
- Installing all Composer dependencies.
- Creating the `.env` file.
- Generating the application key.
- Running database migrations and seeding it with sample data.

## Usage

-   **Accessing the application:**
    Once the Codespace is ready, it will automatically forward the application's port (8000). To start the web server, run the following command in the terminal:
    ```bash
    php artisan serve --host=0.0.0.0 --port=8000
    ```
    You can then access the application from the "Ports" tab in the VS Code editor or by clicking the notification that appears.

-   **Running Artisan commands:**
    You can run `artisan` commands directly in the VS Code terminal:
    ```bash
    php artisan route:list
    ```

-   **Running NPM commands:**
    You can also run `npm` commands in the terminal:
    ```bash
    npm install
    npm run dev
    ```
