# Swifty Connector

## Installation
```bash
git clone https://gitlab.com/swiftyteam/swifty.connector.git
cd swifty.connector
chmod -R 777 ../storage ../bootstrap/cache
cp .env.example .env
nano .env
docker-compose exec app php artisan key:generate
./deploy.sh
```

## Update
```bash
# just run
./deploy.sh
```