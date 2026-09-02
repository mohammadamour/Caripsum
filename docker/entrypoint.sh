#!/bin/bash
set -e

# Ensure storage directories exist
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

# Ensure SQLite file exists
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        touch /var/www/html/database/database.sqlite
    fi
fi

# Ensure valid base64 APP_KEY is present
if [[ -z "$APP_KEY" || "$APP_KEY" != base64:* ]]; then
    echo "Generating and exporting valid Laravel base64 APP_KEY..."
    export APP_KEY=$(php -r 'echo "base64:" . base64_encode(random_bytes(32));')
fi

# Ensure storage symlink exists
php artisan storage:link || true

# Run database migrations and seed catalog
php artisan migrate --force || true
php artisan db:seed --force || true

# Clear and rebuild production optimizations
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Grant www-data ownership of database and storage for read/write access
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Execute the main container command (Apache)
exec "$@"
