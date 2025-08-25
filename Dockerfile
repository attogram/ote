# Use a base image with PHP and the necessary extensions
FROM php:8.2-fpm-alpine

# Install system dependencies and PHP extensions
RUN apk add --no-cache \
    git \
    build-base \
    postgresql-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    && docker-php-ext-install pdo pdo_pgsql gd \
    && rm -rf /var/cache/apk/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www/html

# Copy your application files
COPY . .

# Run the build commands
RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan migrate --force

# Expose port 8000 for the PHP server
EXPOSE 8000

# Set the start command
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8000"]
