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

# create volumes for certbot (if doesn't exists)
docker volume create certs
docker volume create certs-data

# if you need SSL, copy nginx config and everywhere replace example.com
cp nginx.conf.example nginx.conf
nano nginx.conf

# and request a certificate for your domain
docker run -it --rm \
  -v certs:/etc/letsencrypt \
  -v certs-data:/data/letsencrypt \
  deliverous/certbot \
  certonly \
  --webroot --webroot-path=/data/letsencrypt \
  -d example.com

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

## Update SSL certs
```bash
docker run -t --rm \
  -v certs:/etc/letsencrypt \
  -v certs-data:/data/letsencrypt \
  deliverous/certbot \
  renew \
  --webroot --webroot-path=/data/letsencrypt
```