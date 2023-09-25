#!/bin/bash
cp .env.example .env

# generate keys
echo generation secret key
php artisan key:generate

# setup laravel
php artisan storage:link

php artisan optimize

php artisan route:clear

php artisan route:cache

php artisan config:clear

php artisan config:cache

php artisan view:clear

php artisan view:cache

# update application cache
php artisan optimize

# migrate database changes
echo migrating db changes
yes | php artisan migrate

# install auth essentials
php artisan passport:install

# start the application
php-fpm -D && nginx -g "daemon off;"