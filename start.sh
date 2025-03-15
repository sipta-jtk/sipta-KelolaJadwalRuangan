#!/bin/sh

export $(grep -v '^#' .env | xargs)

php artisan key:generate

# Run migrations
php artisan migrate:fresh

# Check if seeders should be run
if [ "$RUN_SEEDERS" = "true" ]; then
    echo "Running seeders..."
    php artisan db:seed
else
    echo "Skipping seeders..."
fi

# Start the Laravel server
php artisan serve --host=0.0.0.0 --port=8080