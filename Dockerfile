# Dockerfile
# Builder stage
FROM public.ecr.aws/composer/composer:2 AS builder
WORKDIR /app
COPY . .
RUN composer install --no-dev --no-interaction --optimize-autoloader

# Production stage
FROM public.ecr.aws/php/php:8.2-fpm-alpine
WORKDIR /var/www

# Install dependencies
RUN apk add --no-cache nginx libpq

# Copy nginx configuration
COPY docker/production/nginx/nginx.conf /etc/nginx/nginx.conf

# Copy application files from builder stage
COPY --from=builder /app .

# Copy start script
COPY docker/production/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Set permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 80
EXPOSE 80

# Start supervisord
CMD ["/usr/local/bin/start.sh"]
