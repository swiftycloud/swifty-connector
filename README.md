# Swifty Connector

## Installation
### Prepare
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
```

### With SSL
```bash
# if you need SSL, copy nginx config and 
# replace DOMAIN_NAME with your domain
cp nginx.conf.with-ssl nginx.conf
sed -i 's/DOMAIN_NAME/mydomain.com/g' nginx.conf

# and request a certificate for your domain
# (replace DOMAIN_NAME with your domain)
docker run -t --rm \
  -v certs:/etc/letsencrypt \
  -v certs-data:/data/letsencrypt \
  --net=host \
  deliverous/certbot \
  certonly \
  --standalone \
  -d DOMAIN_NAME
```

### Without SSL
```bash
# if there is no need for SSL
cp nginx.conf.without-ssl nginx.conf
```

### Final
```bash
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