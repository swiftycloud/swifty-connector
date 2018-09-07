# Swifty Connector

## Set Up Laradock
```bash
git submodule init
git submodule update
cd laradock
cp env-example .env
docker-compose up -d nginx mysql workspace
```

## Set Up Laravel
```bash
chmod -R 777 ../storage ../bootstrap/cache
docker-compose exec --user=laradock workspace bash
composer install
cp .env.example .env
nano .env
php artisan key:generate
php artisan migrate
```
