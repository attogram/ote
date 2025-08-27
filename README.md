# Open Translation Engine (OTE) v2

[![Run Tests](https://github.com/attogram/ote/actions/workflows/tests.yml/badge.svg)](https://github.com/attogram/ote/actions/workflows/tests.yml)

This is the development repository for the **Open Translation Engine (OTE) v2**, a collaborative translation dictionary manager. This project is currently a work-in-progress.

## OTE v2 Development

OTE v2 is being built with [Laravel](https://laravel.com/) and is under active development. The goal is to create a modern, robust, and easy-to-use translation engine.

For a detailed, step-by-step guide on the MVP build process, please see [OTE2.md](OTE2.md).

### Getting Started with OTE v2 (master branch)

To get started with the development of OTE v2, you will need to have PHP and Composer installed on your system.

1.  **Clone the repository:**
    ```bash
    git clone https://github.com/attogram/ote.git
    cd ote
    ```
2.  **Install dependencies:**
    ```bash
    composer install
    ```
3.  **Create the environment file:**
    ```bash
    cp .env.example .env
    ```
4.  **Generate the application key:**
    ```bash
    php artisan key:generate
    ```

### Testing

For information on how to run the test suite, please see the [Testing Documentation](tests/README.md).

### Deployment

This project is configured for automated deployment on [Render](https://render.com/). For detailed instructions on how to deploy your own instance, please see the [Render Deployment Guide](docs/RENDER.md).

### OTE v2 TODO

The following is a summary of the planned features for OTE v2. For a more detailed list, see [docs/todo.md](docs/todo.md).

*   **Architecture:** Laravel, PHP >= 7.1.3, support for multiple databases.
*   **Features:** Anonymous use, user authentication, user levels.
*   **Public Features:** Language and dictionary lists, browsing, exporting, searching, and more.
*   **Editor Features:** Word and word pair management, imports.
*   **Admin Features:** Language and user management.

## OTE v1

The previous version of OTE is still available.

*   The last stable release is **OTE v0.9.9**: [v0.9.9 branch](https://github.com/attogram/ote/tree/v0.9.9)
*   OTE Version 1 was a test with the Attogram Framework: [v1 branch](https://github.com/attogram/ote/tree/v1)

## Citations

Multilingual Online Resources for Minority Languages of a Campus Community

*   Nur Asmaa Adila Mohamad et al. / Procedia - Social and Behavioral Sciences 27 ( 2011 ) 291 – 298
*   <https://www.sciencedirect.com/science/article/pii/S1877042811024372>
*   <https://doi.org/10.1016/j.sbspro.2011.10.610>
*   "In developing this prototype multilingual dictionary, the available features in OTE 0.9.8 are of great
    help to get started. At the same time there are some weaknesses that can be improved ..."

## License

The Open Translation Engine is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Development with GitHub Codespaces

This repository is configured to use [GitHub Codespaces](https://github.com/features/codespaces) for a cloud-based development environment.

### Getting Started

1.  Click the "Code" button on the repository's main page.
2.  Select the "Codespaces" tab.
3.  Click "Create codespace on main".

GitHub will then create a new Codespace and set up the environment for you automatically. This includes:
- Building the Docker containers for the application, database, and Redis.
- Installing all Composer dependencies.
- Creating the `.env` file.
- Generating the application key.
- Running database migrations and seeding it with sample data.

### Usage

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

## Development Environment with Docker

This project includes a Docker-based development environment that allows you to run the application and its dependencies in isolated containers.

### Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

### Setup

1.  **Build and start the containers:**

    ```bash
    docker compose -f compose.dev.yml up --build -d
    ```

    This command will build the Docker images and start the services in detached mode.

2.  **Install Composer dependencies:**

    Open a new terminal and run the following command to install the PHP dependencies using Composer.

    ```bash
    docker compose -f compose.dev.yml exec workspace composer install
    ```

3.  **Copy the environment file:**

    ```bash
    cp .env.example .env
    ```

4.  **Generate the application key:**

    ```bash
    docker compose -f compose.dev.yml exec workspace php artisan key:generate
    ```

5.  **Run database migrations:**

    ```bash
    docker compose -f compose.dev.yml exec workspace php artisan migrate
    ```

6.  **Seed the database (optional):**

    To populate the database with sample data, run the following command:
    ```bash
    docker compose -f compose.dev.yml exec workspace php artisan db:seed
    ```

### Usage

-   **Accessing the application:**

    Once the containers are running, you can access the application in your web browser at [http://localhost](http://localhost).

-   **Running Artisan commands:**

    To run any `artisan` command, use `docker compose exec workspace php artisan <command>`. For example:

    ```bash
    docker compose -f compose.dev.yml exec workspace php artisan route:list
    ```

-   **Running Composer commands:**

    To run any `composer` command, use `docker compose exec workspace composer <command>`. For example:

    ```bash
    docker compose -f compose.dev.yml exec workspace composer update
    ```

-   **Running NPM commands:**

    To run any `npm` command, use `docker compose exec workspace npm <command>`. For example, to compile the frontend assets:

    ```bash
    docker compose -f compose.dev.yml exec workspace npm install
    docker compose -f compose.dev.yml exec workspace npm run dev
    ```

-   **Stopping the environment:**

    To stop the containers, run:

    ```bash
    docker compose -f compose.dev.yml down
    ```

### Related Projects

*   <https://github.com/elexis-eu/lexonomy>
*   <http://www.omegawiki.org/>
*   <https://github.com/glosswordteam/Glossword>

### Known Installations of OTE v1

*   <http://ote.2meta.com/>
*   <http://indo-european.info/dictionary-translator/>
*   <http://indo-european.info/translator-dictionary/>
*   <http://indogermanisch.org/woerterbuch-uebersetzer/>
*   <http://www.elas.sk/lehota/slovnik/>
*   <http://fenry.lescigales.org/ryzom/otr/>
*   <http://indo-european.info/pokorny-etymology-dictionary/>
*   <http://dictionar.poezie.ro/>
