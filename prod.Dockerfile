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

RUN chown -R www-data:www-data /var/www
RUN chmod -R 775 /var/www
# Create necessary directories and set permissions
RUN mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# Install PHP dependencies
RUN composer install --no-interaction --no-progress --optimize-autoloader

RUN php artisan storage:link

# Install frontend dependencies
RUN npm install

# Ensure start script is executable
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 9000

CMD ["/usr/local/bin/start.sh"]

# services:
#     app:
#       image: bujank/sipta-kelolajadwalruangan:latest
#       container_name: kelola_ruangan_app
#       restart: unless-stopped
#       tty: true
#       env_file:
#         - .env
#       ports:
#         - "8005:80"
#       networks:
#         - app_network
#       depends_on:
#         - db
  
#     db:
#       image: mysql:8.0
#       container_name: kelola_ruangan_db
#       restart: unless-stopped
#       environment:
#         MYSQL_DATABASE: ${DB_DATABASE}
#         MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
#         MYSQL_USER: ${DB_USERNAME}
#         MYSQL_PASSWORD: ${DB_PASSWORD}
#       ports:
#         - "3310:3306"
#       networks:
#         - app_network
#       volumes:
#         - db_data:/var/lib/mysql
  
#   networks:
#     app_network:
  
#   volumes:
#     db_data: