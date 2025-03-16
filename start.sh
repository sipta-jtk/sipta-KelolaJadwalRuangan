#!/bin/sh

# Export environment variables from .env file
export $(grep -v '^#' .env | xargs)

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

# Use concurrently to run both Laravel server and Vite
npx concurrently "php artisan serve --host=0.0.0.0 --port=8080" "npm run dev"