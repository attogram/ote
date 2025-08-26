# Jules' Development Environment Setup

This document outlines the steps to set up a development environment for this project.

## 1. Install PHP

First, you need to install PHP. The recommended version is PHP 8.2 or higher.

On a Debian-based Linux distribution, you can install PHP and some common utilities using the following command:

```bash
sudo apt-get update
sudo apt-get install -y php php-cli unzip
```

## 2. Install Required PHP Extensions

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

## 3. Install Composer

Composer is a dependency manager for PHP. You can install it locally with the following commands:

```bash
# Create a bin directory
mkdir -p bin

# Download the installer and install it in the bin directory
curl -sS https://getcomposer.org/installer | php -- --install-dir=bin --filename=composer
```

## 4. Install Project Dependencies

After installing composer, you need to install the project dependencies:

```bash
./bin/composer install
```
