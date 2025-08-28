# Docker Development Environment

This project includes a Docker-based development environment that allows you to run the application and its dependencies in isolated containers.

## Prerequisites

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Setup

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

## Usage

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
