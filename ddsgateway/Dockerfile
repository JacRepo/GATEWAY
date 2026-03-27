FROM php:8.2-cli

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    libpq-dev

# Install PHP extensions
RUN docker-php-ext-install zip pdo pdo_pgsql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy project files into container
COPY ddsgateway/ /var/www/ddsgateway

# Move into app directory
WORKDIR /var/www/ddsgateway

# Install PHP dependencies
RUN composer install --no-interaction --prefer-dist

# Expose port
EXPOSE 10000

# Start the Laravel/Lumen server
CMD php -S 0.0.0.0:10000 -t public