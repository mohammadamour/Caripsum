#!/bin/bash
set -e

# Ensure storage & database directories exist
mkdir -p /var/www/html/storage/app/public \
         /var/www/html/storage/framework/cache/data \
         /var/www/html/storage/framework/sessions \
         /var/www/html/storage/framework/views \
         /var/www/html/storage/logs \
         /var/www/html/bootstrap/cache \
         /var/www/html/database

# Ensure SQLite file exists
if [ ! -f /var/www/html/database/database.sqlite ]; then
    touch /var/www/html/database/database.sqlite
fi

# Ensure valid base64 APP_KEY is generated if missing
if [[ -z "$APP_KEY" || "$APP_KEY" != base64:* ]]; then
    echo "Generating valid Laravel base64 APP_KEY..."
    export APP_KEY=$(php -r 'echo "base64:" . base64_encode(random_bytes(32));')
fi

# Create a baseline runtime .env if one does not exist
if [ ! -f /var/www/html/.env ]; then
    cat << EOF > /var/www/html/.env
APP_NAME=Motora
APP_ENV=${APP_ENV:-production}
APP_DEBUG=${APP_DEBUG:-false}
APP_KEY=${APP_KEY}
APP_URL=${APP_URL:-http://localhost}
DB_CONNECTION=sqlite
DB_DATABASE=/var/www/html/database/database.sqlite
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
LOG_CHANNEL=stderr
EOF
fi

# Ensure storage symlink exists
php artisan storage:link || true

# Run database migrations and seed demo cars
echo "Running migrations..."
php artisan migrate --force

echo "Seeding demo catalog..."
php artisan db:seed --force

# Cache configuration, routes, and views for production speed
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

# Grant www-data ownership of database and storage for read/write access
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Execute the main container command (Apache)
exec "$@"
