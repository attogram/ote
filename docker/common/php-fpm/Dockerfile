# docker/common/php-fpm/Dockerfile
FROM php:8.2-fpm-alpine AS production

# Install system dependencies
RUN apk add --no-cache \
    build-base \
    libpq-dev \
    oniguruma-dev \
    openssl-dev \
    libxml2-dev \
    curl-dev \
    icu-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-turbo-dev

# Install PHP extensions
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    bcmath \
    opcache \
    intl \
    zip \
    gd

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application files
COPY . .

# Install dependencies
RUN composer install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]

FROM production AS development

# Install all composer dependencies, including dev
RUN composer install --no-interaction --no-plugins --no-scripts --prefer-dist
