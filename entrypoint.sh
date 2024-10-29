#!/bin/bash
# Step 1: Generate Laravel application key
php artisan key:generate

# Step 2: Run migrations and seeders
php artisan migrate --seed

# Step 3: Generate and configure Passport client
output=$(php artisan passport:client --password --name="Default User Password Grant Client" --provider=users --no-interaction)
client_id=$(echo "$output" | grep -oP '(?<=Client ID: )\d+')
client_secret=$(echo "$output" | grep -oP '(?<=Client secret: ).*')

# Step 4: Insert client credentials into .env (if needed)
sed -i "s/^PASSPORT_CLIENT_USERS_ID=.*/PASSPORT_CLIENT_USERS_ID=$client_id/" .env
sed -i "s/^PASSPORT_CLIENT_USERS_SECRET=.*/PASSPORT_CLIENT_USERS_SECRET=$client_secret/" .env

# Step 5: Generate Passport encryption keys
php artisan passport:keys --force

# Step 6: Clear and optimize configuration, route, and view cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Step 9: Start PHP built-in server
php -S 0.0.0.0:8000 -t public
