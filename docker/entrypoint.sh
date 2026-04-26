#!/bin/bash
set -e

cd /var/www/html

# Initialize SQLite database if missing (volume mount may be empty)
if [ ! -f database/database.sqlite ]; then
    touch database/database.sqlite
fi

# Initialize demo master database if missing
if [ ! -f database/demos/master.sqlite ]; then
    mkdir -p database/demos
    touch database/demos/master.sqlite
fi

# Ensure writable directories exist
mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache/data \
    storage/app/private storage/app/public bootstrap/cache

# Run migrations
php artisan migrate --force

# Cache config, routes, views for production
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Fix permissions for volume-mounted directories
chown -R www-data:www-data storage bootstrap/cache database
chmod -R 775 storage bootstrap/cache database

exec "$@"