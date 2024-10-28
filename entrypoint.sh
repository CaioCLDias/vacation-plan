#!/bin/bash

# Generate the Laravel application key
php artisan key:generate

# Ensure storage directories exist with correct permissions
mkdir -p /var/www/storage/framework/{sessions,views,cache}
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Run migrations and seeders
php artisan migrate --seed

# Generate and configure Passport client
output=$(php artisan passport:client --password --name="Default User Password Grant Client" --provider=users --no-interaction)
client_id=$(echo "$output" | grep -oP '(?<=Client ID: )\d+')
client_secret=$(echo "$output" | grep -oP '(?<=Client secret: ).*')

# Insert client credentials into .env
sed -i "s/^PASSPORT_CLIENT_USERS_ID=.*/PASSPORT_CLIENT_USERS_ID=$client_id/" .env
sed -i "s/^PASSPORT_CLIENT_USERS_SECRET=.*/PASSPORT_CLIENT_USERS_SECRET=$client_secret/" .env

# Generate Passport keys
php artisan passport:keys

# Cache config, routes, and views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start PHP-FPM
exec php-fpm
