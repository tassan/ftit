FROM php:8.2-apache

WORKDIR /var/www/html

# Suppress ServerName warning
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Install common PHP extensions useful for Laravel
RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        libzip-dev \
        unzip \
    && docker-php-ext-install pdo pdo_mysql zip \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules needed for Laravel
RUN a2enmod rewrite

# Set Apache document root to Laravel's public directory
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# Copy Laravel application (for production image; in dev this will be overridden by a bind mount)
COPY laravel-app/ /var/www/html/

# Set permissions for storage and cache
RUN chown -R www-data:www-data storage bootstrap/cache || true

EXPOSE 80
