# Swifty Connector

## Installation
```bash
# clone the project from repo
git clone https://gitlab.com/swiftyteam/swifty.connector.git

# set up permissions
cd swifty.connector
chmod -R 777 ../storage ../bootstrap/cache

# set up config (important to specify APP_ENV=production for prod)
cp .env.example .env
nano .env

# generate key for encrypt
docker-compose exec app php artisan key:generate

# run deploy
./deploy.sh
```

## Update
```bash
# just run
./deploy.sh
```