# Use official PHP image with CLI and FPM
FROM php:8.3-apache

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install apache
RUN apt-get update && apt-get install -y apache2

RUN a2enmod rewrite headers

# Set the working directory
WORKDIR /var/www

# Copy the entire Laravel application first
COPY . .

# Create necessary directories and set permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --no-progress --optimize-autoloader

# Install frontend dependencies
RUN npm install

# Ensure start script is executable
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

# Copy Apache virtual host configuration
COPY apache/apache.conf /etc/apache2/sites-available/000-default.conf

CMD ["/usr/local/bin/start.sh"]
