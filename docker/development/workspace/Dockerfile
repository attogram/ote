# docker/development/workspace/Dockerfile
FROM public.ecr.aws/php/php:8.2-cli-alpine

# Set environment variables for user and group ID
ARG UID=1000
ARG GID=1000
ARG NODE_VERSION=18

# Install system dependencies
RUN apk add --no-cache \
    curl \
    unzip \
    libpq-dev \
    libonig-dev \
    libssl-dev \
    libxml2-dev \
    libcurl4-openssl-dev \
    libicu-dev \
    libzip-dev \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_pgsql \
    pgsql \
    opcache \
    intl \
    zip \
    bcmath \
    soap

# Install Composer
COPY --from=public.ecr.aws/composer/composer:2 /usr/bin/composer /usr/bin/composer

# Create a non-root user
RUN addgroup -g ${GID} www && \
    adduser -u ${UID} -G www -s /bin/sh -D www

# Switch to the non-root user
USER www

# Set the working directory
WORKDIR /var/www

# Default command to keep the container running
CMD ["tail", "-f", "/dev/null"]
