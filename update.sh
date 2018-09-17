#!/bin/bash

git pull &&
docker-compose build &&
docker-compose up -d &&
docker-compose exec app composer install &&
docker-compose exec app php artisan optimize &&
docker-compose exec app php artisan migrate &&
docker-compose exec app npm install &&
docker-compose exec app npm run prod
