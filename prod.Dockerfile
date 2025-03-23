# Use official PHP image with CLI and FPM
FROM php:8.3-apache

# Set the working directory
WORKDIR /var/www

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install apache
RUN apt-get update && apt-get install -y apache2

# Set environment variables for Apache
ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    APACHE_LOG_DIR=/var/log/apache2 \
    APACHE_PID_FILE=/var/run/apache2/apache2.pid \
    APACHE_RUN_DIR=/var/run/apache2 \
    APACHE_LOCK_DIR=/var/lock/apache2

RUN a2enmod rewrite headers

# Copy Apache virtual host configuration
COPY apache/apache.conf /etc/apache2/sites-available/000-default.conf

# Copy the entire Laravel application first
COPY --chown=www-data:www-data . /var/www/

# Create necessary directories and set permissions
RUN chown -R www-data:www-data /var/www

RUN chmod -R 755 /var/www

RUN ls -la /var/www
# RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
#     && chmod -R 775 storage bootstrap/cache \
#     && chown -R www-data:www-data storage bootstrap/cache

# Ensure required directories exist and are owned by www-data
RUN mkdir -p /var/run/apache2 /var/lock/apache2 /var/log/apache2 && \
    chown -R www-data:www-data /var/run/apache2 /var/lock/apache2 /var/log/apache2

# Install PHP dependencies
RUN composer install --no-interaction --no-progress --optimize-autoloader

# Install frontend dependencies
RUN npm install

# Ensure start script is executable
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

CMD ["/usr/local/bin/start.sh"]
