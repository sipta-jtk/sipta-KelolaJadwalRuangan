# Use official PHP image with CLI and FPM
FROM php:8.3-fpm

# Install required system dependencies
RUN apt-get update && apt-get install -y \
    git unzip libpq-dev libpng-dev libjpeg-dev libfreetype6-dev nodejs npm\
    && docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set the working directory
WORKDIR /var/www

# Copy project files
COPY . .

RUN chmod -R 775 storage bootstrap/cache

# Install dependencies
RUN composer install

RUN composer update --no-scripts

RUN npm install && npm run build

EXPOSE 8080

# Set up Laravel permissions
RUN chmod -R 775 storage bootstrap/cache
RUN chmod -R 775 /var/www/storage /var/www/bootstrap/cache
RUN chown -R www-data:www-data /var/www

# Copy the start script
COPY start.sh /usr/local/bin/start.sh

# Start the Laravel server using the start script
CMD ["sh", "/usr/local/bin/start.sh"]