# Use official PHP image with CLI and FPM
FROM php:8.3-fpm

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www

# Copy project files
COPY . .

# Copy the start script
COPY start.sh /usr/local/bin/start.sh

# Install dependencies
RUN composer install

RUN composer update --no-scripts

EXPOSE 8080

# Set up Laravel permissions
RUN chmod -R 775 storage bootstrap/cache

# Start the Laravel server using the start script
CMD ["sh", "/usr/local/bin/start.sh"]