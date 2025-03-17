# Use official PHP image with CLI and FPM
FROM php:8.3-fpm

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev nodejs npm \
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www

# Copy the entire Laravel application first
COPY . .

<<<<<<< HEAD
RUN chmod -R 775 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --no-progress --optimize-autoloader

# Install frontend dependencies
RUN npm install && npm run build

RUN npm install && npm run build

EXPOSE 8080

# Set up Laravel permissions
RUN chmod -R 775 storage bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache
RUN chown -R www-data:www-data /var/www

# Copy the start script
COPY start.sh /usr/local/bin/start.sh

=======
# Ensure correct permissions for Laravel
RUN chmod -R 775 storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --no-progress --optimize-autoloader

# Install frontend dependencies
RUN npm install

# Copy the start script
COPY start.sh /usr/local/bin/start.sh

EXPOSE 8080

>>>>>>> c291b08ce0ef496562cdcfafe9c4276631718f64
# Start the Laravel server using the start script
CMD ["sh", "/usr/local/bin/start.sh"]
