#!/bin/sh

# Export environment variables from .env file
export $(grep -v '^#' .env | xargs)

# Fungsi untuk menunggu database siap
echo "Menunggu database siap..."
until php -r "try { new PDO('mysql:host=${DB_HOST};dbname=${DB_DATABASE}', '${DB_USERNAME}', '${DB_PASSWORD}'); echo 'Database siap.'; } catch (PDOException \$e) { exit(1); }"; do
    sleep 3
    echo "Menunggu database..."
done || { echo "Gagal terhubung ke database."; exit 1; }

# Generate application key if not already set
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

# Run migrations
php artisan migrate:fresh

# Check if seeders should be run
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "Running seeders..."
    php artisan db:seed
else
    echo "Skipping seeders..."
fi

php artisan serve --host=0.0.0.0 --port=9000