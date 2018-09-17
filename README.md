# Swifty Connector

## Installation
```bash
# clone the project from repo
git clone https://gitlab.com/swiftyteam/swifty.connector.git

# set up permissions
cd swifty.connector
chmod -R 777 storage bootstrap/cache

# set up config (important to specify APP_ENV=production for prod)
cp .env.example .env
nano .env

# run deploy
./deploy.sh

# generate key for encrypt
docker-compose exec app php artisan key:generate
```

## Update
```bash
# just run
./deploy.sh
```