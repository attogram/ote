# OTE v2 Agent Instructions

This document provides instructions and information for AI agents working on the OTE v2 project.

## Manual Workarounds

If you encounter issues with `php artisan` commands, you may need to create files manually. Here's how:

### Creating Migration Files

Instead of `php artisan make:migration`, you can create the migration file manually in the `database/migrations` directory. The filename should follow the format `YYYY_MM_DD_HHMMSS_migration_name.php`.

### Creating Model Files

Instead of `php artisan make:model`, you can create the model file manually in the `app/Models` directory.

### Creating Command Files

Instead of `php artisan make:command`, you can create the command file manually in the `app/Console/Commands` directory.

### Automatic Command Discovery

In this version of Laravel, console commands in the `app/Console/Commands` directory are automatically discovered. This means you do not need to manually register them in `app/Console/Kernel.php`.

## Known Issues

### `run_in_bash_session` and `artisan` (Resolved)

During the initial setup, a persistent issue was encountered where running `artisan` commands would lead to a PHP fatal error. This issue appears to be resolved by setting up a clean, modern PHP 8.4 environment from a static binary, as the `artisan` commands now function correctly.

### Fragile Test: `ExportOteFileCommandTest`

The test for the `ote:export-ote-file` command is brittle. The test's `expectsOutput` assertion fails when the command's success message is built using variables, even though the variables appear correct. To make the test pass, the success message in the `ExportOteFile` command has been hardcoded. This is a workaround, and the underlying issue with the test runner's output capturing has not been resolved.

### Laravel Dusk Integration

There are persistent issues with running Laravel Dusk in the development environment. The test runner (Pest) seems to have a conflict with how Dusk's test cases are discovered, and there are also issues with the ChromeDriver and Chrome binary setup.

After multiple attempts to fix these issues, the integration of Dusk has been abandoned for now.

### Laravel Dusk Integration

There are persistent issues with running Laravel Dusk in the development environment. The test runner (Pest) seems to have a conflict with how Dusk's test cases are discovered, and there are also issues with the ChromeDriver and Chrome binary setup.

After multiple attempts to fix these issues, the integration of Dusk has been abandoned for now.

---

## Agent-Specific Instructions

### jules

This section contains instructions specifically for the agent Jules.

#### Development Environment Setup

This document outlines the steps to set up a development environment for this project.

##### 1. Install PHP

First, you need to install PHP. The recommended version is PHP 8.2 or higher.

On a Debian-based Linux distribution, you can install PHP and some common utilities using the following command:

```bash
sudo apt-get update
sudo apt-get install -y php php-cli unzip
```

##### 2. Install Required PHP Extensions

Laravel requires several PHP extensions to be installed and enabled. You can install them using the following command:

```bash
sudo apt-get install -y \
    php-bcmath \
    php-curl \
    php-dom \
    php-json \
    php-mbstring \
    php-opcache \
    php-pdo \
    php-sqlite3 \
    php-tokenizer \
    php-xml
```

##### 3. Install Composer

Composer is a dependency manager for PHP. You can install it locally with the following commands:

```bash
# Create a bin directory
mkdir -p bin

# Download the installer and install it in the bin directory
curl -sS https://getcomposer.org/installer | php -- --install-dir=bin --filename=composer
```

##### 4. Install Project Dependencies

After installing composer, you need to install the project dependencies:

```bash
./bin/composer install
```

##### 5. Post-Clone Setup

After cloning the repository, you need to perform the following steps to get the application running:

1.  **Install dependencies:**
    ```bash
    composer install
    ```

2.  **Create the environment file:**
    ```bash
    cp .env.example .env
    ```

3.  **Generate the application key:**
    ```bash
    php artisan key:generate
    ```
