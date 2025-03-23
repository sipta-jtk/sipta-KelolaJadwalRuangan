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

RUN a2enmod rewrite

# Set the working directory
WORKDIR /var/www

# Copy the entire Laravel application first
COPY . .


# Create necessary directories and set permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# RUN chown -R www-data:www-data /var/log/apache2

# Set environment variables for Apache
ENV APACHE_RUN_USER=www-data \
    APACHE_RUN_GROUP=www-data \
    APACHE_LOG_DIR=/var/log/apache2 \
    APACHE_PID_FILE=/var/run/apache2/apache2.pid \
    APACHE_RUN_DIR=/var/run/apache2 \
    APACHE_LOCK_DIR=/var/lock/apache2

# Pastikan direktori tersebut ada
RUN mkdir -p ${APACHE_RUN_DIR} ${APACHE_LOCK_DIR} ${APACHE_LOG_DIR} && \
    chown -R www-data:www-data ${APACHE_RUN_DIR} ${APACHE_LOCK_DIR} ${APACHE_LOG_DIR}

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
