#!/bin/bash
set -e

# Ensure SQLite database file exists and is writable if using sqlite connection
if [ "${DB_CONNECTION:-sqlite}" = "sqlite" ]; then
    if [ ! -f /var/www/html/database/database.sqlite ]; then
        touch /var/www/html/database/database.sqlite
    fi
    chown -R www-data:www-data /var/www/html/database
    chmod -R 775 /var/www/html/database
fi

# Ensure storage directories exist and are writable
mkdir -p /var/www/html/storage/app/public /var/www/html/storage/framework/cache /var/www/html/storage/framework/sessions /var/www/html/storage/framework/views /var/www/html/storage/logs
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Ensure public storage symlink exists
php artisan storage:link || true

# Run migrations and seed data for demo platform
php artisan migrate --force || true
php artisan db:seed --force || true

# Optimize cache for production performance
php artisan optimize || true

# Execute the main container command (Apache)
exec "$@"
